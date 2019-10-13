<?php

namespace app\modules\cabinet\models\forms;

class ActivationBonusCodeForm extends \CFormModel
{
    public $code;
    private $bonusCode;

    public function rules()
    {
        $rules = [];

        // code
        $rules[] = ['code', 'filter', 'filter' => 'trim'];
        $rules[] = ['code', 'required'];
        $rules[] = ['code', 'checkCode'];

        return $rules;
    }

    public function checkCode($attr)
    {
        if (!$this->hasErrors()) {
            $code = $this->getBonusCode();
            if (!$code) {
                return $this->addError($attr, 'Бонус код не найден.');
            }
            if ($code->bonusInfo->isTimeEnded()) {
                return $this->addError($attr, 'Время действия кода закончилось.');
            }
            if ($code->limitIsOver()) {
                return $this->addError($attr, 'Код больше не действителен.');
            }
        }
    }

    private function getBonusCode()
    {
        if (!$this->bonusCode) {
            $this->bonusCode = \BonusCodes::findByCode($this->code);
        }
        return $this->bonusCode;
    }

    public function attributeLabels()
    {
        return [
            'code' => 'Код',
        ];
    }

    public function activated()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = db()->beginTransaction();

        try {

            $bonusCode = $this->getBonusCode();

            $userBonuses = new \UserBonuses();
            $userBonuses->bonus_id = $bonusCode->bonus_id;
            $userBonuses->user_id = user()->getId();
            $userBonuses->save(false);

            $log = new \BonusCodesActivatedLogs();
            $log->code_id = $bonusCode->id;
            $log->user_id = user()->getId();
            $log->save(false);

            $bonusCode->count_activation++;
            $bonusCode->save(false);

            if (app()->params['user_actions_log']) {
                $log = new \UserActionsLog();
                $log->user_id = user()->getId();
                $log->action_id = \UserActionsLog::ACTION_ACTIVATED_BONUS_CODE;
                $log->params = json_encode($bonusCode->getAttributes());
                $log->save(false);
            }

            $transaction->commit();

            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            $this->addError('code', $e->getMessage());
        }

        return false;
    }
}
