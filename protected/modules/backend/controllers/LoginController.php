<?php

class LoginController extends BackendBaseController
{
    public $layout = '//layouts/login';

    public function actionIndex()
    {
        Yii::import('application.modules.backend.models.LoginForm');

        $model = new LoginForm();

        if (isset($_POST['LoginForm'])) {
            $model->setAttributes($_POST['LoginForm']);

            if ($model->validate() && $model->login()) {
                $this->redirect(['/backend/default/index']);
            }
        }


        $this->render('//login/index', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        admin()->logout();
        $this->redirect(['/index/default/index']);
    }
}