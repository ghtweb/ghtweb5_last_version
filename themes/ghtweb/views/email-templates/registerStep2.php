<?php
/**
 * @var string $login
 * @var string $password
 */

?>
<font color="#ead255" face="Trebuchet MS" style="font-size: 24px;">Здравствуйте!</font>
<br/><br/><br/><br/>
Ваши данные для <a href="<?php echo $this->createAbsoluteUrl('/login/default/index') ?>"><font color="#ead255">входа</font></a> в личный кабинет:
<br/>
<font color="#5aaee9">Логин</font>: <?php echo $login ?>
<br/>
<font color="#5aaee9">Пароль</font>: <?php echo $password ?>
<br/>