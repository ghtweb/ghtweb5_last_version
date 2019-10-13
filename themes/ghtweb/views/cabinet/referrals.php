<?php
/**
 * @var ReferalsController $this
 * @var CActiveDataProvider $dataProvider
 * @var ReferalsProfit[] $data
 * @var int $countReferals
 */

$title_ = 'Реферальная программа';
$this->pageTitle = $title_;

$this->breadcrumbs = [$title_];

$total = 0;
?>

<?php if (config('referral_program.allow')) { ?>
    <div class="alert alert-info">
        <p>
            С каждого приведенного пользователя по Вашей ссылке или коду вы получаете
            <b><?php echo config('referral_program.percent') ?></b>% от всех совершенных им пожертвований.
        </p>
    </div>

    <div class="partner">
        <span class="title">Ваш заработок по <br/>партнерской программе</span>

        <?php if ($data = $dataProvider->getData()) { ?>

            <ul class="clearfix">
                <?php foreach ($data as $i => $row) { ?>
                    <?php $total += $row->profit ?>
                    <li class="item">
                        <div class="text">
                            <span><span>Дата:</span> <?php echo date('Y-m-d H:i', strtotime($row->created_at)) ?></span>
                            <span><span>Заработано:</span> <?php echo formatCurrency($row->profit) ?></span>
                        </div>
                    </li>
                <?php } ?>
            </ul>

        <?php } else { ?>
            <div class="alert alert-info">
                Нет данных.
            </div>
        <?php } ?>

        <p class="gold"><span>Всего заработано:</span> <?php echo formatCurrency($total) ?></p>
        <div class="hint">
            <p>
                С каждого приведенного пользователя по <span>Вашей ссылке</span> или <span>коду</span>
                <br/>Вы получаете <span><?php echo config('referral_program.percent') ?>%</span> от всех совершенных им пожертвований.
            </p>
        </div>
        <p class="link">
            <span>Ваша ссылка:</span> <?php echo app()->createAbsoluteUrl('/index/default/index') . '?' . app()->params['cookie_referer_name'] . '=' . user()->get('referer') ?>
        </p>
        <p class="code"><span>Ваш код:</span>
            <span><?php echo user()->get('referer') ?></span></p>
        <p class="users">Зарегистрированных пользователей по вашей ссылке/коду: <span class="count"><?php echo $countReferals ?></span></p>
    </div>
<?php } else { ?>
    <div class="alert alert-info">
        Реферальная программа отключена.
    </div>
<?php } ?>