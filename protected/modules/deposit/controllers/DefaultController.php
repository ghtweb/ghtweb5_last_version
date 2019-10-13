<?php

class DefaultController extends FrontendBaseController
{
    public function actionIndex()
    {
        if (isset($_REQUEST)) {
            Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

            $deposit = new Deposit();

            if (!$deposit->init()) {
                app()->end();
            }

            try {
                /**
                 * @var Transactions $transaction
                 */
                $transaction = $deposit->processed();
                $percents = (float)config('referral_program.percent');

                Yii::log("Deposit::log\n" . print_r($_REQUEST, true), CLogger::LEVEL_INFO, 'application.modules.deposit.controllers.DefaultController::' . __LINE__);

                // Начисляю партнёрку
                if ($percents > 0 && $transaction) {
                    // Смотрю есть ли реферер
                    $referer = Referals::model()->find('referal = :referal', [':referal' => $transaction->user_id]);

                    if ($referer !== null) {
                        $refererProfile = UserProfiles::model()->with('user')->find('t.user_id = :user_id', [':user_id' => $referer->referer]);

                        if ($refererProfile) {
                            $gsModel = Gs::model()->findByPk($transaction->gs_id);

                            // Кол-во предметов которые были куплены, от них будет считаться % рефералу
                            $countItems = $transaction->sum / $gsModel->deposit_course_payments;

                            $profit = $countItems / 100 * $percents;

                            $refererProfile->balance += $profit;

                            $refererProfile->save(false, ['balance', 'updated_at']);

                            // Логирую
                            $dataDb = [
                                'referer_id' => $refererProfile->user_id,
                                'referal_id' => $transaction->user_id,
                                'profit' => $profit,
                                'sum' => $transaction->sum,
                                'percent' => $percents,
                                'transaction_id' => $transaction->id,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];

                            db()->createCommand()->insert('{{referals_profit}}', $dataDb);

                            // Отправляю письмо что баланс реферера был пополнен
                            notify()->rechargeBalanceByReferal($refererProfile->user->email, [
                                'profit' => $profit,
                            ]);

                            // Логирую действие юзера
                            if (app()->params['user_actions_log']) {
                                $log = new UserActionsLog();

                                $log->user_id = $transaction->user_id;
                                $log->action_id = UserActionsLog::ACTION_DEPOSIT_SUCCESS;
                                $log->params = json_encode($dataDb);

                                $log->save();
                            }
                        }
                    }
                }

                echo $deposit->success('Ваш баланс успешно пополнен.');
            } catch (Exception $e) {
                echo $deposit->error($e->getMessage());
            }
        } else {
            $this->redirect(['/index/default/index']);
        }
    }

    public function actionSuccess()
    {
        $this->render('//deposit/success');
    }

    public function actionFail()
    {
        $this->render('//deposit/fail');
    }
}