<?php

use app\modules\backend\models\forms\BonusCodesForm;
use app\modules\backend\models\forms\BonusesForm;
use app\modules\backend\models\search\BonusCodesSearch;

class BonusCodesController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var BonusCodesSearch $model */
        $model = $this->loadModel(BonusCodesSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//bonuses/codes/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionForm($id = null)
    {
        /** @var BonusesForm $model */
        $model = $this->loadModel(BonusCodesForm::class, $id);

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Бонус код сохранен.';

                if ($id === null) {
                    $msg = 'Бонус код добавлен.';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index']);
            }
        }

        $this->render('//bonuses/codes/form', [
            'model' => $model,
        ]);
    }

    public function actionAllow($id)
    {
        /** @var BonusCodes $model */
        $model = $this->loadModel(BonusCodes::class, $id);

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
        /** @var BonusCodes $model */
        $model = $this->loadModel(BonusCodes::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Бонус код <b>' . e($model->code) . '</b> удален');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить бонус код с ID: ' . $id);
        }

        $this->redirectBack();
    }

    /**
     * Генерация бонус кода
     * @param int $parts
     * @param int $length
     * @param string $divider
     */
    public function actionGenerateCode($parts = 4, $length = 4, $divider = '-')
    {
        $code = '';

        for ($i = 0; $i < $parts; $i++) {
            $code .= strtoupper(randomString($length)) . $divider;
        }

        echo substr($code, 0, -1);
    }
}