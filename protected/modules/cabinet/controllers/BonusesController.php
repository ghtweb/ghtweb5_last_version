<?php

use app\modules\cabinet\models\forms\ActivationBonusCodeForm;
use app\modules\cabinet\models\forms\ActivationUserBonusCodeForm;
use app\modules\cabinet\models\search\UserBonusesSearch;

class BonusesController extends CabinetBaseController
{
    public function actionIndex()
    {
        $formModel = new ActivationUserBonusCodeForm();

        if (isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            if ($formModel->activate()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Бонус "' . $formModel->getBonus()->bonusInfo->title . '" активирован.');
                $this->refresh();
            }
        }

        $this->render('//cabinet/bonuses/index', [
            'dataProvider' => (new UserBonusesSearch())->search(),
            'formModel' => $formModel,
        ]);
    }

    public function actionBonusCode()
    {
        $formModel = new ActivationBonusCodeForm();

        if (isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            if ($formModel->activated()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Бонус код ' . $formModel->code . ' активирован.');
                $this->refresh();
            }
        }

        $this->render('//cabinet/bonuses/bonus-code', [
            'formModel' => $formModel,
        ]);
    }
}
