<?php

class Unitpay
{
    /**
     * check — запрос на проверку состояния абонента, pay — уведомление о списании, error — уведомление об ошибке
     * @var string
     */
    protected $method;

    /**
     * идентификатор абонента в системе Партнера
     * @var string
     */
    protected $account;

    /**
     * дата платежа в формате YYYY-mm-dd HH:ii:ss (например 2012-10-01 12:32:00)
     * @var string
     */
    protected $date;

    /**
     * буквенный код оператора (beeline, mts, mf, tele2 и т.д.)
     * @var string
     */
    protected $operator;

    /**
     * код платежной системы
     * see: https://unitpay.ru/doc#billingCodes
     * @var string
     */
    protected $paymentType;

    /**
     * телефон плательщика (передается только для мобильных платежей)
     * @var string
     */
    protected $phone;

    /**
     * ваш доход с данного платежа, в руб.
     * @var float
     */
    protected $profit;

    /**
     * ID вашего проекта
     * @var int
     */
    protected $projectId;

    /**
     * цифровая подпись, образуется как md5 хеш от склеивания всех значений параметров (кроме sign),
     * отсортированных по алфавиту и секретного ключа (доступен в настройках проекта)
     * @var string
     */
    protected $sign;
    protected $signature;

    /**
     * сумма списания с лицевого счета абонента, в руб.
     * @var float
     */
    protected $sum;

    /**
     * внутренний номер платежа в Unitpay
     * @var int
     */
    protected $unitpayId;

    /**
     * Публичный ключ
     * @var string
     */
    protected $publicKey;

    /**
     * Секретный ключ
     * @var string
     */
    protected $secretKey;

    protected $allowIpList = ['31.186.100.49', '178.132.203.105', '52.29.152.23', '52.19.56.234'];

    public function __construct()
    {
        $this->method = (string)request()->getParam('method');
        $this->account = isset($_GET['params']['account']) ? (string)$_GET['params']['account'] : '';
        $this->date = isset($_GET['params']['date']) ? (string)$_GET['params']['date'] : '';
        $this->operator = isset($_GET['params']['operator']) ? (string)$_GET['params']['operator'] : '';
        $this->paymentType = isset($_GET['params']['paymentType']) ? (string)$_GET['params']['paymentType'] : '';
        $this->phone = isset($_GET['params']['phone']) ? (string)$_GET['params']['phone'] : '';
        $this->profit = isset($_GET['params']['profit']) ? (float)$_GET['params']['profit'] : 0;
        $this->projectId = isset($_GET['params']['projectId']) ? (int)$_GET['params']['projectId'] : (int)config('unitpay.project_id');
        $this->sign = isset($_GET['params']['sign']) ? (string)$_GET['params']['sign'] : '';
        $this->signature = isset($_GET['params']['signature']) ? (string)$_GET['params']['signature'] : '';
        $this->sum = isset($_GET['params']['sum']) ? (float)$_GET['params']['sum'] : 0;
        $this->unitpayId = isset($_GET['params']['unitpayId']) ? (int)$_GET['params']['unitpayId'] : 0;

        $this->publicKey = (string)config('unitpay.public_key');
        $this->secretKey = (string)config('unitpay.secret_key');
    }

    public function getFormAction()
    {
        return 'https://unitpay.ru/pay/' . $this->publicKey;
    }

    public function getFields(Transactions $transaction)
    {
        return CHtml::hiddenField('account', $transaction->id . ' ' . app()->controller->gs->id) .
            CHtml::hiddenField('sum', $transaction->sum) .
            CHtml::hiddenField('desc', app()->controller->gs->deposit_desc);
    }

    public function checkParams()
    {
        if (!$this->method || !isset($_REQUEST['params']) || !is_array($_REQUEST['params'])) {
            throw new Exception('Некорректный запрос.');
        }

        if ($this->method == 'check') {
            echo $this->success('ok');
            app()->end();
        }

        if ($this->method == 'error') {
            $errorMessage = (!empty($_REQUEST['params']['errorMessage']) ? $_REQUEST['params']['errorMessage'] : '');
            echo $this->error($errorMessage);
            app()->end();
        }

        if (!$this->unitpayId || !$this->sum || !$this->account) {
            throw new Exception('Отсутствуют обязательные параметры платежа.');
        }

        if (!in_array(userIp(), $this->allowIpList)) {
            throw new Exception('IP Access denied.');
        }

        return true;
    }

    public function checkSignature()
    {
        if ($this->signature != $this->md5sign($this->method, $_REQUEST['params'], $this->secretKey)) {
            throw new Exception('Некорректная цифровая подпись.');
        }

        return true;
    }

    private function md5sign($method, $params, $secretKey)
    {
        $delimiter = '{up}';

        ksort($params);

        unset($params['sign']);
        unset($params['signature']);

        return hash('sha256', $method . $delimiter . join($delimiter, $params) . $delimiter . $secretKey);
    }

    public function isSms()
    {
        return $this->paymentType == 'sms';
    }

    public function getId()
    {
        list($transactionId, $gsId) = explode(' ', $this->account);

        return (int)$transactionId;
    }

    public function getGsId()
    {
        list($transactionId, $gsId) = explode(' ', $this->account);

        return (int)$gsId;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function error($message)
    {
        return json_encode([
            'error' => [
                'code' => -32000,
                'message' => $message,
            ],
        ]);
    }

    public function success($message)
    {
        return json_encode([
            'result' => [
                'message' => $message,
            ],
        ]);
    }
}
 