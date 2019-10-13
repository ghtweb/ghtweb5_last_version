<?php

class ListAction extends CAction
{
    public function run()
    {
        Yii::import('application.models.News');

        $dependency = new \TaggedCache\Dependency([
            new \TaggedCache\Tag(News::class)
        ]);

        $model = News::model()->cache(3600 * 24, $dependency, 2)->opened();

        $dataProvider = new CActiveDataProvider($model, [
            'criteria' => [
                'order' => 't.created_at DESC',
            ],
            'pagination' => [
                'pageSize' => (int)config('news.per_page'),
                'pageVar' => 'page',
            ],
        ]);

        app()->getController()->render('//news', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
