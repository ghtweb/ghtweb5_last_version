<?php

class Deposit
{
    // Типы платежных систем
    const PAYMENT_SYSTEM_UNITPAY = 0;
    const PAYMENT_SYSTEM_UNITPAY_SMS = 1;
    const PAYMENT_SYSTEM_ROBOKASSA = 2;
    /**
     * @deprecated
     */
    const PAYMENT_SYSTEM_WAYTOPAY = 3;

    /**
     * @deprecated
     */
    const PAYMENT_SYSTEM_WAYTOPAY_SMS = 4;


    private $_aggregator;
    private $_aggregator_id;


    public function init($aggregator_id = null)
    {
        if ($aggregator_id === null) {
            $aggregator_id = $this->initAggregator();
        }

        $list = static::getAggregatorsList();

        if (!isset($list[$aggregator_id])) {
            Yii::log('Агрегатор для обработки платежа не найден.', CLogger::LEVEL_ERROR, 'modules.deposit.extensions.Deposit.Deposit::' . __LINE__);

            return false;
        }

        $aggregator = $list[$aggregator_id];

        Yii::import('application.modules.deposit.extensions.Deposit.libs.' . $aggregator);

        $this->_aggregator_id = $aggregator_id;
        $this->_aggregator = new $aggregator();

        return true;
    }

    /**
     * Пытаюсь узнать какой агрегатор ломанулся к скрипту
     */
    public function initAggregator()
    {
        $id = -1;

        if (isset($_REQUEST['InvId']) && is_numeric($_REQUEST['InvId'])) {
            $id = self::PAYMENT_SYSTEM_ROBOKASSA;
        } // Unitpay.ru
        elseif (isset($_REQUEST['params']['unitpayId']) && is_numeric($_REQUEST['params']['unitpayId'])) {
            $id = self::PAYMENT_SYSTEM_UNITPAY;
        }

        return $id;
    }

    /**
     * Возвращает все типы платежных агрегаторов
     *
     * @return array
     */
    public static function getAggregatorsList()
    {
        return [
            self::PAYMENT_SYSTEM_UNITPAY => 'Unitpay',
            self::PAYMENT_SYSTEM_UNITPAY_SMS => 'UnitpaySMS',
            self::PAYMENT_SYSTEM_ROBOKASSA => 'Robokassa',
        ];
    }

    public function getAggregatorName()
    {
        return get_class($this->_aggregator);
    }

    public function getAggregator()
    {
        return $this->_aggregator;
    }

    public function processed()
    {
        $aggregator = $this->_aggregator;

        // Проверка необходимых параметров
        $aggregator->checkParams();

        // Проверка подписи
        $aggregator->checkSignature();

        if ($aggregator->isSms()) {
            $paymentSystem = ($this->_aggregator_id == self::PAYMENT_SYSTEM_UNITPAY ? self::PAYMENT_SYSTEM_UNITPAY_SMS : self::PAYMENT_SYSTEM_WAYTOPAY_SMS);

            if ($paymentSystem == self::PAYMENT_SYSTEM_UNITPAY_SMS) {
                $transactionId = $aggregator->getId();

                /** @var Transactions $transaction */
                $transaction = Transactions::model()->findByPk($transactionId);

                if (!$transaction) {
                    throw new Exception('Транзакция не найдена.');
                } elseif ($transaction->isPaid()) {
                    throw new Exception('Транзакция уже обработана.');
                } elseif ($transaction->sum != $aggregator->getSum()) {
                    throw new Exception('Сумма не совпадает.');
                }

                $gsModel = Gs::model()->findByPk($transaction->gs_id);

                if (!$gsModel) {
                    throw new Exception('Сервер не найден.');
                }

                $tr = db()->beginTransaction();

                try {
                    $transaction->status = Transactions::STATUS_SUCCESS;

                    $transaction->save(false, ['status', 'updated_at']);

                    $this->recharge($transaction->user_id, $aggregator->getSum(), $gsModel->deposit_course_payments);

                    $tr->commit();
                } catch (Exception $e) {
                    $tr->rollback();
                    Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'modules.deposit.extensions.Deposit.' . __LINE__);
                    throw new Exception('Ошибка');
                }
            }
        } else {
            $transactionId = $aggregator->getId();

            $transaction = Transactions::model()->findByPk($transactionId);

            if (!$transaction) {
                throw new Exception('Транзакция не найдена.');
            }

            if ($transaction->isPaid()) {
                throw new Exception('Транзакция уже обработана.');
            } elseif ($transaction->sum != $aggregator->getSum()) {
                throw new Exception('Сумма не совпадает.');
            }

            $transaction->status = Transactions::STATUS_SUCCESS;

            $gsModel = Gs::model()->findByPk($transaction->gs_id);

            if (!$gsModel) {
                throw new Exception('Сервер не найден.');
            }

            $tr = db()->beginTransaction();

            try {
                $transaction->save(false, ['status', 'updated_at']);

                $this->recharge($transaction->user_id, $aggregator->getSum(), $gsModel->deposit_course_payments);

                $tr->commit();
            } catch (Exception $e) {
                $tr->rollback();

                throw new Exception($e->getMessage());
            }
        }

        return $transaction;
    }

    /**
     * Поплнение баланса юзеру
     *
     * @param int $userId
     * @param float $sum
     * @param int $curs
     *
     * @return void
     */
    private function recharge($userId, $sum, $curs)
    {
        $userProfilesModel = UserProfiles::model()->find('user_id = :user_id', [
            'user_id' => $userId,
        ]);

        $balance = floor($sum / $curs);

        $userProfilesModel->balance += $balance;

        $userProfilesModel->save(false, ['balance', 'updated_at']);
    }

    /**
     * Возвращает URL для формы
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->_aggregator->getFormAction();
    }

    /**
     * Возвращает поля для формы
     *
     * @param Transactions $transaction
     *
     * @return string
     */
    public function getFields(Transactions $transaction)
    {
        return $this->_aggregator->getFields($transaction);
    }

    /**
     * Возвращает ID
     */
    public function getId()
    {
        return $this->_aggregator->getId();
    }

    /**
     * Возвращает ID гейм сервера
     */
    public function getGsId()
    {
        return $this->_aggregator->getGsId();
    }

    /**
     * Возвращает текст ошибки для агригатора
     *
     * @param string $str
     *
     * @return string
     */
    public function error($str)
    {
        return $this->_aggregator->error($str);
    }

    /**
     * Возвращает текст успеха для агригатора
     *
     * @param string $str
     *
     * @return string
     */
    public function success($str)
    {
        return $this->_aggregator->success($str);
    }
}
