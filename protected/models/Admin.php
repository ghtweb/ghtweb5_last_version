<?php

/**
 * This is the model class for table "{{admin}}".
 *
 * The followings are the available columns in table '{{admin}}':
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $auth_hash
 * @property string $created_at
 * @property string $updated_at
 */
class Admin extends ActiveRecord
{
	public function tableName()
	{
		return '{{admin}}';
	}

    public function generateAuthHash()
    {
        $this->auth_hash = Users::generateAuthHash() . md5(microtime());
	}

    public static function create(string $login, string $password): self
    {
        $date = date('Y-m-d H:i:s');

        $model = new self();

        $model->login = $login;
        $model->password = $password;
        $model->created_at = $date;
        $model->updated_at = $date;

        return $model;
	}

    public function setPassword(string $password)
    {
        $this->password = $password;
	}
}
