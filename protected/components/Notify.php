<?php

/**
 * Class Notify
 */
class Notify extends CApplicationComponent
{
    /**
     * Основной шаблон для писем, в него вставляется контент
     * @var string
     */
    protected $layout = '//email-templates/layout';

    /**
     * Где лежат шаблоны для писем
     * @var string
     */
    protected $emailTemplatesPath = '//email-templates';

    /**
     * @var PHPMailer
     */
    protected $PHPMailer;


    public function init()
    {
        parent::init();

        require Yii::getPathOfAlias('ext.PHPMailer') . '/PHPMailerAutoload.php';

        $this->PHPMailer = new PHPMailer();

        $this->settings();
    }

    protected function settings()
    {
        // SMTP
        if (config('mail.smtp')) {
            $this->PHPMailer->isSMTP();

            $this->PHPMailer->Host = config('mail.smtp_host');

            if (config('mail.smtp_login') != '') {
                $this->PHPMailer->SMTPAuth = true;
                $this->PHPMailer->Username = config('mail.smtp_login');
                $this->PHPMailer->Password = config('mail.smtp_password');
                $this->PHPMailer->Port = config('mail.smtp_port');
            }
        }

        // Other
        $this->PHPMailer->From = config('mail.admin_email');
        $this->PHPMailer->FromName = config('mail.admin_name');
        $this->PHPMailer->CharSet = 'utf-8';

        $this->PHPMailer->isHTML(true);

        // Language (перевод системных сообщений)
        $this->PHPMailer->setLanguage('ru', Yii::getPathOfAlias('ext.PHPMailer.language') . '/');
    }

    /**
     * Регистрация, шаг 1
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function registerStep1($to, array $params = null)
    {
        return $this->send($to, 'Регистрация, активация аккаунта', $this->render(__FUNCTION__, $params));
    }

    /**
     * Регистрация, шаг 2
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function registerStep2($to, array $params = null)
    {
        return $this->send($to, 'Регистрация, аккаунт активирован', $this->render(__FUNCTION__, $params));
    }

    /**
     * Регистрация без активации аккаунта
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function registerNoEmailActivated($to, array $params = null)
    {
        return $this->send($to, 'Аккаунт успешно зарегистрирован', $this->render(__FUNCTION__, $params));
    }

    /**
     * Восстановление пароля, шаг 1
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function forgottenPasswordStep1($to, array $params = null)
    {
        return $this->send($to, 'Восстановление пароля от аккаунта, шаг 1', $this->render(__FUNCTION__, $params));
    }

    /**
     * Восстановление пароля, шаг 2
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function forgottenPasswordStep2($to, array $params = null)
    {
        return $this->send($to, 'Восстановление пароля от аккаунта, шаг 2', $this->render(__FUNCTION__, $params));
    }

    /**
     * Смена пароля от аккаунта
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function changePassword($to, array $params = null)
    {
        return $this->send($to, 'Смена пароля от аккаунта', $this->render(__FUNCTION__, $params));
    }

    /**
     * Пополнение баланса рефералом
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function rechargeBalanceByReferal($to, array $params = null)
    {
        return $this->send($to, 'Пополнение баланса по реферальной программе', $this->render(__FUNCTION__, $params));
    }

    /**
     * Покупка в магазине
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function shopBuyItems($to, array $params = null)
    {
        $this->send($to, 'Покупка в магазине', $this->render(__FUNCTION__, $params));
    }

    /**
     * Тикет был создан, информирую админа
     *
     * @param array $params
     *
     * @return bool
     */
    public function adminNoticeTicketAdd(array $params = null)
    {
        $this->send(config('mail.admin_email'), 'Был создан новый тикет', $this->render(__FUNCTION__, $params));
    }

    /**
     * Был дан ответ на тикет, информирую юзера
     *
     * @param string $to
     * @param array $params
     *
     * @return bool
     */
    public function userNoticeTicketAnswer($to, array $params = null)
    {
        $this->send($to, 'Был дан ответ на Ваш тикет', $this->render(__FUNCTION__, $params));
    }

    /**
     * Возвращает шаблон для письма
     *
     * @param string $view
     * @param array $params
     *
     * @return string
     */
    protected function render($view, array $params = null)
    {
        (new \app\services\ThemeService())->setTheme(config('theme'));

        return app()->controller->renderPartial($this->emailTemplatesPath . '/' . $view, $params, true);
    }

    /**
     * Обработка названия письма
     *
     * @param string $str
     *
     * @return string
     */
    protected function title($str)
    {
        return $str;
    }

    /**
     * @return PHPMailer
     */
    public function getMailer()
    {
        return $this->PHPMailer;
    }

    /**
     * Прикрепление файлов к письму
     *
     * @param array|string $data
     *
     * @return void
     */
    public function attach($data)
    {
        $data = (!is_array($data) ? (array)$data : $data);

        foreach ($data as $file) {
            $this->PHPMailer->addAttachment($file);
        }
    }

    /**
     * Отправка на почту
     *
     * @param string $subject
     * @param string $to
     * @param string $message
     *
     * @return bool
     */
    protected function send($to, $subject, $message)
    {
        if ($this->layout) {
            $message = app()->controller->renderPartial($this->layout, [
                'content' => $message,
            ], true);
        }

        if (!($this->PHPMailer instanceof PHPMailer)) {
            return false;
        }

        $this->PHPMailer->addAddress(trim($to));

        $this->PHPMailer->Subject = trim($subject);
        $this->PHPMailer->Body = $message;
        $this->PHPMailer->AltBody = strip_tags($message);


        if (!$this->PHPMailer->send()) {
            Yii::log($this->PHPMailer->ErrorInfo, CLogger::LEVEL_ERROR, 'Notify');

            return false;
        }

        // Cleared
        $this->PHPMailer->ClearAddresses();
        $this->PHPMailer->ClearAttachments();

        return true;
    }
}
 