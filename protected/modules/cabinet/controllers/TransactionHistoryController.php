<?php

use app\modules\cabinet\models\search\TransactionHistorySearch;

class TransactionHistoryController extends CabinetBaseController
{
    public function actionIndex()
    {
        $this->render('//cabinet/transaction-history', [
            'dataProvider' => (new TransactionHistorySearch())->search(),
        ]);
    }
}
