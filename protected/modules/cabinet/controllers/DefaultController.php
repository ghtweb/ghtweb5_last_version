<?php

class DefaultController extends CabinetBaseController
{
    public function actionIndex()
    {
        $this->render('//cabinet/index');
    }

    public function actionLogout()
    {
        user()->logout();
        $this->redirect(app()->homeUrl);
    }
}
