<?php

use app\modules\backend\models\forms\AddingBonusToUserForm;
use app\modules\backend\models\forms\AddMessageToUserForm;
use app\modules\backend\models\forms\AdminCreateForm;
use app\modules\backend\models\forms\AdminEditForm;
use app\modules\backend\models\forms\AdminForm;
use app\modules\backend\models\forms\EditUserForm;
use app\modules\backend\models\search\AdminSearch;
use app\modules\backend\models\search\TransactionsSearch;
use app\modules\backend\models\search\UsersAuthLogsSearch;
use app\modules\backend\models\search\UsersSearch;

class AdminsController extends BackendBaseController
{
    public function beforeAction($action)
    {
        if (!in_array(admin()->getId(), app()->params['superAdminId'])) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Нет доступа к этому разделу!');
            return $this->redirect(['/backend/default/index']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        /** @var AdminSearch $model */
        $model = $this->loadModel(AdminSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//admins/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionAdd()
    {
        $model = new AdminCreateForm();

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            if ($model->create()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Админ создан');
                $this->redirect(['index']);
            }
        }

        $this->render('//admins/add', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $model = new AdminEditForm(Admin::model()->findByPk($id));

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            if ($model->update()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Данные сохранены');
                $this->redirect(['index']);
            }
        }

        $this->render('//admins/edit', [
            'model' => $model,
        ]);
    }

    public function actionDel($id)
    {
        $model = $this->loadModel(Admin::class, $id);
        $model->delete();
        user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Админ удален');
        $this->redirect(['index']);
    }
}
