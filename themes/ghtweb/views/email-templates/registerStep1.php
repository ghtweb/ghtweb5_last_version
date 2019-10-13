<?php
/**
 * @var string hash
 */

$url = $this->createAbsoluteUrl('/register/default/activated', ['_hash' => $hash]);
$siteName = CHtml::link($_SERVER['HTTP_HOST'], $this->createAbsoluteUrl('/index/default/index'));
?>

    <font color="#ead255" face="Trebuchet MS"
          style="font-size: 24px;">Здравствуйте!</font>
    <br/><br/><br/><br/>
Вы подали заявку регистрации на сайте <font color="#5aaee9"><?php echo $siteName ?></font>
    <br/>
Ваша <a href="<?php echo $url ?>"><font color="#ead255">ссылка</font></a> для активации аккаунта
    <br/>
Или скопируйте ссылку ниже и вставьте в адресную строку Вашего браузера
    <br/>
<?php echo $url ?>