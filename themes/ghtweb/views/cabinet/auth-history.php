<?php
/**
 * @var AuthHistoryController $this
 * @var CActiveDataProvider $dataProvider
 * @var UsersAuthLogs[] $data
 */

$title_ = 'История авторизаций';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>

    <div class="entry">
        <div class="scroll-pane">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>IP</th>
                    <th>Браузер</th>
                    <th>Доступ</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($data = $dataProvider->getData()) { ?>
                    <?php foreach ($data as $i => $row) { ?>
                        <?php

                        $userAgent = explode(' ', $row->user_agent);
                        $userAgent = !empty($userAgent[0]) ? trim($userAgent[0]) : 'n/a';
                        ?>
                        <tr>
                            <td><?php echo ++$i ?></td>
                            <td><?php echo CHtml::link($row->ip, getLocationLinkByIp($row->ip), ['target' => '_blank']) ?></td>
                            <td><?php echo e($userAgent) ?></td>
                            <td>
                                <span style="color: <?php echo($row->status == UsersAuthLogs::STATUS_AUTH_SUCCESS ? 'green' : 'red') ?>;"><?php echo $row->getStatus() ?></span>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($row->created_at)) ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">Нет данных.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>