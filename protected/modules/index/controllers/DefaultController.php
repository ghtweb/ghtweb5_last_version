<?php

Yii::import('modules.news.models.News');

class DefaultController extends FrontendBaseController
{
    public function actions()
    {
        return [
            'list' => 'application.modules.news.controllers.actions.ListAction',
            'page' => 'application.modules.page.controllers.actions.DetailAction',
        ];
    }

    public function actionIndex()
    {
        switch (config('index.type')) {
            case 'news':
                $this->createAction('list')->run();
                break;
            case 'rss':
                $this->rss();
                break;
            case 'page':
                $this->createAction('page')->run(config('index.page'));
                break;
            default:
                //$this->render('//index');
        }
    }

    private function rss()
    {
        Yii::import('ext.Rss.Rss');

        $rss = new Rss(config('index.rss.url'), config('index.rss.date_format'), config('index.rss.cache'));

        try {
            $dataProvider = new CArrayDataProvider($rss->parse(), [
                'pagination' => [
                    'pageSize' => (int)config('index.rss.limit'),
                    'pageVar' => 'page',
                ]
            ]);
        } catch (Exception $e) {
            throw new CHttpException(404, $e->getMessage());
        }


        $this->render('//rss-news', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
