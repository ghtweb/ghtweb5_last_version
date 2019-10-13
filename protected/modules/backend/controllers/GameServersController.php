<?php

use app\modules\backend\models\forms\GsForm;
use app\modules\backend\models\search\GsSearch;

class GameServersController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var GsSearch $model */
        $model = $this->loadModel(GsSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//gs/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($gs_id = null)
    {
        $model = $this->loadModel(GsForm::class, $gs_id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Изменения сохранены.';

                if ($gs_id === null) {
                    $msg = 'Сервер добавлен.';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//gs/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($gs_id)
    {
        /** @var Gs $model */
        $model = $this->loadModel(Gs::class, $gs_id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус');
        }

        $this->redirectBack();
    }

    public function actionDel($gs_id)
    {
        /** @var Gs $model */
        $model = $this->loadModel(Gs::class, $gs_id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Сервер <b>' . e($model->name) . '</b> удален');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить сервер с ID: ' . $gs_id);
        }

        $this->redirectBack();
    }
}
