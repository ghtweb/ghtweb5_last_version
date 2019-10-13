<?php

class DefaultController extends CController
{
    public $layout = '/layouts/master';

    protected function beforeAction($action)
    {
        $file = Yii::getPathOfAlias('app') . '/config/lock';
        if (file_exists($file)) {
            return $this->redirect(['/index/default/index']);
        }
        app()->getErrorHandler()->errorAction = 'install/default/error';
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionStep2()
    {
        $model = new Step2Form();

        if (isset($_POST['Step2Form'])) {
            $model->attributes = $_POST['Step2Form'];

            if ($model->validate()) {
                $body = "<?php\n";
                $body .= "\n";
                $body .= "return [\n";
                $body .= "    'connectionString' => 'mysql:host=" . $model->mysql_host . ";port=" . $model->mysql_port . ";dbname=" . $model->mysql_name . "',\n";
                $body .= "    'emulatePrepare' => TRUE,\n";
                $body .= "    'username' => '" . $model->mysql_user . "',\n";
                $body .= "    'password' => '" . $model->mysql_pass . "',\n";
                $body .= "    'charset' => 'utf8',\n";
                $body .= "    'tablePrefix' => 'ghtweb_',\n";
                $body .= "    'enableProfiling' => YII_DEBUG,\n";
                $body .= "    'enableParamLogging' => true,\n";
                $body .= "    'schemaCachingDuration' => 3600,\n";
                $body .= "];";

                file_put_contents(Yii::getPathOfAlias('app.config') . '/database.php', $body);

                $this->redirect(['step3']);
            }
        }

        $this->render('step2', [
            'model' => $model,
        ]);
    }

    /**
     * Установка миграций
     */
    public function actionStep3()
    {
        $res = '';

        try {
            $res = $this->runMigrationTool();
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'Install::step3');
        }

        $this->render('step3', [
            'res' => $res,
        ]);
    }

    private function runMigrationTool()
    {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $args = ['yiic', 'mymigrate', '--interactive=0'];
        ob_start();
        $runner->run($args);

        return htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    /**
     * Создание админа
     */
    public function actionStep4()
    {
        $model = new Step4Form(new \app\services\YiiPasswordManager());

        if (isset($_POST['Step4Form'])) {
            $model->setAttributes($_POST['Step4Form']);
            if ($model->createAdmin()) {
                $this->redirect(['step5']);
            }
        }

        $this->render('step4', [
            'model' => $model,
        ]);
    }

    /**
     * Finish
     */
    public function actionStep5()
    {
        file_put_contents(Yii::getPathOfAlias('app.config') . '/lock', '');
        $this->render('step5');
    }

    public function actionError()
    {
        if ($error = app()->errorHandler->error) {
            $this->render('error', $error);
        }
    }
}
