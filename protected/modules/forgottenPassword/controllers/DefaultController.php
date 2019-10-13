<?php

class DefaultController extends FrontendBaseController
{
    public function actionIndex()
    {
        if (!user()->isGuest) {
            // Если авторизирован
            $this->redirect(['/cabinet/default/index']);
        }

        $model = new ForgottenPasswordForm();

        if (isset($_POST['ForgottenPasswordForm'])) {
            $model->attributes = $_POST['ForgottenPasswordForm'];

            if ($model->validate()) {

                $user = $model->getUser();
                $user->generateResetPasswordHash();
                $user->save(false);

                notify()->forgottenPasswordStep1($model->email, [
                    'hash' => $user->reset_password_hash,
                ]);

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'На Email <b>' . $model->email . '</b> отправлены инструкции по восстановлению пароля.');
                $this->refresh();
            }
        }

        $this->render('//forgotten-password', [
            'model' => $model,
        ]);
    }

    public function actionStep2($hash)
    {
        /** @var Users $user */
        $user = Users::model()->find('reset_password_hash = :rph AND activated = :activated', ['rph' => $hash, 'activated' => Users::STATUS_ACTIVATED]);
        if ($user) {
            if (Users::isResetPasswordHashValid($hash)) {
                $passwordManager = new \app\services\YiiPasswordManager();
                $newPassword = $passwordManager->generate(rand(Users::PASSWORD_MIN_LENGTH, Users::PASSWORD_MAX_LENGTH));
                // Обновляю пароль на сервере
                try {
                    $l2 = l2('ls', $user['ls_id'])->connect();
                    $login = $user['login'];
                    $encryptPassword = $l2->passwordEncrypt($newPassword);

                    $res = $l2->getDb()->createCommand("UPDATE {{accounts}} SET password = :password WHERE login = :login LIMIT 1")
                        ->bindParam('password', $encryptPassword, PDO::PARAM_STR)
                        ->bindParam('login', $login, PDO::PARAM_STR)
                        ->execute();

                    if ($res) {
                        $user->removeResetPasswordHash();
                        $user->save(false);
                        notify()->forgottenPasswordStep2($user['email'], [
                            'password' => $newPassword,
                        ]);
                        user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'На почту указанную при регистрации отправлен новый пароль.');
                    } else {
                        user()->setFlash(FlashConst::MESSAGE_ERROR, 'Произошла ошибка! Попробуйте повторить позже.');
                    }
                } catch (Exception $e) {
                    user()->setFlash(FlashConst::MESSAGE_ERROR, $e->getMessage());
                }
            } else {
                user()->setFlash(FlashConst::MESSAGE_ERROR, 'Ключ для восстановления пароля просрочен.');
            }
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Ключ для восстановления пароля не найден.');
        }

        $this->redirect(['/login/default/index']);
    }
}