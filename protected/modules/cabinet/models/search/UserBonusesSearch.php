<?php

namespace app\modules\cabinet\models\search;

use TaggedCache\Dependency;
use TaggedCache\Tag;

class UserBonusesSearch extends \UserBonuses
{
    public function search()
    {
        $userId = user()->getId();

        $dependency = new Dependency([
            new Tag(\UserBonuses::class . 'user_id:' . $userId),
            new Tag(\Bonuses::class),
            new Tag(\BonusesItems::class),
        ]);

        $criteria = new \CDbCriteria;

        $criteria->condition = 'user_id = :user_id';
        $criteria->params['user_id'] = $userId;
        $criteria->order = 'created_at DESC';
        $criteria->with = ['bonusInfo' => [
            'joinType' => 'inner join',
            'condition' => 'date_end IS NULL OR date_end > :date_now',
            'params' => ['date_now' => date('Y-m-d H:i:s')],
            'scopes' => 'opened',
            'with' => ['items' => [
                'scopes' => ['opened'],
                'with' => ['itemInfo' => [
                    'order' => 'name asc'
                ]],
            ]],
        ]];

        $model = \UserBonuses::model()->cache(60, $dependency, 3);

        return new \CActiveDataProvider($model, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int) config('cabinet.bonuses.limit'),
                'pageVar' => 'page',
            ],
        ]);
    }
}
