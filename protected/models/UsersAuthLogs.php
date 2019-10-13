<?php

/**
 * This is the model class for table "{{users_auth_logs}}".
 *
 * The followings are the available columns in table '{{users_auth_logs}}':
 * @property string $id
 * @property string $user_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $status
 * @property string $created_at
 *
 * @property Gs $gs
 */
class UsersAuthLogs extends ActiveRecord
{
    const STATUS_AUTH_SUCCESS = 1; // Авторизовался удачно
    const STATUS_AUTH_DENIED = 0; // Авторизация не прошла

    public function tableName()
    {
        return '{{users_auth_logs}}';
    }

    public function getStatusList()
    {
        return [
            self::STATUS_AUTH_SUCCESS => 'Разрешен',
            self::STATUS_AUTH_DENIED => 'Запрешен',
        ];
    }

    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->ip = userIp();
            $this->user_agent = request()->getUserAgent();
        }

        return parent::beforeSave();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => self::class,
            'idAttributeName' => 'user_id',
        ];

        return $behaviors;
    }

    /**
     * Добавление неудачной попытки авторизации
     *
     * @param $userId
     *
     * @return void
     */
    public function addErrorAuth($userId)
    {
        $model = new self();

        $model->user_id = $userId;
        $model->status = self::STATUS_AUTH_DENIED;

        $model->save(false);
    }

    /**
     * Добавление удачной попытки авторизации
     *
     * @param $userId
     *
     * @return void
     */
    public function addSuccessAuth($userId)
    {
        $model = new self();

        $model->user_id = $userId;
        $model->status = self::STATUS_AUTH_SUCCESS;

        $model->save(false);
    }
}
