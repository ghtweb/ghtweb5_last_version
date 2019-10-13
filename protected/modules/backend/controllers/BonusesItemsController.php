<?php

use app\modules\backend\models\forms\BonusesForm;
use app\modules\backend\models\forms\BonusesItemsForm;
use app\modules\backend\models\search\BonusesItemsSearch;

class BonusesItemsController extends BackendBaseController
{
    /**
     * @var Bonuses
     */
    private $_bonusModel;

    public function beforeAction($action)
    {
        $this->_bonusModel = $this->loadModel(Bonuses::class, request()->getQuery('bonus_id'));

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        /** @var BonusesItemsSearch $model */
        $model = $this->loadModel(BonusesItemsSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search($this->_bonusModel->id);

        $this->render('//bonuses/items/index', [
            'bonusModel' => $this->_bonusModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForm()
    {
        $id = request()->getQuery('item_id');

        /** @var BonusesItemsForm $model */
        $model = $this->loadModel(BonusesItemsForm::class, $id);
        $model->bonus_id = $this->_bonusModel->id;

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if ($model->save()) {
                $msg = 'Предмет сохранен.';

                if ($id === null) {
                    $msg = 'Предмет добавлен.';
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index', 'bonus_id' => $this->_bonusModel->id]);
            }
        }

        $this->render('//bonuses/items/form', [
            'bonusModel' => $this->_bonusModel,
            'model' => $model,
        ]);
    }

    public function actionAllow()
    {
        /** @var Bonuses $model */
        $model = $this->loadModel(BonusesItems::class, request()->getQuery('item_id'));

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус');
        }

        $this->redirectBack();
    }

    public function actionDel()
    {
        $id = request()->getQuery('item_id');

        /** @var Bonuses $model */
        $model = $this->loadModel(BonusesItems::class, $id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Бонус <b>' . e($model->title) . '</b> удален');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить бонус с ID: ' . $id);
        }

        $this->redirectBack();
    }

    /**
     * Предметы в бонусе
     *
     * @param int $bonus_id
     * @throws CHttpException
     *
     * @return void
     */
    public function actionItems($bonus_id)
    {
        $bonus = $this->loadBonusesModel($bonus_id);

        $dataProvider = new CActiveDataProvider('BonusesItems', [
            'criteria' => [
                'condition' => 'bonus_id = :bonus_id',
                'params' => [
                    ':bonus_id' => $bonus_id
                ],
                'with' => ['itemInfo'],
            ],
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);

        $this->render('//bonuses/items/index', [
            'bonus' => $bonus,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function loadBonusesModel($id)
    {
        $model = Bonuses::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Добавление предмета к бонусу
     *
     * @param int $bonus_id
     */
    public function actionItemAdd($bonus_id)
    {
        $model = new BonusesItems();

        if (isset($_POST[CHtml::modelName($model)])) {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            $model->bonus_id = $bonus_id;

            if ($model->save()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Предмет добавлен.');
                $this->refresh();
            }
        }

        $this->render('//bonuses/items/form', [
            'bonus' => Bonuses::model()->findByPk($bonus_id),
            'model' => $model,
        ]);
    }

    /**
     * Редактирование предмета в бонусе
     *
     * @param int $bonus_id
     * @param int $item_id
     */
    public function actionItemEdit($bonus_id, $item_id)
    {
        $model = BonusesItems::model()->findByPk($item_id);

        if ($model === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Предмет не найден.');
            $this->redirect(['/backend/bonuses/items']);
        }

        $model->item_name = $model->itemInfo->name . ($model->itemInfo->add_name ? ' (' . $model->itemInfo->add_name . ')' : '');

        if (isset($_POST['BonusesItems'])) {
            $model->setAttributes($_POST['BonusesItems']);

            if ($model->save()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Предмет сохранен.');
                $this->refresh();
            }
        }

        $this->render('//bonuses/items/form', [
            'bonus' => Bonuses::model()->findByPk($bonus_id),
            'model' => $model,
        ]);
    }

    /**
     * Изменения статуса предмету
     *
     * @param int $bonus_id
     * @param int $item_id
     */
    public function actionItemAllow($bonus_id, $item_id)
    {
        $model = BonusesItems::model()->findByPk($item_id);

        if ($model === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Предмет не найден.');
            $this->redirectBack();
        }

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if ($model->save(false, ['status'])) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        }

        $this->redirectBack();
    }

    /**
     * Удаление предмета из бонуса
     *
     * @param int $bonus_id
     * @param int $item_id
     */
    public function actionItemDel($bonus_id, $item_id)
    {
        $model = BonusesItems::model()->with('itemInfo')->findByPk($item_id);

        if ($model === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Предмет не найден.');
            $this->redirectBack();
        }

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Предмет <b>' . e($model->itemInfo->name) . '</b> удален');
        }

        $this->redirectBack();
    }
}