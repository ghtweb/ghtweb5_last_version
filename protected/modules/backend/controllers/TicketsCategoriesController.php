<?php

use app\modules\backend\models\forms\TicketsCategoriesForm;
use app\modules\backend\models\search\TicketsCategoriesSearch;

class TicketsCategoriesController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var TicketsCategoriesSearch $model */
        $model = $this->loadModel(TicketsCategoriesSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//tickets/categories/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($id = null)
    {
        $model = $this->loadModel(TicketsCategoriesForm::class, $id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Категория добавлена.';

                if (is_numeric($id)) {
                    $msg = 'Изменения сохранены.';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//tickets/categories/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($id)
    {
        /** @var TicketsCategories $model */
        $model = $this->loadModel(TicketsCategories::class, $id);

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
        /** @var TicketsCategories $model */
        $model = $this->loadModel(TicketsCategories::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Категория с ID <b>' . $model->id . '</b> удалена');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить категорию с ID: ' . $id);
        }

        $this->redirectBack();
    }
}