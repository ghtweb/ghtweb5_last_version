<?php

namespace app\modules\backend\models\forms;

class LsForm extends \Ls
{
    public function rules()
    {
        $rules = [];

        // name
        $rules[] = ['name', 'filter', 'filter' => 'trim'];
        $rules[] = ['name', 'required'];
        $rules[] = ['name', 'length', 'max' => 54];

        // ip
        $rules[] = ['ip', 'filter', 'filter' => 'trim'];
        $rules[] = ['ip', 'required'];
        $rules[] = ['ip', 'length', 'max' => 54];

        // port
        $rules[] = ['port', 'filter', 'filter' => 'trim'];
        $rules[] = ['port', 'required'];
        $rules[] = ['port', 'length', 'max' => 6];
        $rules[] = ['port', 'numerical', 'integerOnly' => true];

        // db_host
        $rules[] = ['db_host', 'filter', 'filter' => 'trim'];
        $rules[] = ['db_host', 'required'];
        $rules[] = ['db_host', 'length', 'max' => 54];

        // db_host
        $rules[] = ['db_port', 'filter', 'filter' => 'trim'];
        $rules[] = ['db_port', 'required'];
        $rules[] = ['db_port', 'length', 'max' => 6];
        $rules[] = ['db_port', 'numerical', 'integerOnly' => true];

        // db_user
        $rules[] = ['db_user', 'filter', 'filter' => 'trim'];
        $rules[] = ['db_user', 'required'];
        $rules[] = ['db_user', 'length', 'max' => 54];

        // db_pass
        $rules[] = ['db_pass', 'filter', 'filter' => 'trim'];
//        $rules[] = ['db_pass', 'required'];
        $rules[] = ['db_pass', 'length', 'max' => 54];
        $rules[] = ['db_pass', 'default', 'value' => null];

        // db_name
        $rules[] = ['db_name', 'filter', 'filter' => 'trim'];
        $rules[] = ['db_name', 'required'];
        $rules[] = ['db_name', 'length', 'max' => 54];

        // version
        $rules[] = ['version', 'filter', 'filter' => 'trim'];
        $rules[] = ['version', 'required'];
        $rules[] = ['version', 'in', 'range' => array_keys(serverVersionList()), 'message' => 'Выберите версию сервера'];

        // password_type
        $rules[] = ['password_type', 'filter', 'filter' => 'trim'];
        $rules[] = ['password_type', 'required'];
        $rules[] = ['password_type', 'in', 'range' => array_keys(\Ls::getPasswordTypeList())];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'ip' => 'Ip',
            'port' => 'Порт',
            'db_host' => 'MYSQL host',
            'db_port' => 'MYSQL port',
            'db_user' => 'MYSQL user',
            'db_pass' => 'MYSQL pass',
            'db_name' => 'MYSQL bd name',
            'version' => 'Версия логина',
            'status' => 'Статус',
            'password_type' => 'Тип пароля',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
