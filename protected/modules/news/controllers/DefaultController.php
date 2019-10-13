<?php

class DefaultController extends FrontendBaseController
{
    public function actions()
    {
        return [
            'index' => 'application.modules.news.controllers.actions.ListAction',
            'detail' => 'application.modules.news.controllers.actions.DetailAction',
        ];
    }
}