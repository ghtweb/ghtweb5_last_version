<?php

namespace app\modules\backend\models\search;

class LsSearch extends \Ls
{
    public function rules()
    {
        $rules = [];

        // id
        $rules[] = ['id', 'numerical', 'integerOnly' => true];

        // name
        $rules[] = ['name', 'filter', 'filter' => 'trim'];

        // ip
        $rules[] = ['ip', 'filter', 'filter' => 'trim'];

        // port
        $rules[] = ['port', 'numerical', 'integerOnly' => true];

        // version
        $rules[] = ['version', 'in', 'range' => array_keys(serverVersionList())];

        // status
        $rules[] = ['status', 'in', 'range' => array_keys(\ActiveRecord::getStatusListWithoutDelete())];

        return $rules;
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('port', $this->port, true);
        $criteria->compare('version', $this->version);
        $criteria->compare('status', $this->status);

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 't.created_at DESC'
            ],
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }
}
