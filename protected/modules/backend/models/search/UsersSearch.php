<?php

namespace app\modules\backend\models\search;

class UsersSearch extends \Users
{
    public function rules()
    {
        $rules = [];

        // user_id
        $rules[] = ['user_id', 'numerical', 'integerOnly' => true];

        // login
        $rules[] = ['login', 'filter', 'filter' => 'trim'];

        // email
        $rules[] = ['email', 'filter', 'filter' => 'trim'];

        // ls_id
        $rules[] = ['ls_id', 'in', 'range' => array_keys($this->getLsList())];

        // role
        $rules[] = ['role', 'in', 'range' => array_keys($this->getRoleList())];

        return $rules;
    }

    /**
     * @return array
     */
    public function getLsList()
    {
        return \Chtml::listData(\Ls::getAllLoginServers(), 'id', 'name');
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare($this->getTableAlias() . '.user_id', $this->user_id, true);
        $criteria->compare($this->getTableAlias() . '.login', $this->login, true);
        $criteria->compare($this->getTableAlias() . '.email', $this->email, true);
        $criteria->compare($this->getTableAlias() . '.ls_id', $this->ls_id);
        $criteria->compare($this->getTableAlias() . '.role', $this->role);

        $criteria->with = ['profile', 'ls', 'referals'];
        $criteria->order = 't.created_at DESC';

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 15,
                'pageVar' => 'page',
            ],
        ]);
    }
}
