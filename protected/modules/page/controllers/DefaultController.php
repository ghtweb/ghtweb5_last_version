<?php

class DefaultController extends FrontendBaseController
{
    public function actions()
    {
        return [
            'index' => 'application.modules.page.controllers.actions.DetailAction',
        ];
    }
}