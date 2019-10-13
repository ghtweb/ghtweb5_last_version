<?php
/**
 * @var GamesServerShopCategoriesPacksController $this
 * @var ShopItemsPacks[] $packs
 * @var ShopCategories $category
 * @var Gs $gs
 * @var CActiveDataProvider $dataProvider
 */

$title__ = 'Наборы';
$this->pageTitle = $title__;
$this->breadcrumbs = [
    'Сервера' => ['/backend/gameServers/index'],
    $gs->name . ' - ' . 'Магазин' => ['/backend/gamesServerShopCategories/index', 'gs_id' => $gs->id],
    'Наборы для категории - ' . $category->name,
] ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo CHtml::link('Создать набор', ['form', 'gs_id' => $gs->id, 'category_id' => $category->id], ['class' => 'btn btn-primary btn-block']) ?>

<hr>

<style>
    .pack-img img {
        max-width: 100%;
        display: block;
    }

    .desc h3 {
        margin-top: 0;
    }
</style>

<table class="table">
    <colgroup>
        <col style="width: 10%;">
        <col>
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
    </colgroup>
    <thead>
    <tr>
        <th>Картинка</th>
        <th>Название/Описание</th>
        <th>Кол-во предметов</th>
        <th>Статус</th>
        <th>Сортировка</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($packs = $dataProvider->data) { ?>
        <?php foreach ($packs as $pack) { ?>
            <tr>
                <td class="pack-img">
                    <?php echo ($pack->imgIsExists('original') ? CHtml::image($pack->getImgUrl('original')) : '') ?>
                </td>
                <td class="desc">
                    <?php echo e($pack->title) ?><br>
                    <?php echo wordLimiter($pack->description, 20) ?>
                </td>
                <td><?php echo $pack->countItems ?></td>
                <td><?php $this->widget('app.widgets.Status.Status', [
                        'status' => $pack->status,
                        'statusText' => $pack->getStatus()
                    ]) ?></td>
                <td><?php echo $pack->sort ?></td>
                <td>
                    <ul class="actions list-unstyled">
                        <li><?php echo CHtml::link('', ['form', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['allow', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id], ['class' => ($pack->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($pack->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['/backend/gamesServerShopCategoriesPacksItems/index', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id], ['class' => 'glyphicon glyphicon-th', 'title' => 'Предметы', 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['del', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
                    </ul>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6">Нет данных.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>