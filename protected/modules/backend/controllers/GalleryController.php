<?php

use app\modules\backend\models\forms\GalleryForm;
use app\modules\backend\models\search\GallerySearch;

class GalleryController extends BackendBaseController
{
    public function actionIndex()
    {
        $model = $this->loadModel(GallerySearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//gallery/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForm($id = null)
    {
        /** @var GalleryForm $model */
        $model = $this->loadModel(GalleryForm::class, $id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            $model->img = CUploadedFile::getInstance($model, 'img');

            if ($model->save()) {
                $msg = 'Изменения сохранены';

                if (is_null($id)) {
                    $msg = 'Картинка добавлена';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//gallery/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($id)
    {
        /** @var Gallery $model */
        $model = $this->loadModel(Gallery::class, $id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, '<b>' . $model->title . '</b> статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Не удалось изменить статус');
        }

        $this->redirectBack();
    }

    public function actionDel($id)
    {
        /** @var Gallery $model */
        $model = $this->loadModel(Gallery::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Картинка удалена, ID ' . $id);
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить картинку с ID: ' . $id);
        }

        $this->redirectBack();
    }
}