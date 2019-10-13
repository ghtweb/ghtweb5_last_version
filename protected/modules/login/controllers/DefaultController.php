<?php

class DefaultController extends FrontendBaseController
{
    public function actionIndex()
    {
        // Если уже авторизован
        if (!user()->isGuest) {
            $this->redirect(['/cabinet/default/index']);
        }

        $model = new LoginForm();

        if (isset($_POST['LoginForm']) && !$model->isBlockedForm() && $model->getGsList()) {
            $model->setAttributes($_POST['LoginForm']);

            if ($model->validate() && $model->login()) {
                $this->redirect(['/cabinet/default/index']);
            }
        }

        $this->render('//login', [
            'model' => $model,
        ]);
    }
}