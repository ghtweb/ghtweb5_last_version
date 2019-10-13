<?php

class SecurityController extends CabinetBaseController
{
    public function actionIndex()
    {
        $model = UserProfiles::model()->find('user_id = :user_id', [':user_id' => user()->getId()]);

        if (isset($_POST['UserProfiles'])) {
            $model->setScenario('security');
            $model->setAttributes($_POST['UserProfiles']);

            if ($model->validate()) {
                $model->save(false, ['protected_ip']);
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Данные сохранены.');
                $this->refresh();
            }
        }

        if ($model->protected_ip && is_array($model->protected_ip)) {
            $model->protected_ip = implode(PHP_EOL, $model->protected_ip);
        }


        $this->render('//cabinet/security', [
            'model' => $model,
        ]);
    }
}
