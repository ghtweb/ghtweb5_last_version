<?php

/**
 * This is the model class for table "{{user_actions_log}}".
 *
 * The followings are the available columns in table '{{user_actions_log}}':
 * @property string $id
 * @property string $user_id
 * @property integer $action_id
 * @property string $params
 * @property string $created_at
 */
class UserActionsLog extends ActiveRecord
{
    /**
     * Авторизация
     */
    const ACTION_AUTH = 1;

    /**
     * Пополнение баланса
     */
    const ACTION_DEPOSIT_SUCCESS = 2;

    /**
     * Смена пароля
     */
    const ACTION_CHANGE_PASSWORD = 3;

    /**
     * Создание тикета
     */
    const ACTION_CREATE_TICKET = 4;

    /**
     * Телепорт персонажа в город
     */
    const ACTION_TELEPORT_TO_TOWN = 5;

    /**
     * Активация бонуса
     */
    const ACTION_ACTIVATED_BONUS = 6;

    /**
     * Активация бонус кода
     */
    const ACTION_ACTIVATED_BONUS_CODE = 7;

    /**
     * Покупка предмета в магазине
     */
    const ACTION_SHOP_BUY_ITEM = 8;

    /**
     * Покупка ПА
     */
    const ACTION_SERVICES_BUY_PREMIUM = 9;

    /**
     * Удаление HWID
     */
    const ACTION_SERVICES_REMOVE_HWID = 10;


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_actions_log}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => UserActionsLog::class,
            'idAttributeName' => 'user_id',
        ];

        return $behaviors;
    }
}
