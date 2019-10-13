<?php

use app\modules\backend\models\forms\LsForm;
use app\modules\backend\models\search\LsSearch;

class LoginServersController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var LsSearch $model */
        $model = $this->loadModel(LsSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//ls/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($ls_id = null)
    {
        $model = $this->loadModel(LsForm::class, $ls_id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Изменения сохранены.';

                if ($ls_id === null) {
                    $msg = 'Логин добавлен.';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//ls/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($ls_id)
    {
        /** @var Ls $model */
        $model = $this->loadModel(Ls::class, $ls_id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус');
        }

        $this->redirectBack();
    }

    public function actionDel($ls_id)
    {
        /** @var Ls $model */
        $model = $this->loadModel(Ls::class, $ls_id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Логин сервер <b>' . e($model->name) . '</b> удален');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить логин с ID: ' . $ls_id);
        }

        $this->redirectBack();
    }

    public function actionAccounts($ls_id)
    {
        $perPage = 20;

        try {
            $l2 = l2('ls', $ls_id)->connect();

            $accounts = $l2->accounts()->queryAll();

            $dataProvider = new CArrayDataProvider($accounts, [
                'id' => 'accounts',
                'sort' => [
                    'attributes' => ['login'],
                ],
                'pagination' => [
                    'pageSize' => $perPage,
                    'pageVar' => 'page',
                ],
            ]);
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'LoginServersController::' . __LINE__);
            user()->setFlash(FlashConst::MESSAGE_ERROR, $e->getMessage());
            $this->redirect(['index']);
        }

        $ls = $this->loadModel(Ls::class, $ls_id);

        $this->render('//ls/accounts/index', [
            'ls' => $ls,
            'dataProvider' => $dataProvider,
            'perPage' => $perPage,
        ]);
    }
}
