<?php

use app\modules\backend\models\search\TransactionsSearch;

class TransactionsController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var TransactionsSearch $model */
        $model = $this->loadModel(TransactionsSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//transactions/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
}