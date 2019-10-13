<?php
/**
 * @var DefaultController $this
 */

$this->pageTitle = 'Личный кабинет';
?>

<h2 class="title user">Здравствуй, <?php echo user()->get('login') ?>!</h2>

<div class="user-info">
    <p class="gold"><span>Ваш баланс —</span> <b><?php echo formatCurrency(user()->get('balance')) ?></b></p>
    <p class="calendar"><span>Дата регистрации —</span>
        <b><?php echo date('Y-m-d H:i', strtotime(user()->created_at)) ?></b></p>
</div>