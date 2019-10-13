<?php

/**
 * This is the model class for table "{{ls}}".
 *
 * The followings are the available columns in table '{{ls}}':
 * @property integer $id
 * @property string $name
 * @property string $ip
 * @property string $port
 * @property string $db_host
 * @property string $db_port
 * @property string $db_user
 * @property string $db_pass
 * @property string $db_name
 * @property string $version
 * @property integer $status
 * @property string $password_type
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Gs[] $servers
 */
class Ls extends ActiveRecord
{
    // Password
    const PASSWORD_TYPE_SHA1 = 'sha1';
    const PASSWORD_TYPE_WHIRLPOOL = 'whirlpool';


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{ls}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Ls::class,
        ];

        return $behaviors;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'servers' => [self::HAS_MANY, 'Gs', ['login_id' => 'id']],
        ];
    }

    public static function getPasswordTypeList()
    {
        return [
            self::PASSWORD_TYPE_SHA1 => 'sha1',
            self::PASSWORD_TYPE_WHIRLPOOL => 'whirlpool',
        ];
    }

    public function getPasswordType()
    {
        $data = Ls::getPasswordTypeList();

        return isset($data[$this->password_type]) ? $data[$this->password_type] : '*Unknown*';
    }

    /**
     * Возвращает список открытых логинов
     *
     * @return Ls[]
     */
    public static function getOpenLoginServers()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class),
        ]);
        $list = [];

        /** @var Ls[] $res */
        $res = Ls::model()->cache(3600, $dependency)->opened()->findAll();

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }

    /**
     * Все логин сервера
     *
     * @return Ls[]
     */
    public static function getAllLoginServers()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class),
        ]);
        $list = [];

        /** @var Ls[] $res */
        $res = Ls::model()->cache(3600, $dependency)->findAll();

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }
}
