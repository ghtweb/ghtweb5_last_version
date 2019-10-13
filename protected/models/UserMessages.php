<?php

/**
 * This is the model class for table "{{user_messages}}".
 *
 * The followings are the available columns in table '{{user_messages}}':
 * @property string $id
 * @property string $user_id
 * @property string $message
 * @property integer $read
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class UserMessages extends ActiveRecord
{
    const STATUS_READ = 1; // Прочитано
    const STATUS_NOT_READ = 0; // Не прочитано


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_messages}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => UserMessages::class,
            'idAttributeName' => ['id', 'user_id'],
        ];

        return $behaviors;
    }

    /**
     * Возвращает небольшую часть сообщения
     *
     * @param int $count_word (кол-во слов которые надо вернуть)
     *
     * @return string
     */
    public function getShortMessage($count_word = 10)
    {
        return wordLimiter(e(strip_tags($this->message)), $count_word, ' ...');
    }

    /**
     * @param int $userId
     * @return UserMessages[]
     */
    public static function getMessagesByUserId($userId)
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(UserMessages::class . $userId)
        ]);
        $list = [];

        /** @var UserMessages[] $res */
        $res = UserMessages::model()->cache(3600, $dependency)->findAll('user_id = ?', [$userId]);

        foreach ($res as $item) {
            $list[$item->getPrimaryKey()] = $item;
        }

        return $list;
    }
}
