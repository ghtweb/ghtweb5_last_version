<?php

namespace app\modules\cabinet\models\search;

use TaggedCache\Dependency;
use TaggedCache\Tag;

class TransactionHistorySearch extends \Transactions
{
    public function search()
    {
        $userId = user()->getId();

        $dependency = new Dependency([
            new Tag(\Transactions::class . 'user_id:' . $userId)
        ]);

        $criteria = new \CDbCriteria;

        $criteria->condition = 'user_id = :user_id';
        $criteria->params['user_id'] = $userId;
        $criteria->order = 'created_at DESC';

        $model = \Transactions::model()->cache(3600 * 24, $dependency, 2);

        return new \CActiveDataProvider($model, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)config('cabinet.transaction_history.limit'),
                'pageVar' => 'page',
            ],
        ]);
    }
}
