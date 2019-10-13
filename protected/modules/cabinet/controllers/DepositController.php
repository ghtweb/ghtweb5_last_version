<?php

Yii::import('application.modules.deposit.extensions.Deposit.*');

class DepositController extends CabinetBaseController
{
    public function actionIndex()
    {
        $model = new DepositForm();

        if (isset($_POST['DepositForm']) && $this->gs->deposit_allow) {
            $model->setAttributes($_POST['DepositForm']);

            if ($model->validate()) {
                try {
                    db()->createCommand()->insert('{{transactions}}', [
                        'payment_system' => $this->gs->deposit_payment_system,
                        'user_id' => user()->getId(),
                        'sum' => $model->sum * $this->gs->deposit_course_payments,
                        'count' => $model->sum,
                        'status' => 0,
                        'user_ip' => userIp(),
                        'params' => null,
                        'gs_id' => user()->getGsId(),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    app()->session['transaction_id'] = db()->getLastInsertID();
                    $this->redirect(['/cabinet/deposit/processed']);
                } catch (Exception $e) {
                    Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'deposit');
                    user()->setFlash(FlashConst::MESSAGE_ERROR, 'Произошла ошибка! Попробуйте повторить позже.');
                    $this->refresh();
                }
            }
        }

        $deposit = new Deposit();
        $deposit->init($this->gs->deposit_payment_system);

        $this->render('//cabinet/deposit/index', [
            'model' => $model,
            'deposit' => $deposit,
            'smsList' => (isset($smsList) ? $smsList : []),
            'smsCountries' => (isset($smsCountries) ? $smsCountries : []),
        ]);
    }

    public function actionProcessed()
    {
        $transactionId = app()->session['transaction_id'];

        $model = Transactions::model()->findByPk($transactionId);

        // Транзакция не найдена
        if ($model === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Транзакция не найдена.');
            $this->redirect(['index']);
        }

        if ($model->isPaid()) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Транзакция уже обработана.');
            $this->redirect(['index']);
        }

        $deposit = new Deposit();
        $deposit->init($model->payment_system);

        $this->render('//cabinet/deposit/processed', [
            'model' => $model,
            'fields' => $deposit->getFields($model),
            'formAction' => $deposit->getFormAction(),
            'deposit' => $deposit,
        ]);
    }
}