<?php
/**
 * @var Gs $gs
 * @var CActiveDataProvider $dataProvider
 * @var ShopCategories[] $categories
 */

$title_ = 'Магазин';
$this->pageTitle = $title_;
$this->breadcrumbs = [
    'Сервера' => ['/backend/gameServers/index'],
    $gs->name . ' - ' . $title_,
];
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo CHtml::link('Создать категорию', ['form', 'gs_id' => $gs->id], ['class' => 'btn btn-primary btn-block']) ?>

<hr>

<table class="table">
    <colgroup>
        <col>
        <col style="width: 15%;">
        <col style="width: 15%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
    </colgroup>
    <thead>
    <tr>
        <th>Название</th>
        <th width="15%">Ссылка</th>
        <th width="15%">Кол-во наборов</th>
        <th width="10%">Статус</th>
        <th width="10%">Сортировка</th>
        <th width="10%"></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($categories = $dataProvider->data) { ?>
        <?php foreach ($categories as $category) { ?>
            <tr>
                <td><?php echo e($category->name) ?></td>
                <td><?php echo $category->link ?></td>
                <td><?php echo $category->countPacks ?></td>
                <td><?php $this->widget('app.widgets.Status.Status', [
                        'status' => $category->status,
                        'statusText' => $category->getStatus()
                    ]) ?></td>
                <td><?php echo $category->sort ?></td>
                <td>
                    <ul class="actions list-unstyled">
                        <li><?php echo CHtml::link('', ['form', 'gs_id' => $gs->id, 'category_id' => $category->id], ['class' => 'glyphicon glyphicon-pencil', 'title' => 'Редактировать', 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['allow', 'gs_id' => $gs->id, 'category_id' => $category->id], ['class' => ($category->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($category->isStatusOn() ? 'Выключить' : 'Включить'), 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['/backend/gamesServerShopCategoriesPacks/index', 'gs_id' => $gs->id, 'category_id' => $category->id], ['class' => 'glyphicon glyphicon-th', 'title' => 'Наборы', 'rel' => 'tooltip']) ?></li>
                        <li><?php echo CHtml::link('', ['del', 'gs_id' => $gs->id, 'category_id' => $category->id], ['class' => 'glyphicon glyphicon-remove js-confirm-del', 'title' => 'Удалить', 'rel' => 'tooltip']) ?></li>
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

<p style="font-size: 12px;">
    <span style="color: red;">*</span> Внимание!!!! при удалении категории также удаляются все наборы и все предметы в
    наборах!
</p>