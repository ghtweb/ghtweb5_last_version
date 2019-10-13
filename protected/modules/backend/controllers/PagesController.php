<?php

use app\modules\backend\models\forms\PagesForm;
use app\modules\backend\models\search\PagesSearch;

class PagesController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var PagesSearch $model */
        $model = $this->loadModel(PagesSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//pages/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($id = null)
    {
        $model = $this->loadModel(PagesForm::class, $id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Страница сохранена';

                if ($id === null) {
                    $msg = 'Страница добавлена';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//pages/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($id)
    {
        /** @var Pages $model */
        $model = $this->loadModel(Pages::class, $id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус');
        }

        $this->redirectBack();
    }

    public function actionDel($id)
    {
        /** @var Pages $model */
        $model = $this->loadModel(Pages::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Страница <b>' . e($model->title) . '</b> удалена');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить страницу с ID: ' . $id);
        }

        $this->redirectBack();
    }
}