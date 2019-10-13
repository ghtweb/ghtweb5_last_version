<?php

/**
 * This is the model class for table "{{tickets}}".
 *
 * The followings are the available columns in table '{{tickets}}':
 * @property string $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $priority
 * @property string $date_incident
 * @property string $char_name
 * @property string $title
 * @property integer $status
 * @property integer $new_message_for_user
 * @property integer $new_message_for_admin
 * @property integer $gs_id
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property TicketsCategories $category
 * @property TicketsAnswers $answers
 * @property Users $user
 * @property Gs $gs
 */
class Tickets extends ActiveRecord
{
    // Приоритет высокий
    const PRIORITY_HIGH = 2;

    // Приоритет средний
    const PRIORITY_MID = 1;

    // Приоритет низкий
    const PRIORITY_LOW = 0;


    // Новые сообщения есть
    const STATUS_NEW_MESSAGE_ON = 1;

    // Новых сообщений нет
    const STATUS_NEW_MESSAGE_OFF = 0;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['ClearCacheBehavior'] = [
            'class' => 'application.components.behaviors.ClearCacheBehavior',
            'className' => Tickets::class,
            'idAttributeName' => ['id', 'user_id'],
        ];

        return $behaviors;
    }

    public function init()
    {
        parent::init();

        $this->attachEventHandler('onAfterSave', [$this, 'clearCache']);
    }

    public function getPrioritiesList()
    {
        return [
            self::PRIORITY_HIGH => 'Высокий',
            self::PRIORITY_MID => 'Средний',
            self::PRIORITY_LOW => 'Низкий',
        ];
    }

    public function getPriority()
    {
        $data = $this->getPrioritiesList();

        return isset($data[$this->priority]) ? $data[$this->priority] : '*Unknown*';
    }

    public function getStatusList()
    {
        return [
            ActiveRecord::STATUS_ON => 'Открыт',
            ActiveRecord::STATUS_OFF => 'Закрыт',
        ];
    }

    /**
     * Есть ли новые сообщения для админа
     *
     * @return string
     */
    public function isNewMessageForAdmin()
    {
        return $this->new_message_for_admin ? 'Есть' : 'Нет';
    }

    /**
     * Есть ли новые сообщения для юзера
     *
     * @return string
     */
    public function isNewMessageForUser()
    {
        return $this->new_message_for_user ? 'Есть' : 'Нет';
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{tickets}}';
    }

    public function rules()
    {
        return [
            ['id, category_id, priority, title, new_message_for_admin, status, gs_id, user_id', 'filter', 'filter' => 'trim'],
            ['id, category_id, priority, title, new_message_for_admin, status, gs_id', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'category' => [self::HAS_ONE, TicketsCategories::class, ['id' => 'category_id']],
            'answers' => [self::HAS_MANY, TicketsAnswers::class, 'ticket_id'],
            'user' => [self::HAS_ONE, Users::class, ['user_id' => 'user_id']],
            'admin' => [self::HAS_ONE, Admin::class, ['id' => 'user_id']],
            'gs' => [self::HAS_ONE, Gs::class, ['id' => 'gs_id']],
        ];
    }

    public function getDate()
    {
        return date('Y-m-d H:i', strtotime($this->created_at));
    }

    /**
     * Кол-во новых сообщение для админа
     *
     * @return int
     */
    public static function getCountNewMessagesForAdmin()
    {
        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(self::class . 'getCountNewMessagesForAdmin'),
        ]);

        return Tickets::model()->cache(60, $dependency)->opened()->count('new_message_for_admin = :status', ['status' => Tickets::STATUS_NEW_MESSAGE_ON]);
    }

    /**
     * Кол-во новых сообщение для юзера
     *
     * @return int
     */
    public static function getCountNewMessagesForUser()
    {
        $userId = user()->getId();

        $dependency = new TaggedCache\Dependency([
            new \TaggedCache\Tag(Tickets::class . 'user_id:' . $userId),
        ]);

        return Tickets::model()->cache(60, $dependency)->opened()->count('new_message_for_user = :status AND user_id = :user_id', [
            'status' => Tickets::STATUS_NEW_MESSAGE_ON,
            'user_id' => $userId,
        ]);
    }

    public function clearCache(CEvent $event)
    {
        /** @var Tickets $model */
        $model = $event->sender;

        // Очистка кэша с новыми сообщениями для юзера
        (new \TaggedCache\Tag(Tickets::class . '.countAnswer.' . $model->user_id))->delete();

        // Очистка списка тикетов для юзера
        (new \TaggedCache\Tag(Tickets::class . '.all.' . $model->user_id))->delete();

        // Очистка для одного тикета юзера
        (new \TaggedCache\Tag(Tickets::class . '.' . $model->id . '.' . $model->user_id))->delete();

    }
}
