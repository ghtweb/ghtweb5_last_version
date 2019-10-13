<?php

use app\modules\cabinet\models\search\AuthHistorySearch;

class AuthHistoryController extends CabinetBaseController
{
    public function actionIndex()
    {
        $this->render('//cabinet/auth-history', [
            'dataProvider' => (new AuthHistorySearch())->search(),
        ]);
    }
}
