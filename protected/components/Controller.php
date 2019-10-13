<?php

use app\services\VersionService;

class Controller extends CController
{
    /**
     * @var string
     */
    public $layout = '//layouts/master';

    /**
     * @var array
     */
    public $breadcrumbs = [];

    public $metaDescription;
    public $metaKeywords;

    /**
     * @var string
     */
    public $pageHeader;

    /**
     * @var array
     */
    public $license_info = [
        'timeEnd' => 0,
        'domain' => '',
    ];

    /**
     * @var array
     */
    protected $ajax = [
        'status' => false, // true|false
        'msg' => '',
    ];

    /**
     * @var Gs
     */
    public $gs;

    /**
     * @var Ls
     */
    public $ls;


    public function init()
    {
        $this->setDevHeaders();

        $this->migrationInstall();

        if (!user()->isGuest) {
            if (!$this->gs) {
                $gsId = user()->getGsId();

                $gsList = Gs::getOpenServers();

                if (isset($gsList[$gsId])) {
                    $this->gs = $gsList[$gsId];
                }
            }

            if (!$this->ls && $this->gs) {
                $lsList = Ls::getOpenLoginServers();
                $this->ls = isset($lsList[$this->gs->id]) ? $lsList[$this->gs->id] : null;
            }
        }

        $this->generateOnlineFile();
    }

    public function performAjaxValidation($model)
    {
        echo ActiveForm::validate($model);
        Yii::app()->end();
    }

    public function redirectBack()
    {
        if (request()->getUrlReferrer() !== null) {
            $this->redirect(request()->getUrlReferrer());
        }

        $this->redirect(['/index/default/index']);
    }

    public function actionError()
    {
        if ($error = app()->errorHandler->error) {
            if (request()->isAjaxRequest) {
                $this->ajax['msg'] = $error;
                echo json_encode($this->ajax);
            } else {
                $this->render('//error', $error);
            }
        }
    }

    /**
     * Установка миграций
     *
     * @return string
     */
    protected function migrationInstall()
    {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $args = ['yiic', 'mymigrate', '--interactive=0'];
        ob_start();
        $runner->run($args);

        return htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    public static function disableProfiler()
    {
        if (Yii::app()->getComponent('log')) {
            foreach (Yii::app()->getComponent('log')->routes as $route) {
                if (in_array(get_class($route), ['CProfileLogRoute', 'CWebLogRoute', 'YiiDebugToolbarRoute'])) {
                    $route->enabled = false;
                }
            }
        }
    }

    /**
     * Генерация online.txt файла
     */
    public function generateOnlineFile()
    {
        if ((bool) config('online.txt.allow')) {
            $file = Yii::getPathOfAlias('webroot') . '/online.txt';

            if (!is_file($file)) {
                Yii::log('Нет файла online.txt', CLogger::LEVEL_ERROR, 'online.txt');

                return;
            }

            if (!is_readable($file)) {
                Yii::log('Нет доступа на чтение файла online.txt', CLogger::LEVEL_ERROR, 'online.txt');

                return;
            }

            if (!is_writable($file)) {
                Yii::log('Нет доступа на запись файла online.txt', CLogger::LEVEL_ERROR, 'online.txt');

                return;
            }

            if ((time() - filemtime($file)) > (int) config('online.txt.time_refresh') * 60) {
                $online = 0;

                /** @var Gs[] $gsList */
                $gsList = Gs::getOpenServers();

                foreach ($gsList as $gs) {
                    if ((bool) $gs->online_txt_allow) {
                        try {
                            $gs_ = l2('gs', $gs->getPrimaryKey())->connect();

                            $online += $gs_->getCountOnlineCharacters();
                        } catch (Exception $e) {
                            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'online.txt');
                        }
                    }
                }

                file_put_contents($file, $online);
                Yii::log('Файл online.txt был обновлён, новый онлайн ' . $online, CLogger::LEVEL_INFO, 'online.txt');
            }
        }
    }

    private function setDevHeaders()
    {
        if (headers_sent()) {
            return;
        }

        $version = (new VersionService())->getVersion();

        header('X-Developer: ght^^');
        header('X-Version: ' . $version ? $version : '--');
        header('X-Website: https://ghtweb.ru');
    }
}
