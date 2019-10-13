<?php

use app\modules\backend\models\forms\AddingBonusToUserForm;
use app\modules\backend\models\forms\AddMessageToUserForm;
use app\modules\backend\models\forms\EditUserForm;
use app\modules\backend\models\search\TransactionsSearch;
use app\modules\backend\models\search\UsersAuthLogsSearch;
use app\modules\backend\models\search\UsersSearch;

class UsersController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var UsersSearch $model */
        $model = $this->loadModel(UsersSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//users/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionReferals($user_id)
    {
        $dataProvider = new CActiveDataProvider('Referals', [
            'criteria' => [
                'condition' => 't.referer = :referer',
                'params' => [':referer' => $user_id],
                'with' => ['referalInfo' => [
                    'with' => 'profile',
                ]]
            ],
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);

        $this->render('//users/referals', [
            'user' => Users::model()->findByPk($user_id),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAuthHistory($user_id)
    {
        /** @var UsersAuthLogsSearch $model */
        $model = $this->loadModel(UsersAuthLogsSearch::class);
        $model->unsetAttributes();
        $model->setAttribute('user_id', $user_id);

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//users/auth-history', [
            'user' => Users::model()->findByPk($user_id),
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр транзакций юзера
     *
     * @param int $user_id
     */
    public function actionTransactionHistory($user_id)
    {
        $model = new TransactionsSearch();
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $dataProvider->criteria->mergeWith([
            'condition' => 't.user_id = :user_id',
            'params' => ['user_id' => $user_id],
        ]);

        $this->render('//users/transaction-history', [
            'user' => Users::model()->findByPk($user_id),
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($user_id)
    {
        $criteria = new CDbCriteria([
            'condition' => 't.user_id = :user_id',
            'params' => [':user_id' => $user_id],
            'with' => ['bonuses' => [
                'order' => 'bonuses.created_at DESC',
                'with' => ['bonusInfo' => [
                    'with' => ['items' => [
                        'with' => ['itemInfo'],
                    ]],
                ]]
            ]],
        ]);

        $model = Users::model()->find($criteria);

        if (!$model) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Юзер не найден.');
            $this->redirect(['index']);
        }

        $this->render('//users/view/index', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление бонуса у юзера
     *
     * @param int $user_id
     * @param int $bonus_id
     *
     * @throws Exception
     */
    public function actionDelBonus($user_id, $bonus_id)
    {
        /** @var UserBonuses $model */
        $model = UserBonuses::model()->find('id = :id AND user_id = :user_id', [':id' => $bonus_id, ':user_id' => $user_id]);

        if (is_null($model)) {
            throw new CHttpException(404, 'Бонус не найден');
        }

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Бонус <b>' . CHtml::encode($model->bonusInfo->title) . '</b> удален');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить бонус');
        }

        $this->redirect(['view', 'user_id' => $user_id]);
    }

    /**
     * Добавление бонуса юзеру
     *
     * @param $user_id
     */
    public function actionAddBonus($user_id)
    {
        if (!request()->isAjaxRequest) {
            $this->redirect(['index']);
        }

        $formModel = new AddingBonusToUserForm($user_id);

        if (request()->isPostRequest && isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            if ($errors = ActiveForm::validate($formModel)) {
                $this->ajax['errors'] = $errors;
            } else {
                if ($formModel->save(false)) {
                    $this->ajax['status'] = true;
                    $this->ajax['msg'] = 'Бонус <b>' . CHtml::encode($formModel->getUserBonuses()->bonusInfo->title) . '</b> добавлен';
                } else {
                    $this->ajax['msg'] = 'Произошла ошибка! Попробуйте повторить позже.';
                }
            }
        } else {
            $this->ajax['status'] = true;
            $this->ajax['title'] = 'Добавление бонуса';
            $this->ajax['body'] = $this->renderPartial('//users/view/add-bonus-form', [
                'model' => $formModel,
            ], true);
        }

        echo CJSON::encode($this->ajax);
    }

    /**
     * Отправляет сообщение
     *
     * @param $user_id
     */
    public function actionAddMessage($user_id)
    {
        $formModel = new AddMessageToUserForm($user_id);

        if (request()->isPostRequest && isset($_POST[CHtml::modelName($formModel)])) {

            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);

            if ($errors = ActiveForm::validate($formModel)) {
                $this->ajax['errors'] = $errors;
            } else {
                $formModel->save(false);
                $this->ajax['status'] = true;
                $this->ajax['msg'] = 'Сообщение отправлено';
            }
        } else {
            // get
            $this->ajax['status'] = true;
            $this->ajax['title'] = 'Добавление сообщения';
            $this->ajax['body'] = $this->renderPartial('//users/view/add-message-form', [
                'model' => $formModel,
            ], true);
        }

        echo json_encode($this->ajax);
    }

    /**
     * Редактирование данных
     *
     * @param $user_id
     */
    public function actionEditData($user_id)
    {
        $userModel = Users::model()->findByPk($user_id);
        $formModel = new EditUserForm($userModel);

        if (request()->isPostRequest && isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);

            if ($errors = ActiveForm::validate($formModel)) {
                $this->ajax['errors'] = $errors;
            } else {

                try {
                    $formModel->editData();
                    $this->ajax['status'] = true;
                    $this->ajax['msg'] = 'Данные сохранены';
                } catch (Exception $e) {
                    $this->ajax['errors'] = $e->getMessage();
                }
            }
        } else {
            // get
            $this->ajax['status'] = true;
            $this->ajax['title'] = 'Изменение данных юзера';
            $this->ajax['body'] = $this->renderPartial('//users/view/edit-data-form', [
                'formModel' => $formModel,
                'userModel' => $userModel,
            ], true);
        }

        echo json_encode($this->ajax);
    }

    /**
     * Купленные предметы в магазине
     *
     * @param int $user_id
     */
    public function actionItemPurchaseLog($user_id)
    {
        $dataProvider = new CActiveDataProvider('PurchaseItemsLog', [
            'criteria' => [
                'condition' => 'user_id = :user_id',
                'params' => [
                    ':user_id' => $user_id,
                ],
                'order' => 't.created_at DESC',
                'with' => ['itemInfo', 'gs'],
            ],
        ]);

        $this->render('//users/item-purchase-log', [
            'user' => Users::model()->findByPk($user_id),
            'dataProvider' => $dataProvider,
        ]);
    }
}
