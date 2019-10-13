<?php

class DetailAction extends CAction
{
    public function run($slug)
    {
        $dependency = new \TaggedCache\Dependency([
            new \TaggedCache\Tag(News::class . 'slug:' . $slug),
        ]);

        $model = News::model()->cache(3600 * 24, $dependency)->opened()->find('slug = :slug', ['slug' => $slug]);

        if (!$model) {
            throw new CHttpException(404, 'Новость не найдена.');
        }

        app()->getController()->render('//news-detail', [
            'model' => $model,
        ]);
    }
}
 