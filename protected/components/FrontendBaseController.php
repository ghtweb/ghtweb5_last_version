<?php

class FrontendBaseController extends Controller
{
    // До загрузки
    public function beforeAction($action)
    {
        (new \app\services\ThemeService())->setTheme(config('theme'));

        // Реферальная кука
        $referrerCookieName = app()->params['cookie_referer_name'];

        if (!empty($_GET[$referrerCookieName])) {
            $cookie = new CHttpCookie($referrerCookieName, $_GET[$referrerCookieName]);
            $cookie->expire = time() + 3600 * 24 * 7;

            request()->cookies[$referrerCookieName] = $cookie;
        }

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