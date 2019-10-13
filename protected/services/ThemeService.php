<?php

namespace app\services;

class ThemeService
{
    /**
     * Установка темы приложения
     *
     * @param string $themeName
     * @throws \CException
     */
    public function setTheme($themeName)
    {
        app()->getThemeManager()->setBasePath(\Yii::getPathOfAlias('app') . '/../themes/' . $themeName);
        app()->getThemeManager()->setBaseUrl(app()->getBaseUrl(true) . '/themes/' . $themeName);

        app()->setTheme($themeName);
        app()->viewPath = \Yii::getPathOfAlias('themes') . '/' . $themeName . '/views';
        app()->layoutPath = \Yii::getPathOfAlias('themes') . '/' . $themeName . '/views/layouts';
    }
}
