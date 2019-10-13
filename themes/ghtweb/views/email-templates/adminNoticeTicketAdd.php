<?php
/**
 * @var Users $user
 * @var Tickets $ticket
 */
?>
<font color="#ead255" face="Trebuchet MS" style="font-size: 24px;">Здравствуйте!</font>
<br/><br/><br/><br/>
Был создан новый тикет
<br/>
Пользователь: <a
        href="<?php echo app()->createAbsoluteUrl('/backend/users/view', ['user_id' => $user->getPrimaryKey()]) ?>"
        title="Перейти к просмотру пользователя"><?php echo CHtml::encode($user->login) ?></a>
<br>
Тикет: <a href="<?php echo app()->createAbsoluteUrl('/backend/tickets/edit', ['id' => $ticket->getPrimaryKey()]) ?>"
          title="Перейти к просмотру тикета"><?php echo CHtml::encode($ticket->title) ?></a>
