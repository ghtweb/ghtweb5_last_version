<?php

class BackendBaseController extends Controller
{
    public function beforeAction($action)
    {
        if (admin()->getIsGuest() && strpos($_SERVER['REQUEST_URI'], '/login') === false) {
            $this->redirect(admin()->loginUrl);
        }

        app()->getErrorHandler()->errorAction = 'backend/default/error';

        (new \app\services\ThemeService())->setTheme('backend');

        return true;
    }

    public function init()
    {
        Yii::app()->params['countTickets'] = Tickets::getCountNewMessagesForAdmin();

        parent::init();
    }

    /**
     * @param $className
     * @param int|null $id
     * @return ActiveRecord
     * @throws CHttpException
     */
    protected function loadModel($className, $id = null)
    {
        if (is_null($id)) {
            $model = new $className();
        } elseif (is_array($id)) {
            $model = $className::model()->find($id[0] . ' = ?', [$id[1]]);
        } else {
            $model = $className::model()->findByPk($id);
        }

        if (!$model) {
            throw new CHttpException(404, 'Не удалось загрузить модель (' . $className . ').');
        }

        return $model;
    }
}