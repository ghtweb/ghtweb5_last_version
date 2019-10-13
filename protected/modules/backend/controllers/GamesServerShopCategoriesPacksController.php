<?php

use app\modules\backend\models\forms\ShopItemsPackForm;
use app\modules\backend\models\search\GamesServerShopCategoriesPacksSearch;

Yii::import('app.modules.backend.controllers.GamesServerShopCategoriesController');

class GamesServerShopCategoriesPacksController extends GamesServerShopCategoriesController
{
    /**
     * @var ShopCategories
     */
    protected $category;

    public function beforeAction($action)
    {
        $this->category = $this->loadModel(ShopCategories::class, request()->getQuery('category_id'));
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $model = $this->loadModel(GamesServerShopCategoriesPacksSearch::class);
        $dataProvider = $model->search($this->category->id);

        $this->render('//games-server-shop-categories-packs/index', [
            'gs' => $this->gameServer,
            'category' => $this->category,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForm($pack_id = null)
    {
        /** @var ShopItemsPackForm $formModel */
        $formModel = $this->loadModel(ShopItemsPackForm::class, $pack_id);
        $formModel->setAttribute('category_id', $this->category->id);
        $formModel->setMaxSort();

        if (isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            $formModel->img = CUploadedFile::getInstance($formModel, 'img');
            if ($formModel->save()) {
                $msg = 'Данные сохранены';
                if (is_null($pack_id)) {
                    $msg = 'Нобор создан';
                }
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index', 'gs_id' => $this->gameServer->id, 'category_id' => $this->category->id]);
            }
        }

        $this->render('//games-server-shop-categories-packs/form', [
            'gs' => $this->gameServer,
            'category' => $this->category,
            'formModel' => $formModel,
        ]);
    }

    public function actionDel($pack_id)
    {
        /** @var ShopItemsPacks $model */
        $model = $this->loadModel(ShopItemsPacks::class, $pack_id);

        if ($model->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Набор <b>' . CHtml::encode($model->title) . '</b> и предметы в наборе были удалены.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить набор.');
        }

        $this->redirectBack();
    }

    public function actionAllow($pack_id)
    {
        /** @var ShopItemsPacks $model */
        $model = $this->loadModel(ShopItemsPacks::class, $pack_id);
        $model->status = ($model->isStatusOn() ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус.');
        }

        $this->redirectBack();
    }

    public function actionDelImg($pack_id)
    {
        if (!request()->isAjaxRequest) {
            $this->redirect(['index', 'gs_id' => $this->gameServer->id, 'category_id' => $this->category->id]);
        }

        /** @var ShopItemsPacks $model */
        $model = $this->loadModel(ShopItemsPacks::class, $pack_id);
        $model->deleteImage();

        if ($model->save(false)) {
            $this->ajax['status'] = true;
            $this->ajax['msg'] = 'Картинка удалена';
        } else {
            $this->ajax['errors'] = $model->getErrors();
        }

        echo CJSON::encode($this->ajax);
    }
}
