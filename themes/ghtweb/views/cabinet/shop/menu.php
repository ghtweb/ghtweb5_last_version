<?php
/**
 * @var ShopController $this
 * @var ShopCategories[] $categories
 */
?>

<?php if ($categories) { ?>
    <?php
    $items = [];

    foreach ($categories as $category) {
        $items[] = [
            'label' => $category->name,
            'url' => ['/cabinet/shop/category', 'category_link' => $category->link],
        ];
    }

    $this->widget('zii.widgets.CMenu', [
        'htmlOptions' => [
            'class' => 'tabs',
        ],
        'items' => $items,
    ]) ?>



<?php } ?>


