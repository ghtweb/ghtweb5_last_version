<?php

class Lineage
{
    private static $_instance;

    /**
     * Конфиг
     * @var Gs|Ls
     */
    protected $_config;

    /**
     * Подключение к БД
     * @var CDbConnection
     */
    protected $_db;

    /**
     * Запросы под сервер
     * @var
     */
    protected $_query;


    /**
     * @param string $type gs|ls
     * @param int $id
     *
     * @return Lineage
     */
    public static function getInstance($type, $id)
    {
        $type = strtolower($type);
        $id = (int)$id;

        if (!isset(self::$_instance[$type][$id])) {
            self::$_instance[$type][$id] = new self($type, $id);
        }

        return self::$_instance[$type][$id];
    }

    private function __construct($type, $id)
    {
        $this->init($type, $id);

        return $this;
    }

    /**
     * @param int $id
     *
     * @return Lineage
     */
    public static function gs($id)
    {
        return self::getInstance('gs', $id);
    }

    /**
     * @param int $id
     *
     * @return Lineage
     */
    public function ls($id)
    {
        return self::getInstance('ls', $id);
    }


    protected function __clone()
    {
    }

    /**
     * @param string $type gs|ls
     * @param int $id
     *
     * @throws Exception
     *
     * @return void
     */
    private function init($type, $id)
    {
        if ($type == 'gs') {
            $this->_config = isset(Gs::getOpenServers()[$id]) ? Gs::getOpenServers()[$id] : [];
        } elseif ($type == 'ls') {
            $this->_config = isset(Ls::getOpenLoginServers()[$id]) ? Ls::getOpenLoginServers()[$id] : [];
        }

        if (!$this->_config) {
            Yii::log('Настройки в БД для ' . $type . ' с ID ' . $id . ' не найдены', CLogger::LEVEL_ERROR, 'Lineage::init');
            throw new Exception('Настройки в БД для ' . $type . ' с ID ' . $id . ' не найдены');
        }
    }

    public function connect()
    {
        if (!$this->_db) {
            // Подключаюсь к БД
            $db = Yii::createComponent([
                'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=' . $this->config('db_host') . ';port=' . $this->config('db_port') . ';dbname=' . $this->config('db_name'),
                'enableProfiling' => YII_DEBUG,
                'enableParamLogging' => true,
                'username' => $this->config('db_user'),
                'password' => $this->config('db_pass'),
                'charset' => 'utf8',
                'emulatePrepare' => true,
                'tablePrefix' => '',
            ]);

            $this->_db = $db;
        }

        $this->loadQuery();

        return $this;
    }

    /**
     * Подключение файла с запросами
     *
     * @throws CException
     *
     * @return void
     */
    private function loadQuery()
    {
        // Подключение файлов с запросами
        $version = ucfirst($this->config('version'));

        $className = $version;

        Yii::import('app.l2j.' . $className);

        if (!class_exists($className)) {
            throw new CException('Файл с запросами не найден ' . Yii::getPathOfAlias('app.l2j.' . $className));
        }

        $this->_query = new $className($this);
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return isset($this->_config[$key]) ? $this->_config[$key] : $default;
    }

    /**
     * @return Gs|Ls
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Запросы под сервер
     *
     * @return object
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Подключение к БД
     *
     * return CDbConnection
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @throws CHttpException
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (method_exists($this->_query, $method)) {
            return call_user_func_array([$this->_query, $method], $params);
        }

        Yii::log('Метод ' . $method . ' в классе ' . get_class($this->_query) . ' не найден', CLogger::LEVEL_ERROR, 'Lineage::__call');
        throw new CHttpException(500, 'Метод ' . $method . ' в классе ' . get_class($this->_query) . ' не найден');
    }

    /**
     * Шифровка пароля
     *
     * @param $password
     *
     * @return string
     */
    public function passwordEncrypt($password)
    {
        if ($this->config('password_type') == Ls::PASSWORD_TYPE_WHIRLPOOL) {
            return base64_encode(hash('whirlpool', $password, true));
        }

        return base64_encode(pack('H*', sha1(utf8_encode($password))));
    }

    /**
     * Возвращает инфу сколько провер юзер в игре
     *
     * @param int $time
     *
     * @return string
     */
    public static function getOnlineTime($time)
    {
        $hours = floor($time / 3600);
        $time = $time - ($hours * 3600);
        $minutes = floor($time / 60);

        return Yii::t('main', '{n} час|{n} часа|{n} часов|{n} часа', $hours) . ' ' .
            Yii::t('main', '{n} минута|{n} минуты|{n} минут|{n} минуты', $minutes);
    }

    /**
     * Возвращает грейд предмена, определяется по типу кристалов получаемых с предмета
     *
     * @param int $crystal_type
     *
     * @return string
     */
    public static function getItemGrade($crystal_type)
    {
        $symbol = self::getGradeName($crystal_type);

        if ($symbol != '') {
            //return CHtml::image(app()->createUrl(AllItems::ITEM_GRADE_ICONS_PATH) . $symbol . '.gif', $symbol, array('title' => $symbol));
        }

        //return $symbol;
        return '';
    }

    /**
     * Возвращает название грейда
     *
     * @param $crystal_type
     *
     * @return string
     */
    public static function getGradeName($crystal_type)
    {
        $symbol = '';

        switch ($crystal_type) {
            case 0:
                $symbol = '';
                break;
            case 1:
                $symbol = 'D';
                break;
            case 2:
                $symbol = 'C';
                break;
            case 3:
                $symbol = 'B';
                break;
            case 4:
                $symbol = 'A';
                break;
            case 5:
                $symbol = 'S';
                break;
            case 6:
                $symbol = 'S80';
                break;
            case 8:
                $symbol = 'R';
                break;
            case 9:
                $symbol = 'R95';
                break;
            case 10:
                $symbol = 'R99';
                break;
        }

        return $symbol;
    }

    /**
     * Расположение предмета у персонажа
     *
     * @param $loc
     *
     * @return string
     */
    public static function getItemLocName($loc)
    {
        switch ($loc) {
            case 'INVENTORY':
                return 'Инвентарь';
            case 'PAPERDOLL':
                return 'Одето';
            case 'WAREHOUSE':
                return 'На складе';
            default:
                return $loc;
        }
    }

    /**
     * Иконка для предмета
     *
     * @param string $icon
     * @param string $title
     *
     * @return string
     */
    public static function getItemIcon($icon, $title = '')
    {
        $icon = strtolower($icon);
        $url = app()->createUrl(AllItems::ITEM_ICONS_PATH);
        $title = CHtml::encode($title);

        if (is_file(Yii::getPathOfAlias('webroot') . '/' . AllItems::ITEM_ICONS_PATH . '/' . $icon . '.jpg')) {
            return CHtml::image($url . $icon . '.jpg', $title, ['title' => $title]);
        }

        return CHtml::image($url . 'no-image.jpg', $title, ['title' => $title]);
    }

    /**
     * Накрутка онлайна
     *
     * @param int $online
     * @param int $percent
     *
     * @return int
     */
    public static function fakeOnline($online, $percent)
    {
        return ceil($online * $percent / 100);
    }

    /**
     * Название замка
     *
     * @param int $castleId
     *
     * @return string
     */
    public static function getCastleName($castleId = 0)
    {
        return isset(app()->params['l2']['castle'][$castleId]) ? app()->params['l2']['castle'][$castleId] : app()->params['l2']['castle'][0];
    }

    /**
     * Название форта
     *
     * @param int $fortId
     *
     * @return string
     */
    public static function getFortName($fortId = 0)
    {
        return isset(app()->params['l2']['forts'][$fortId]) ? app()->params['l2']['forts'][$fortId] : 'Нет форта';
    }

    /**
     * Возвращает название класса
     *
     * @param int $classId
     *
     * @return string
     */
    public static function getClassName($classId)
    {
        return isset(app()->params['l2']['classList'][$classId]['name']) ? app()->params['l2']['classList'][$classId]['name'] : 'n/a';
    }

    /**
     * Рандомный город
     *
     * @return array
     */
    public static function getRandomCity()
    {
        $city = app()->params['l2']['listCities'];

        return $city[array_rand($city, 1)];
    }

    /**
     * Пол персонажа
     *
     * @param $sex
     *
     * @return string
     */
    public static function getGender($sex)
    {
        return $sex == 1 ? 'женщина' : 'мужчина';
    }

    /**
     * Названия для статистики
     *
     * @param string $type
     *
     * @return string
     */
    public static function statsName($type)
    {
        switch ($type) {
            case 'total':
                return 'Общая';
            case 'pvp':
                return 'ПВП';
            case 'pk':
                return 'ПК';
            case 'clans':
                return 'Кланы';
            case 'castles':
                return 'Замки';
            case 'online':
                return 'В игре';
            case 'top':
                return 'ТОП';
            case 'rich':
                return 'Богачи';
            case 'items':
                return 'Предметы';
            default:
                return 'n/a';
        }
    }

    /**
     * Статус сервера вкл/выкл
     *
     * @param string $host
     * @param int $port
     * @param int $timeout
     *
     * @return string
     */
    public static function getServerStatus($host, $port, $timeout = 1)
    {
        $online = 'offline';
        $sock = @fsockopen($host, $port, $errno, $errstr, $timeout);

        if ($sock) {
            @fclose($sock);
            $online = 'online';
        }

        return $online;
    }

    /**
     * Иконка клана|алли
     *
     * @param string $type (crest|ally)
     * @param int $gsId
     * @param int $id (clan_id|ally_id)
     * @param $crest
     *
     *
     */
    public static function getCrestIcon($type, $gsId, $id, $crest)
    {

    }

    /**
     * Иконка замка
     *
     * @param int $castleId
     *
     * @return string
     */
    public static function getCastleIcon($castleId)
    {
        $castleName = self::getCastleName($castleId);

        return CHtml::image(app()->createUrl('images/castles') . $castleId . '.jpg', $castleName, ['title' => $castleName]);
    }
}
 