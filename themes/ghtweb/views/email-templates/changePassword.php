<?php
/**
 * @var string $password
 */

$siteName = CHtml::link($_SERVER['HTTP_HOST'], $this->createAbsoluteUrl('/index/default/index'));
?>

<font color="#ead255" face="Trebuchet MS" style="font-size: 24px;">Здравствуйте!</font>
<br/><br/><br/><br/>
Вы сменили пароль от аккаунта на сайте <font color="#5aaee9"><?php echo $siteName ?></font>
<br/>
Ваш новый пароль: <font color="#ead255"><?php echo $password ?></font>