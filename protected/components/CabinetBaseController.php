<?php

class CabinetBaseController extends Controller
{
    // До загрузки
    public function beforeAction($action)
    {
        if (user()->isGuest) {
            $this->redirect(user()->loginUrl);
        }

        (new \app\services\ThemeService())->setTheme(config('theme'));

        return true;
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'CCaptchaAction',
                'backColor' => intval('0x' . str_replace('#', '', config('captcha.bg.color')), 0),
                'minLength' => config('captcha.min_length'),
                'maxLength' => config('captcha.max_length'),
                'foreColor' => intval('0x' . str_replace('#', '', config('captcha.font.color')), 0),
                'width' => config('captcha.width'),
                'height' => config('captcha.height'),
            ],
        ];
    }
}