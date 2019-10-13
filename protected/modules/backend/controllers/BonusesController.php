<?php

use app\modules\backend\models\forms\BonusesForm;
use app\modules\backend\models\search\BonusesSearch;

class BonusesController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var BonusesSearch $model */
        $model = $this->loadModel(BonusesSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//bonuses/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($id = null)
    {
        $model = $this->loadModel(BonusesForm::class, $id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Бонус сохранен.';

                if ($id === null) {
                    $msg = 'Бонус добавлен.';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//bonuses/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($id)
    {
        /** @var Bonuses $model */
        $model = $this->loadModel(Bonuses::class, $id);

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
        /** @var Bonuses $model */
        $model = $this->loadModel(Bonuses::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Бонус <b>' . e($model->title) . '</b> удален');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить бонус с ID: ' . $id);
        }

        $this->redirectBack();
    }
}
