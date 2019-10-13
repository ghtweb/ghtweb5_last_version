<?php

/**
 * Виджет показа юзерам сколько у них новых сообщений
 *
 * Class UserNotifications
 */
class UserNotifications extends CWidget
{
    private $_cookieName = 'messages';


    public function init()
    {
        if (user()->isGuest) {
            return;
        }

        $assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.widgets.UserNotifications.css'), false, -1, YII_DEBUG);

        $cs = clientScript();
        $cs->registerCssFile($assetsUrl . '/style.css');

        $countMessages = $this->getCountMessages();
        $cookieCountMessages = 0;

        if (isset(request()->cookies[$this->_cookieName]) && is_numeric(request()->cookies[$this->_cookieName]->value)) {
            $cookieCountMessages = request()->cookies[$this->_cookieName]->value;
        }

        if ($countMessages > $cookieCountMessages) {
            request()->cookies[$this->_cookieName] = new CHttpCookie($this->_cookieName, $countMessages, ['expire' => time() + 3600 * 24 * 365]);

            $count = $countMessages - $cookieCountMessages;
            $countMessagesTranslate = Yii::t('main', 'новое сообщение|новых сообщения|новых сообщений|новых сообщения', $count);

            echo '<div class="user-messages-block"><a href="' . app()->createUrl('/cabinet/messages/index') . '">' . 'У Вас <b>' . $count . '</b> ' . $countMessagesTranslate . '</a></div>';
        }
    }

    private function getCountMessages()
    {
        $userId = user()->getId();
        $status = ActiveRecord::STATUS_ON;
        $messages = [];

        foreach (UserMessages::getMessagesByUserId($userId) as $message) {
            if ($message->status == $status) {
                $messages[] = $message;
            }
        }

        return count($messages);
    }
}
 