<?php
/**
 * @var Users $user
 * @var Tickets $ticket
 */

?>
    <font color="#ead255" face="Trebuchet MS"
          style="font-size: 24px;">Здравствуйте!</font>
    <br/><br/><br/><br/>
Был дан ответ на Ваш тикет
    <br/>
    <a href="<?php echo app()->createAbsoluteUrl('/cabinet/tickets/view', ['ticket_id' => $ticket->getPrimaryKey()]) ?>">Перейти к просмотру</a>
    <br>
Ссылка на тикет:<br>
<?php echo app()->createAbsoluteUrl('/cabinet/tickets/view', ['ticket_id' => $ticket->getPrimaryKey()]) ?>