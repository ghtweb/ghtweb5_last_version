<?php
/**
 * @var Gs $gs
 * @var ShopCategories $category
 * @var CActiveDataProvider $dataProvider
 * @var ShopItemsPacks $pack
 */

$title__ = 'Наборы';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    'Сервера' => ['/backend/gameServers/index'],
    $gs->name . ' - ' . 'Магазин' => ['/backend/gamesServerShopCategories/index', 'gs_id' => $gs->id],
    'Наборы для категории - ' . $category->name => ['/backend/gamesServerShopCategoriesPacks/index', 'gs_id' => $gs->id, 'category_id' => $category->id],
    'Предметы в наборе - ' . $pack->title,
] ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo CHtml::link('Добавить предмет', ['form', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id], ['class' => 'btn btn-primary btn-block']) ?>

    <hr>

    <style>
        .pack-img img {
            width: 150px;
        }

        .desc h3 {
            margin-top: 0;
        }
    </style>

    <table class="table">
        <colgroup>
            <col style="width: 5%;">
            <col>
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
        </colgroup>
        <thead>
        <tr>
            <th></th>
            <th>Название</th>
            <th>Стоимость</th>
            <th>Скидка</th>
            <th>Кол-во</th>
            <th>Заточка</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($items = $dataProvider->getData()) { ?>
            <?php foreach ($items as $item) { ?>
                <tr>
                    <td><?php echo $item->itemInfo->getIcon() ?></td>
                    <td>
                        <?php echo e($item->itemInfo->name) . ' [' . $item->itemInfo->item_id . ']' ?>
                        <?php if ($item->itemInfo->add_name) { ?>
                            (<?php echo $item->itemInfo->add_name ?>)
                        <?php } ?>
                    </td>
                    <td><?php echo $item->cost ?></td>
                    <td><?php echo $item->discount ?><?php echo ($item->discount > 0 ? '%' : '') ?></td>
                    <td><?php echo number_format($item->count, 0, '', '.') ?></td>
                    <td><?php echo $item->enchant ?></td>
                    <td><?php $this->widget('app.widgets.Status.Status', [
                            'status' => $item->status,
                            'statusText' => $item->getStatus()
                        ]) ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo CHtml::link('', ['form', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id, 'item_id' => $item->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['allow', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id, 'item_id' => $item->id], ['class' => ($pack->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($pack->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                            <li><?php echo CHtml::link('', ['del', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id, 'item_id' => $item->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8">Нет данных.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>