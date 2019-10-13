<?php
/**
 * @var array $licenseInfo
 * @var \app\services\VersionService $version
 * @var int $countGameAccounts
 * @var int $countSuccessTransaction
 * @var int $countTickets
 */

js(app()->getThemeManager()->getBaseUrl() . '/js/default_index.js', CClientScript::POS_END);

$this->breadcrumbs = [];
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<h4>Версия CMS</h4>
Установленная версия: <code class="js-old-version"><?php echo $version->getVersion() ?></code><br><br>
<?php echo CHtml::link('Очистить кэш', ['clearCache'], ['class' => 'btn btn-warning btn-sm js-clear-cache']) ?>

<hr>

<h4>Информация по сайту</h4>

<p>Кол-во игровых аккаунтов: <code><a href="<?php echo app()->createUrl('/backend/users/index') ?>"><?php echo $countGameAccounts ?></a></code></p>
<p>Кол-во пожертвований (успешных): <code><a href="<?php echo app()->createUrl('/backend/transactions/index') ?>"><?php echo $countSuccessTransaction ?></a></code></p>
<p>Создано тикетов: <code><a href="<?php echo app()->createUrl('/backend/tickets/index') ?>"><?php echo $countTickets ?></a></code></p>