<?php

namespace app\modules\cabinet\models\search;

use TaggedCache\Dependency;
use TaggedCache\Tag;

class AuthHistorySearch extends \UsersAuthLogs
{
    public function search()
    {
        $userId = user()->getId();

        $dependency = new Dependency([
            new Tag(\UsersAuthLogs::class . 'user_id:' . $userId)
        ]);

        $criteria = new \CDbCriteria;

        $criteria->condition = 'user_id = :user_id';
        $criteria->params['user_id'] = $userId;
        $criteria->order = 'created_at DESC';

        $model = \UsersAuthLogs::model()->cache(3600 * 24, $dependency, 2);

        return new \CActiveDataProvider($model, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int) config('cabinet.auth_logs_limit'),
                'pageVar' => 'page',
            ],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Юзер',
            'ip' => 'Ip',
            'user_agent' => 'Браузер',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
        ];
    }
}
