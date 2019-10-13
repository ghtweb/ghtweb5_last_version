<?php

class ReferalsController extends CabinetBaseController
{
    public function actionIndex()
    {
        $dependency = new CDbCacheDependency('SELECT COUNT(0) FROM {{referals_profit}} WHERE referer_id = :referer_id');
        $dependency->params = ['referer_id' => user()->getId()];
        $model = ReferalsProfit::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, [
            'criteria' => [
                'order' => 'created_at DESC',
                'condition' => 'referer_id = :user_id',
                'params' => ['user_id' => user()->getId()],
            ],
            'pagination' => [
                'pageSize' => (int)config('cabinet.referals.limit'),
                'pageVar' => 'page',
            ],
        ]);

        $this->render('//cabinet/referrals', [
            'dataProvider' => $dataProvider,
            'countReferals' => count(Referals::getByUserId(user()->getId())),
        ]);
    }
}
