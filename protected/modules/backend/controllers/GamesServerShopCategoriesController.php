<?php

use app\modules\backend\models\forms\ShopCategoryForm;
use app\modules\backend\models\search\GamesServerShopCategoriesSearch;

class GamesServerShopCategoriesController extends BackendBaseController
{
    /**
     * @var Gs
     */
    protected $gameServer;

    public function beforeAction($action)
    {
        $this->gameServer = $this->loadModel(Gs::class, request()->getQuery('gs_id'));
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        /** @var GamesServerShopCategoriesSearch $model */
        $model = $this->loadModel(GamesServerShopCategoriesSearch::class);
        $model->gs_id = $this->gameServer->id;

        $this->render('//games-server-shop-categories/index', [
            'model' => $model,
            'dataProvider' => $model->search(),
            'gs' => $this->gameServer,
        ]);
    }

    public function actionForm($category_id = null)
    {
        $formModel = $this->loadModel(ShopCategoryForm::class, $category_id);

        if (isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttribute('gs_id', $this->gameServer->id);
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            if ($formModel->save()) {
                $msg = 'Данные сохранены';
                if (is_null($category_id)) {
                    $msg = 'Категория создана';
                }
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->redirect(['index', 'gs_id' => $this->gameServer->id]);
            }
        }

        $this->render('//games-server-shop-categories/form', [
            'formModel' => $formModel,
            'gs' => $this->gameServer,
        ]);
    }

    public function actionDel($category_id)
    {
        if ($this->loadModel(ShopCategories::class, $category_id)->delete()) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Категория, наборы и предметы в наборах были удалены.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось удалить категорию.');
        }

        $this->redirectBack();
    }

    public function actionAllow($category_id)
    {
        /** @var ShopCategories $model */
        $model = $this->loadModel(ShopCategories::class, $category_id);
        $model->status = ($model->isStatusOn() ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);

        if ($model->save(false)) {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Статус изменен на <b>' . $model->getStatus() . '</b>.');
        } else {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Не удалось изменить статус.');
        }

        $this->redirectBack();
    }
}
