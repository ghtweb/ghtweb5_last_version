<?php

class DetailAction extends CAction
{
    public function run($page_name = 'index')
    {
        $dependency = new \TaggedCache\Dependency([
            new \TaggedCache\Tag(Pages::class . 'page:' . $page_name)
        ]);

        $model = Pages::model()->cache(3600, $dependency)->opened()->find('page = :page', [
            'page' => $page_name,
        ]);

        if (is_null($model)) {
            throw new CHttpException(404, 'Страница не найдена');
        }

        app()->getController()->render('//page', [
            'model' => $model,
        ]);
    }
}
