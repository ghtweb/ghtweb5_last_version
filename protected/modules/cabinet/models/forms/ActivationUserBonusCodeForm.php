<?php

namespace app\modules\cabinet\models\forms;

class ActivationUserBonusCodeForm extends \CFormModel
{
    public $bonus_id;
    public $char_id;

    private $characters;

    /**
     * @var \UserBonuses
     */
    private $bonus;

    public function rules()
    {
        $rules = [];

        // bonus_id
        $rules[] = ['bonus_id', 'filter', 'filter' => 'trim'];
        $rules[] = ['bonus_id', 'required'];
        $rules[] = ['bonus_id', 'numerical', 'integerOnly' => true];
        $rules[] = ['bonus_id', 'checkBonus'];

        // char_id
        $rules[] = ['char_id', 'filter', 'filter' => 'trim'];
        $rules[] = ['char_id', 'required'];
        $rules[] = ['char_id', 'numerical', 'integerOnly' => true];
        $rules[] = ['char_id', 'checkChar'];

        return $rules;
    }

    public function checkBonus($attr)
    {
        $criteria = new \CDbCriteria;

        $criteria->condition = 'user_id = :user_id and t.bonus_id = :bonus_id';
        $criteria->params['user_id'] = user()->getId();
        $criteria->params['bonus_id'] = $this->bonus_id;
        $criteria->order = 'created_at DESC';
        $criteria->with = ['bonusInfo' => [
            'joinType' => 'inner join',
            'scopes' => 'opened',
            'with' => ['items' => [
                'scopes' => ['opened'],
                'with' => ['itemInfo' => [
                    'order' => 'name asc'
                ]],
            ]],
        ]];

        /** @var \UserBonuses $userBonusModel */
        $userBonusModel = \UserBonuses::model()->find($criteria);

        if (!$userBonusModel) {
            return $this->addError($attr, 'Бонус код не найден.');
        }

        if ($userBonusModel->isActivated()) {
            return $this->addError($attr, 'Бонус уже активирован (дата активации: ' . $userBonusModel->getUpdatedAt('Y-m-d H:i') . ').');
        }

        if ($userBonusModel->bonusInfo->isTimeEnded()) {
            return $this->addError($attr, 'Время действия бонус кода истекло');
        }

        if (!$userBonusModel->bonusInfo->items) {
            return $this->addError($attr, 'Предметы в бонус коде не найдены');
        }

        $this->bonus = $userBonusModel;
    }

    public function checkChar($attr)
    {
        if (!$this->hasErrors()) {
            if (!isset($this->getCharacters()[$this->char_id])) {
                $this->addError($attr, 'Персонаж не найден');
            }
        }
    }

    public function getCharacters()
    {
        if (!$this->characters) {
            $this->characters = user()->getCharacters();
        }
        return \CHtml::listData($this->characters, 'char_id', 'char_name');
    }

    /**
     * @return \UserBonuses
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    public function getCharName()
    {
        return isset($this->getCharacters()[$this->char_id])
            ? $this->getCharacters()[$this->char_id]
            : 'n/a';
    }

    public function activate()
    {
        if (!$this->validate()) {
            return false;
        }

        try {

            $l2 = l2('gs', user()->gs_id)->connect();

            $items = [];

            foreach ($this->bonus->bonusInfo->items as $i => $item) {
                $items[$i] = [
                    'owner_id' => $this->char_id,
                    'item_id' => $item->item_id,
                    'count' => $item->count,
                    'enchant' => $item->enchant,
                ];

                // @TODO, удалить как сделаю норм запросы
                if ($l2->getQuery() instanceof \PainTeamIt) {
                    $items[$i]['char_name'] = $this->getCharName();
                }
            }

            if (!$l2->multiInsertItem($items)) {
                throw new \Exception('Не удалось активировать бонус.');
            }

            $this->bonus->status = \UserBonuses::STATE_ACTIVE;
            $this->bonus->char_name = $this->getCharName();
            $this->bonus->save(false);

            return true;

        } catch (\Exception $e) {
            $this->addError('bonus_id', $e->getMessage());
        }

        return false;
    }
}
