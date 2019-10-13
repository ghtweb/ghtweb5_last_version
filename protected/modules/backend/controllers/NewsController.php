<?php

use app\modules\backend\models\forms\NewsForm;
use app\modules\backend\models\search\NewsSearch;

class NewsController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var NewsSearch $model */
        $model = $this->loadModel(NewsSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//news/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($id = null)
    {
        /** @var NewsForm $model */
        $model = $this->loadModel(NewsForm::class, $id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            $model->user_id = admin()->getId();
            $model->img = CUploadedFile::getInstance($model, 'img');

            if ($model->save()) {
                $msg = 'Новость сохранена.';
                if ($id === null) {
                    $msg = 'Новость добавлена.';
                }
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//news/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($id)
    {
        /** @var News $model */
        $model = $this->loadModel(News::class, $id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус');
        }

        $this->redirectBack();
    }

    public function actionDel($id)
    {
        /** @var News $model */
        $model = $this->loadModel(News::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Новость с ID <b>' . $model->id . '</b> удалена');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить новость с ID: ' . $id);
        }

        $this->redirectBack();
    }
}