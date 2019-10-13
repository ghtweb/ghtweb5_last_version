<?php

use app\modules\cabinet\models\search\ShopSearch;

class ShopController extends CabinetBaseController
{
    public function actionIndex($category_link = null)
    {
        $model = new ShopSearch();
        $data = $model->search($category_link, $this->gs->id);

        if (!$data) {
            throw new CHttpException(404, 'Магазин недоступен');
        }

        $this->render('//cabinet/shop/index', [
            'categories' => $this->getCategories(),
            'category' => $data['category'],
            'characters' => user()->getCharacters(),
            'dataProvider' => $data['dataProvider'],
            'gs' => $this->gs,
        ]);
    }

    public function getCategories()
    {
        return ShopCategories::getOpenCategories(user()->gs_id);
    }

    /**
     * Покупка предметов
     *
     * @param string $category_link
     *
     * @return void
     */
    public function actionBuy($category_link)
    {
        if (!request()->getIsPostRequest() || (!isset($_POST['pack_id']) || !filter_var($_POST['pack_id'], FILTER_VALIDATE_INT)) && $_POST['char_id'] > 0) {
            $this->redirect(['index']);
        }

        // Предметы не выбраны
        if (!isset($_POST['items']) || !is_array($_POST['items']) || count($_POST['items']) < 1) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Выберите предметы.');
            $this->redirectBack();
        }

        // Не выбран персонаж
        if (!isset($_POST['char_id']) || !filter_var($_POST['char_id'], FILTER_VALIDATE_INT) && $_POST['char_id'] > 0) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Выберите персонажа.');
            $this->redirectBack();
        }

        $char_id = (int)$_POST['char_id'];
        $packId = (int)$_POST['pack_id'];
        $items = [];

        foreach ($_POST['items'] as $item) {
            if (!isset($item['id']) || !isset($item['count']) || $item['count'] < 1) {
                continue;
            }

            $items[(int) $item['id']] = (int) $item['count'];
        }

        if (!$items) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Выберите предметы.');
            $this->redirectBack();
        }

        // Проверяю есть ли такой раздел
        $category = null;

        foreach ($this->getCategories() as $row) {
            if ($row->link == $category_link) {
                $category = $row;
                break;
            }
        }

        // Пытаюстся купить в закрытой/несуществующей категории
        if (!$category) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Покупка невозможна.');
            $this->redirectBack();
        }

        // Проверяю есть ли такой набор
        $pack = db()->createCommand("SELECT id FROM {{shop_items_packs}} WHERE id = ? AND category_id = ? AND status = ?")
            ->queryRow(true, [$packId, $category->getPrimaryKey(), ActiveRecord::STATUS_ON]);

        // Набор не найден
        if (!$pack) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Покупка невозможна.');
            $this->redirectBack();
        }

        // Ищю предметы в наборе
        $criteria = new CDbCriteria([
            'condition' => 'pack_id = :pack_id',
            'params' => [
                'pack_id' => $pack['id'],
            ],
            'scopes' => ['opened'],
            'with' => ['itemInfo']
        ]);

        $criteria->addInCondition('id', array_keys($items));

        /** @var ShopItems[] $itemsDb */
        $itemsDb = ShopItems::model()->findAll($criteria);

        // Если предметы не найдены
        if (!$itemsDb) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Покупка невозможна.');
            $this->redirectBack();
        }


        // Общая сумма
        $totalSum = 0;
        $itemsInfo = [];

        // Подсчитываю что почём
        foreach ($itemsDb as $item) {
            $id = (int)$item->getPrimaryKey();
            $discount = (float)$item->discount;
            $cost = (float)$item->cost;
            //$costDiscount = ShopItems::costAtDiscount($cost, $discount);
            $count = (int)$item->count;
            $sum = 0;
            $costPerOne = (float)$cost / $count;
            $costPerOneDiscount = ShopItems::costAtDiscount($costPerOne, $discount);

            $itemsInfo[$id] = [
                'id' => $id,
                'item_id' => (int)$item->item_id,
//                'cost' => $costPerOneDiscount,
                'cost_per_one' => $cost / $count,
                'cost_per_one_discount' => $costPerOneDiscount,
                'discount' => $discount,
                'name' => $item->itemInfo->getFullName(),
                'desc' => $item->itemInfo->description,
                'enchant' => (int)$item->enchant,
            ];

            if (($count = $items[$id]) > 0) {
                $sum += $count * $costPerOneDiscount;
            }

            //$itemsInfo[$id]['total_sum_o'] = $sum;

            if ($sum > 1) {
                $sum = round($sum, 2);
            } else {
                $sum = ceil($sum);
            }

            $itemsInfo[$id]['total_sum'] = $sum;
            $itemsInfo[$id]['count'] = $count;

            $totalSum += $sum;
        }

        // Проверка баланса
        if ($totalSum < 0 || user()->get('balance') < $totalSum) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'У Вас недостаточно средств на балансе для совершения сделки.');
            $this->redirectBack();
        }

        // Смотрю персонажа на сервере
        try {
            $l2 = l2('gs', user()->getGsId())->connect();

            $charIdFieldName = $l2->getField('characters.char_id');
            $login = user()->get('login');

            $character = $l2->getDb()->createCommand("SELECT online FROM {{characters}} WHERE account_name = :account_name AND " . $charIdFieldName . " = :char_id LIMIT 1")
                ->bindParam('account_name', $login, PDO::PARAM_STR)
                ->bindParam('char_id', $char_id, PDO::PARAM_INT)
                ->queryRow();

            if (!$character) {
                user()->setFlash(FlashConst::MESSAGE_ERROR, 'Персонаж на сервере не найден.');
                $this->redirectBack();
            }

            if ($character['online'] != 0) {
                user()->setFlash(FlashConst::MESSAGE_ERROR, 'Персонаж НЕ должен находится в игре.');
                $this->redirectBack();
            }

            // Подготавливаю предметы для БД
            $itemsToDb = [];

            foreach ($itemsInfo as $item) {
                $itemsToDb[] = [
                    'owner_id' => $char_id,
                    'item_id' => $item['item_id'],
                    'count' => $item['count'],
                    'enchant' => $item['enchant'],
                ];
            }

            // Накидываю предмет(ы) в игру
            $res = $l2->multiInsertItem($itemsToDb);

            if ($res) {
                $userId = user()->getId();

                if ($totalSum > 0) {
                    db()->createCommand("UPDATE {{user_profiles}} SET balance = balance - :total_sum WHERE user_id = :user_id LIMIT 1")
                        ->execute([
                            'total_sum' => $totalSum,
                            'user_id' => $userId,
                        ]);
                }

                // Записываю лог о сделке
                $itemsLog = [];
                $itemList = '';

                foreach ($itemsDb as $i => $item) {
                    $itemId = $item->getPrimaryKey();
                    $itemList .= ++$i . ') ' . $item->itemInfo->getFullName() . ' x' . $itemsInfo[$itemId]['count'] . ' (' . $itemsInfo[$itemId]['total_sum'] . ' ' . $this->gs->currency_name . ')<br>';

                    $itemsLog[] = [
                        'pack_id' => $item->pack_id,
                        'item_id' => $item->item_id,
                        'description' => $item->description,
                        'cost' => $item->cost,
                        'discount' => $item->discount,
                        'count' => $itemsInfo[$itemId]['count'],
                        'enchant' => $item->enchant,
                        'user_id' => user()->getId(),
                        'char_id' => $char_id,
                        'gs_id' => user()->getGsId(),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }

                if ($itemsLog) {
                    $builder = db()->schema->commandBuilder;
                    $builder->createMultipleInsertCommand('{{purchase_items_log}}', $itemsLog)->execute();
                }

                // Логирую действие юзера
                if (app()->params['user_actions_log']) {
                    $log = new UserActionsLog();

                    $log->user_id = user()->getId();
                    $log->action_id = UserActionsLog::ACTION_DEPOSIT_SUCCESS;
                    $log->params = json_encode($itemsLog);

                    $log->save(false);
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Сделка прошла успешно, Нижеперечисленные предметы в ближайшее время будут зачислены на Вашего персонажа.<br><b>' . $itemList . '</b>');

                notify()->shopBuyItems(user()->get('email'), [
                    'items' => $itemsInfo,
                    'currency' => $this->gs->currency_name,
                ]);
                }
        } catch (Exception $e) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Произошла ошибка! Попробуйте повторить позже.');
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'shop_buy');
        }

        $this->redirectBack();

    }
}