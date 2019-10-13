<?php

/**
 * Сайт команды разработчиков: 
 * Версия сервера на которой писались запросы: 14920
 * Автор: ght^^ (http://ghtweb.ru/)
 * Дата создания файла с запросами: 2018-09-02 12:23:21
 */
class L2j_scripts_god
{
    /**
     * @var CDbConnection
     */
    private $_db;

    /**
     * @var Lineage
     */
    private $_context;

    /**
     * Поля из БД
     * @var array
     */
    private $_fields = array(
        'accounts.access_level'   => 'accounts.access_level',
        'characters.access_level' => 'characters.accesslevel',
        'characters.char_id'      => 'characters.obj_Id',
        'clan_data.clan_id'       => 'clan_data.clan_id',
    );



    public function __construct($context)
    {
        $this->_context = $context;
        $this->_db = $context->getDb();
    }

    /**
     * Создание игрового аккаунта
     *
     * @param string $login
     * @param string $password
     * @param int $accessLevel
     *
     * @return bool
     */
    public function insertAccount($login, $password, $accessLevel = 0)
    {
        $encodePassword = $this->_context->passwordEncrypt($password);

        return $this->_db->createCommand('INSERT INTO `accounts` (`login`, `password`, `access_level`) VALUES(:login, :password, :access_level)')
            ->bindParam('login', $login, PDO::PARAM_STR)
            ->bindParam('password', $encodePassword, PDO::PARAM_STR)
            ->bindParam('access_level', $accessLevel, PDO::PARAM_INT)
            ->execute();
    }

    /**
     * Возвращает аккаунты
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function accounts($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
login, password, access_level, last_access AS last_active 
FROM accounts
        */
        return $command
			->select('login, password, access_level, last_access AS last_active')
			->from('accounts');
    }

    /**
     * Возвращает список персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function characters($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, character_subclasses.class_id AS base_class, character_subclasses.level, character_subclasses.exp, character_subclasses.sp, character_subclasses.maxHp, character_subclasses.curHp, character_subclasses.maxCp, character_subclasses.curCp, character_subclasses.maxMp, character_subclasses.curMp, clan_subpledges.name AS clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score 
FROM characters 
LEFT JOIN character_subclasses ON character_subclasses.char_obj_id = characters.obj_Id AND character_subclasses.type = 0
LEFT JOIN clan_data ON clan_data.clan_id = characters.clanid
LEFT JOIN clan_subpledges ON clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0
        */
        return $command
			->select('characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, character_subclasses.class_id AS base_class, character_subclasses.level, character_subclasses.exp, character_subclasses.sp, character_subclasses.maxHp, character_subclasses.curHp, character_subclasses.maxCp, character_subclasses.curCp, character_subclasses.maxMp, character_subclasses.curMp, clan_subpledges.name AS clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score')
			->leftJoin('character_subclasses', 'character_subclasses.char_obj_id = characters.obj_Id AND character_subclasses.type = 0')
			->leftJoin('clan_data', 'clan_data.clan_id = characters.clanid')
			->leftJoin('clan_subpledges', 'clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0')
			->from('characters');
    }

    /**
     * Информация о кланах, лидере, алли и кол-ве персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function clans($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
clan_data.clan_id, clan_subpledges.name AS clan_name, clan_subpledges.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score, (SELECT COUNT(0) FROM characters WHERE characters.clanid = clan_data.clan_id) as ccount, ally_data.ally_name, ally_data.crest AS ally_crest, ally_data.ally_id, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, character_subclasses.class_id AS base_class, character_subclasses.level, character_subclasses.exp, character_subclasses.sp, character_subclasses.maxHp, character_subclasses.curHp, character_subclasses.maxCp, character_subclasses.curCp, character_subclasses.maxMp, character_subclasses.curMp 
FROM clan_data 
LEFT JOIN clan_subpledges ON clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0
LEFT JOIN ally_data ON clan_data.ally_id = ally_data.ally_id
LEFT JOIN characters ON characters.obj_Id = clan_subpledges.leader_id
LEFT JOIN character_subclasses ON character_subclasses.char_obj_id = characters.obj_Id AND character_subclasses.type = 0
        */
        return $command
			->select('clan_data.clan_id, clan_subpledges.name AS clan_name, clan_subpledges.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score, (SELECT COUNT(0) FROM characters WHERE characters.clanid = clan_data.clan_id) as ccount, ally_data.ally_name, ally_data.crest AS ally_crest, ally_data.ally_id, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, character_subclasses.class_id AS base_class, character_subclasses.level, character_subclasses.exp, character_subclasses.sp, character_subclasses.maxHp, character_subclasses.curHp, character_subclasses.maxCp, character_subclasses.curCp, character_subclasses.maxMp, character_subclasses.curMp')
			->leftJoin('clan_subpledges', 'clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0')
			->leftJoin('ally_data', 'clan_data.ally_id = ally_data.ally_id')
			->leftJoin('characters', 'characters.obj_Id = clan_subpledges.leader_id')
			->leftJoin('character_subclasses', 'character_subclasses.char_obj_id = characters.obj_Id AND character_subclasses.type = 0')
			->from('clan_data');
    }

    /**
     * Предметы персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function items($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data 
FROM items
        */
        return $command
			->select('items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data')
			->from('items');
    }

    /**
     * Добавление предмета в игру
     *
     * @param int $ownerId
     * @param int $itemId
     * @param int $count
     * @param int $enchantLevel
     *
     * @return bool
     */
    public function insertItem($ownerId, $itemId, $count = 1, $enchantLevel = 0)
    {
        		return $this->_db->createCommand('INSERT INTO `items_delayed` (`owner_id`, `item_id`, `count`, `enchant_level`, `description`) VALUES(:owner_id, :item_id, :count, :enchant_level, :description)')
            ->bindParam('owner_id', $ownerId, PDO::PARAM_INT)
            ->bindParam('item_id', $itemId, PDO::PARAM_INT)
            ->bindParam('count', $count, PDO::PARAM_INT)
            ->bindParam('enchant_level', $enchantLevel, PDO::PARAM_INT)
			->bindParam('description', 'GHTWEB v5', PDO::PARAM_STR)
			->execute();
    }

    /**
     * Добавление дохуя предметов в игру одним запросом
     *
     * @param array $items
     * <code>
     *     array(
     *         'owner_id' => 111111
     *         'item_id' => 57
     *         'count' => 100
     *         'enchant' => 0
     *     )
     * </code>
     *
     * @return bool
     */
    public function multiInsertItem(array $items)
    {
        // Докидываю описание
        foreach($items as $k => $v)
        {
            $items[$k]['description'] = 'GHTWEB v5';
        }

        $command = $this->_db->schema->commandBuilder->createMultipleInsertCommand('items_delayed', $items);
        return $command->execute();
    }

    /**
     * Возвращает кол-во людей
     *
     * @return int
     */
    public function getCountRaceHuman()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM character_subclasses WHERE class_id IN (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98) AND character_subclasses.type = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во эльфов
     *
     * @return int
     */
    public function getCountRaceElf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM character_subclasses WHERE class_id IN (18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 99, 100, 101, 102, 103, 104, 105) AND character_subclasses.type = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во тэмных эльфов
     *
     * @return int
     */
    public function getCountRaceDarkElf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM character_subclasses WHERE class_id IN (31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 106, 107, 108, 109, 110, 111, 112) AND character_subclasses.type = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во орков
     *
     * @return int
     */
    public function getCountRaceOrk()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM character_subclasses WHERE class_id IN (44, 45, 46, 47, 48, 49, 50, 51, 52, 113, 114, 115, 116) AND character_subclasses.type = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во гномов
     *
     * @return int
     */
    public function getCountRaceDwarf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM character_subclasses WHERE class_id IN (53, 54, 55, 56, 57, 117, 118) AND character_subclasses.type = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во камаэлей
     *
     * @return int
     */
    public function getCountRaceKamael()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM character_subclasses WHERE class_id IN (123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 139, 140, 141, 142, 143, 144, 145, 146) AND character_subclasses.type = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во аккаунтов
     *
     * @return int
     */
    public function getCountAccounts()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM accounts')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во персонажей
     *
     * @return int
     */
    public function getCountCharacters()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во персонажей в игре
     *
     * @return int
     */
    public function getCountOnlineCharacters()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE online = 1')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во торговцев в игре
     *
     * @return int
     */
    public function getCountOfflineTraders()
    {
        // TODO!!!
        return 0;
    }

    /**
     * Возвращает кол-во кланов
     *
     * @return int
     */
    public function getCountClans()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM clan_data')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во мужчин
     *
     * @return int
     */
    public function getCountMen()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE sex = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во женщин
     *
     * @return int
     */
    public function getCountWomen()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE sex = 1')
            ->queryScalar();
    }

    /**
     * Возвращает Топ ПВП
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopPvp($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->where('pvpkills > 0')
			->andWhere('accesslevel = 0')
            ->order('pvpkills DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ ПК
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopPk($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->where('pkkills > 0')
			->andWhere('accesslevel = 0')
            ->order('pkkills DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ игроков
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTop($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
			->where('accesslevel = 0')
            ->order('exp DESC, sp DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ богачей
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopRich($limit = 20, $offset = 0)
    {
        $command = $this->characters();

        return $command->select($command->getSelect() . ', SUM(items.count) AS adena_count')
            ->where('items.item_id = 57')
			->andWhere('accesslevel = 0')
            ->order('adena_count DESC')
            ->limit($limit)
            ->offset($offset)
            ->group('characters.obj_Id')
            ->leftJoin('items', 'items.owner_id = characters.obj_Id')
            ->queryAll();
    }

    /**
     * Возвращает кто в игре
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getOnline($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->where('online = 1')
			->andWhere('accesslevel = 0')
            ->order('level DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ кланов
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopClans($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand();

        $command->order  = 'clan_level DESC, reputation_score DESC';
        $command->limit  = $limit;
        $command->offset = $offset;

        return $this->clans($command)
            ->queryAll();
    }

    /**
     * Возвращает список замков и инфу о владельцах
     *
     * @return array
     */
    public function getCastles()
    {
        /*
SELECT 
castle.id, castle.name, castle.tax_percent AS taxPercent, castle.siege_date AS siegeDate, clan_data.clan_id, clan_subpledges.name AS clan_name, clan_subpledges.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score, ally_data.ally_name, ally_data.crest AS ally_crest, ally_data.ally_id 
FROM castle 
LEFT JOIN clan_data ON castle.id = clan_data.hasCastle
LEFT JOIN clan_subpledges ON clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0
LEFT JOIN ally_data ON clan_data.ally_id = ally_data.ally_id
        */
        return $this->_db->createCommand()
			->select('castle.id, castle.name, castle.tax_percent AS taxPercent, castle.siege_date AS siegeDate, clan_data.clan_id, clan_subpledges.name AS clan_name, clan_subpledges.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score, ally_data.ally_name, ally_data.crest AS ally_crest, ally_data.ally_id')
			->leftJoin('clan_data', 'castle.id = clan_data.hasCastle')
			->leftJoin('clan_subpledges', 'clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0')
			->leftJoin('ally_data', 'clan_data.ally_id = ally_data.ally_id')
			->from('castle')->queryAll();
    }

    /**
     * Возвращает информацию о кланах и алли которые принимают участие в осаде
     *
     * @return array
     */
    public function getSiege()
    {
        /*
SELECT 
siege_clans.residence_id AS castle_id, IF(siege_clans.type="attackers",1,0) as type, clan_data.clan_id, clan_subpledges.name AS clan_name, clan_subpledges.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score, ally_data.ally_name, ally_data.crest AS ally_crest, ally_data.ally_id 
FROM siege_clans 
LEFT JOIN clan_data ON siege_clans.clan_id = clan_data.clan_id
LEFT JOIN clan_subpledges ON clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0
LEFT JOIN ally_data ON clan_data.ally_id = ally_data.ally_id
        */
        return $this->_db->createCommand()
			->select('siege_clans.residence_id AS castle_id, IF(siege_clans.type="attackers",1,0) as type, clan_data.clan_id, clan_subpledges.name AS clan_name, clan_subpledges.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score, ally_data.ally_name, ally_data.crest AS ally_crest, ally_data.ally_id')
			->leftJoin('clan_data', 'siege_clans.clan_id = clan_data.clan_id')
			->leftJoin('clan_subpledges', 'clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0')
			->leftJoin('ally_data', 'clan_data.ally_id = ally_data.ally_id')
			->from('siege_clans')->queryAll();
    }

    /**
     * Хроники сервера
     *
     * @return string
     */
    public function getChronicle()
    {
        return 'god';
    }

    /**
     * Возвращает название поля из таблицы
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function getField($fieldName)
    {
        return isset($this->_fields[$fieldName]) ? $this->_fields[$fieldName] : NULL;
    }

    /**
     * Возвращает название сервера
     *
     * @return string
     */
    public function getServerName()
    {
        return __CLASS__;
    }

    /**
     * Информация о премиум аккаунте
     * Возвращает дату окончания в timestamp формате
     *
     * @param string $accountName
     *
     * @return array
     *
     * <code>
     *     array(
     *         'dateEnd' => 1234567890
     *     )
     * </code>
     *
     */
    public function getPremiumInfo($accountName)
    {
        // @TODO !!!!!
        return '';
    }

    /**
     * Добавление времени к премиум аккаунту
     *
     * @param string $accountName
     * @param int $timeEnd
     *
     * @return bool
     */
    public function addPremium($accountName, $timeEnd)
    {
        // @TODO !!!!!
        return '';
    }

    /**
     * Удаление привязки по HWID
     *
     * @param string $accountName
     *
     * @return bool
     */
    public function removeHWID($accountName)
    {
        // @TODO !!!!!
        return '';
    }

    /**
     * Контроль предметов
     *
     * @param array $itemList (ID предметов по которым будет выборка)
     *
     * @return array
     */
    public function getItemsControl(array $itemList)
    {
        if(!$itemList)
        {
            return array();
        }

        $res = AllItems::model()->findAllByAttributes(array(
            'item_id' => $itemList,
        ));

        $itemNames = array();

        foreach($res as $row)
        {
            $itemNames[$row->getPrimaryKey()] = $row;
        }

        unset($res);

        /*
SELECT 
MAX(items.count) AS maxCountItems, COUNT(items.count) AS countItems, items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, character_subclasses.class_id AS base_class, character_subclasses.level, character_subclasses.exp, character_subclasses.sp, character_subclasses.maxHp, character_subclasses.curHp, character_subclasses.maxCp, character_subclasses.curCp, character_subclasses.maxMp, character_subclasses.curMp, clan_subpledges.name AS clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score 
FROM items 
LEFT JOIN characters ON items.owner_id = characters.obj_Id
LEFT JOIN character_subclasses ON character_subclasses.char_obj_id = characters.obj_Id AND character_subclasses.type = 0
LEFT JOIN clan_data ON clan_data.clan_id = characters.clanid
LEFT JOIN clan_subpledges ON clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0 
GROUP BY items.owner_id, items.item_id
        */
        $res = $this->_db->createCommand()
            ->select('MAX(items.count) AS maxCountItems, COUNT(items.count) AS countItems, items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, character_subclasses.class_id AS base_class, character_subclasses.level, character_subclasses.exp, character_subclasses.sp, character_subclasses.maxHp, character_subclasses.curHp, character_subclasses.maxCp, character_subclasses.curCp, character_subclasses.maxMp, character_subclasses.curMp, clan_subpledges.name AS clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest AS clan_crest, clan_data.reputation_score')
			->leftJoin('characters', 'items.owner_id = characters.obj_Id')
			->leftJoin('character_subclasses', 'character_subclasses.char_obj_id = characters.obj_Id AND character_subclasses.type = 0')
			->leftJoin('clan_data', 'clan_data.clan_id = characters.clanid')
			->leftJoin('clan_subpledges', 'clan_subpledges.clan_id = clan_data.clan_id AND clan_subpledges.type = 0')
			->andWhere(array('in', 'item_id', $itemList))
			->group('items.owner_id, items.item_id')
			->from('items')
			->queryAll();

        $characters = array();

        foreach($res as $item)
        {
            if(!isset($characters[$item['item_id']]['maxTotalItems']))
            {
                $characters[$item['item_id']]['maxTotalItems'] = 0;
            }

            $characters[$item['item_id']]['itemInfo'] = $itemNames[$item['item_id']];
            $characters[$item['item_id']]['characters'][] = $item;
            $characters[$item['item_id']]['maxTotalItems'] += $item['maxCountItems'];
            $characters[$item['item_id']]['totalItems'] = count($characters[$item['item_id']]['characters']);
        }

        foreach(array_diff_key($itemNames, $characters) as $item)
        {
            $characters[$item->item_id]['itemInfo'] = $item;
            $characters[$item->item_id]['characters'] = array();
            $characters[$item->item_id]['maxTotalItems'] = 0;
            $characters[$item->item_id]['totalItems'] = 0;
        }

        //prt($characters);die;

        return $characters;
    }
}