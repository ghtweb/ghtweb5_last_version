<?php

class DefaultController extends FrontendBaseController
{
    public function actionIndex()
    {
        if (!config('register.allow')) {
            throw new CHttpException(404, 'Регистрация отключена.');
        }

        $formModel = new RegisterForm();

        if (isset($_POST['RegisterForm'])) {
            $formModel->setAttributes($_POST['RegisterForm']);
            if ($formModel->registerAccount()) {
                if (!config('register.confirm_email')) {
                    user()->setFlash('download-token', $formModel->getRegistrationInfoFileToken());
                }
                $this->refresh();
            }
        }

        $this->render('//register', [
            'model' => $formModel,
        ]);
    }

    public function actionDownloadRegisterData()
    {
        $cacheName = RegisterForm::getCacheNameForRegisterInfoFile();

        if (!($registerData = cache()->get($cacheName))) {
            $this->redirect(['index']);
            return;
        }

        cache()->delete($cacheName);

        list ($login, $clear_login, $password, $email, $gs_id) = $registerData;

        $msg = $this->renderPartial('//register-after-data', [
            'gs' => Gs::model()->findByPk($gs_id),
            'login' => $login,
            'clear_login' => $clear_login,
            'password' => $password,
            'email' => $email,
        ], true);

        request()->sendFile(config('meta.title') . '-' . $email . '.txt', $msg);
    }

    public function actionActivated($_hash)
    {
        $cache = new CFileCache();
        $cache->init();

        $hash = $cache->get('registerActivated' . $_hash);
        $cache->delete('registerActivated' . $_hash);

        // Ключ не найден, возможно пытаются подобрать или истекло время отведенное для активации аккаунта
        if ($hash === false) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Ключ для активации аккаунта не найден.');
            $this->redirect(['index']);
        }

        /** @var Users $user */
        $user = Users::model()->findByPk($hash['user_id']);

        if (!$user) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Аккаунт не найден.');
        } elseif ($user->isActivated()) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Аккаунт уже активирован.');
        } else {
            // Создаю игровой аккаунт
            try {
                $l2 = l2('ls', $user->ls_id)->connect();

                $l2->insertAccount($user->login, $hash['password']);

                $user->setActivated();

                $user->save(false);

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Активация аккаунта прошла успешно. Приятной игры!');

                notify()->registerStep2($hash['email'], [
                    'login' => $user->login,
                    'password' => $hash['password'],
                ]);
            } catch (Exception $e) {
                Yii::log("FAIL, активация аккаута\n" . $e->getMessage(), CLogger::LEVEL_ERROR, __FILE__ . '::' . __LINE__);
                user()->setFlash(FlashConst::MESSAGE_ERROR, $e->getMessage());
            }
        }

        $this->redirect(['index']);
    }
}
