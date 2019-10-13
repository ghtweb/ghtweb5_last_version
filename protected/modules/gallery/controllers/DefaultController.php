<?php

class DefaultController extends FrontendBaseController
{
    public function actionIndex()
    {
        $dependency = new \TaggedCache\Dependency([
            new \TaggedCache\Tag(Gallery::class)
        ]);

        $model = Gallery::model()->cache(3600, $dependency, 2)->opened();

        $dataProvider = new CActiveDataProvider($model, [
            'criteria' => [
                'order' => 'sort',
            ],
            'pagination' => [
                'pageSize' => (int)config('gallery.limit'),
                'pageVar' => 'page',
            ],
        ]);

        $this->render('//gallery', [
            'dataProvider' => $dataProvider,
        ]);
    }
}