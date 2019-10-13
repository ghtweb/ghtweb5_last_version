<?php
/**
 * @var string $server_name
 * @var string $login
 * @var string $password
 * @var string $referer
 */
?>
<font color="#ead255" face="Trebuchet MS" style="font-size: 24px;">Здравствуйте!</font>
<br /><br /><br /><br />
Ваши регистрационные данные
<br />
<font color="#5aaee9">Сервер</font>: <font color="#ffffff"><?php echo $server_name ?><font>
<br />
<font color="#5aaee9">Логин</font>: <font color="#ffffff"><?php echo $login ?></font>
<br />
<font color="#5aaee9">Пароль</font>: <font color="#ffffff"><?php echo $password ?></font>
<br />
<?php if(config('referral_program.allow')) { ?>
    <font color="#5aaee9">Реферальный код</font>: <font color="#ffffff"><?php echo $referer ?></font>
    <br />
<?php } ?>