<?php

namespace app\modules\cabinet\models\search;

use TaggedCache\Dependency;
use TaggedCache\Tag;

class TicketsSearch extends \Tickets
{
    public function search()
    {
        $userId = user()->getId();
        $dependency = new Dependency([
            new Tag(\Tickets::class . 'user_id:' . $userId),
        ]);

        $model = \Tickets::model()->cache(3600 * 24, $dependency, 2);

        return new \CActiveDataProvider($model, [
            'criteria' => [
                'condition' => 'user_id = :user_id',
                'params' => [':user_id' => $userId],
                'order' => 't.status DESC, t.created_at DESC',
                'with' => [
                    'category' => [
                        'scopes' => ['opened'],
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => (int) config('cabinet.tickets.limit'),
                'pageVar' => 'page',
            ],
        ]);
    }
//
//    public function attributeLabels()
//    {
//        return [
//            'user_id' => 'Юзер',
//            'ip' => 'Ip',
//            'user_agent' => 'Браузер',
//            'status' => 'Статус',
//            'created_at' => 'Дата создания',
//        ];
//    }
}
