<?php

use app\modules\backend\models\forms\ShopPackItemsForm;
use app\modules\backend\models\search\GamesServerShopCategoriesPacksItemsSearch;

Yii::import('app.modules.backend.controllers.GamesServerShopCategoriesPacksController');

class GamesServerShopCategoriesPacksItemsController extends GamesServerShopCategoriesPacksController
{
    /**
     * @var ShopItemsPacks
     */
    private $pack;

    public function beforeAction($action)
    {
        $this->pack = $this->loadModel(ShopItemsPacks::class, request()->getQuery('pack_id'));
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $model = $this->loadModel(GamesServerShopCategoriesPacksItemsSearch::class);
        $dataProvider = $model->search($this->pack->id);

        $this->render('//games-server-shop-categories-packs-items/index', [
            'gs' => $this->gameServer,
            'category' => $this->category,
            'pack' => $this->pack,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForm($pack_id = null)
    {
        /** @var ShopPackItemsForm $formModel */
        $formModel = $this->loadModel(ShopPackItemsForm::class, request()->getParam('item_id'));
        $formModel->setGs($this->gameServer);

        if (isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttribute('pack_id', $this->pack->id);
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            if ($formModel->save()) {
                $msg = 'Данные сохранены';
                if (is_null($pack_id)) {
                    $msg = 'Предмет добавлен';
                }
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index', 'gs_id' => $this->gameServer->id, 'category_id' => $this->category->id, 'pack_id' => $this->pack->id]);
            }
        }

        $this->render('//games-server-shop-categories-packs-items/form', [
            'gs' => $this->gameServer,
            'category' => $this->category,
            'pack' => $this->pack,
            'formModel' => $formModel,
        ]);
    }

    public function actionAllow($item_id)
    {
        /** @var ShopItems $model */
        $model = $this->loadModel(ShopItems::class, $item_id);
        $model->status = ($model->isStatusOn() ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус.');
        }

        $this->redirectBack();
    }

    public function actionDel($item_id)
    {
        /** @var ShopItems $model */
        $model = $this->loadModel(ShopItems::class, $item_id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Предмет <b>' . CHtml::encode($model->itemInfo->name) . '</b> из набора <b>' . CHtml::encode($model->pack->title) . '</b> был удален.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить предмет.');
        }

        $this->redirectBack();
    }
}
