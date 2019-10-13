<?php

use app\services\VersionService;

class DefaultController extends BackendBaseController
{
    public function actionIndex()
    {
        $countGameAccounts = Users::model()->cache(300)->count();
        $countSuccessTransaction = Transactions::model()->cache(300)->count('status = :status', [':status' => Transactions::STATUS_SUCCESS]);
        $countTickets = Tickets::model()->cache(300)->count();

        $this->render('//default/index', [
            'licenseInfo' => $this->license_info,
            'version' => new VersionService(),
            'countGameAccounts' => $countGameAccounts,
            'countSuccessTransaction' => $countSuccessTransaction,
            'countTickets' => $countTickets,
        ]);
    }

    /**
     * Поиск предметов в БД
     */
    public function actionGetItemInfo()
    {
        if (request()->getParam('item-id')) {

            /** @var AllItems $model */
            $model = AllItems::model()->findByPk(request()->getParam('item-id'));

            if (!$model) {
                throw new CHttpException(404);
            }

            $this->ajax['status'] = 'success';
            $this->ajax['name'] = $model->name . ($model->add_name ? ' (' . $model->add_name . ') [' . $model->item_id . ']' : '');
            $this->ajax['icon'] = $model->getIcon();

            echo CJSON::encode($this->ajax);
        } else {
            set_time_limit(0);

            $query = request()->getParam('query');
            $limit = request()->getParam('limit', 0);

            if (strlen($query)) {
                $criteria = new CDbCriteria();
                $criteria->select = 'item_id, name, add_name, icon';

                if ($limit > 0) {
                    $criteria->limit = $limit;
                }

                $criteria->order = 'name';
                $criteria->compare('name', $query, true);

                $model = AllItems::model()->findAll($criteria);
                $items = [];

                foreach ($model as $item) {
                    $items[] = [
                        'id' => $item->item_id,
                        'value' => $item->name . ($item->add_name ? ' (' . $item->add_name . ')' : '') . ' [' . $item->item_id . ']',
                        'icon' => $item->getIcon(),
                    ];
                }

                $json = [
                    'query' => $query,
                    'suggestions' => $items,
                ];

                echo CJSON::encode($json);
            }
        }

        app()->end();
    }

    /**
     * Обновление
     */
    public function actionUpdate()
    {
        $this->update();
    }

    /**
     * Очистка кэша
     */
    public function actionClearCache()
    {
        if (request()->isAjaxRequest) {
            cache()->flush();
            echo 'Кэш удален';
        }
    }
}