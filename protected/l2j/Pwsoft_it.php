<?php

/**
 * Сайт команды разработчиков: http://pwsoft.ru/server/
 * Версия сервера на которуй писались запросы: 0.0.0
 * Автор: ght^^ (http://ghtweb.ru/)
 * Дата создания файла с запросами: 2014-11-11 20:38:55
 */
class Pwsoft_it extends AbstractQuery
{
    /**
     * Поля из БД
     * @var array
     */
    private $_fields = [
        'accounts.access_level' => 'accounts.access_level',
        'characters.access_level' => 'characters.accesslevel',
        'characters.char_id' => 'characters.obj_Id',
        'clan_data.clan_id' => 'clan_data.clan_id',
    ];

    /**
     * Создание игрового аккаунта
     *
     * @param string $login
     * @param string $password
     * @param int $access_level
     *
     * @return bool
     */
    public function insertAccount($login, $password, $access_level = 0)
    {
        $encodePassword = $this->_context->passwordEncrypt($password);

        return $this->_db->createCommand('INSERT INTO `accounts` (`login`, `password`, `access_level`) VALUES(:login, :password, :access_level)')
            ->bindParam('login', $login, PDO::PARAM_STR)
            ->bindParam('password', $encodePassword, PDO::PARAM_STR)
            ->bindParam('access_level', $access_level, PDO::PARAM_INT)
            ->execute();
    }

    /**
     * Возвращает аккаунты
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function accounts($command = null)
    {
        if (!($command instanceof CDbCommand)) {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
login, password, access_level, lastactive AS last_active 
FROM accounts
        */

        return $command
            ->select('login, password, access_level, lastactive AS last_active')
            ->from('accounts');
    }

    /**
     * Возвращает список персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function characters($command = null)
    {
        if (!($command instanceof CDbCommand)) {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp, clan_data.clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score 
FROM characters 
LEFT JOIN clan_data ON clan_data.clan_id = characters.clanid
        */

        return $command
            ->select('characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp, clan_data.clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score')
            ->leftJoin('clan_data', 'clan_data.clan_id = characters.clanid')
            ->from('characters');
    }

    /**
     * Информация о кланах, лидере, алли и кол-ве персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function clans($command = null)
    {
        if (!($command instanceof CDbCommand)) {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, (SELECT COUNT(0) FROM characters WHERE characters.clanid = clan_data.clan_id) as ccount, ally_name, ally_crest_id AS ally_crest, ally_id, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp 
FROM clan_data 
LEFT JOIN characters ON characters.obj_Id = clan_data.leader_id
        */

        return $command
            ->select('clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, (SELECT COUNT(0) FROM characters WHERE characters.clanid = clan_data.clan_id) as ccount, ally_name, ally_crest_id AS ally_crest, ally_id, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp')
            ->leftJoin('characters', 'characters.obj_Id = clan_data.leader_id')
            ->from('clan_data');
    }

    /**
     * Предметы персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function items($command = null)
    {
        if (!($command instanceof CDbCommand)) {
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
        $maxId = $this->_db->createCommand('SELECT MAX(object_id) + 1 FROM `items`')->queryScalar();

        return $this->_db->createCommand('INSERT INTO `items` (`owner_id`, `object_id`, `item_id`, `count`, `enchant_level`, `loc`) VALUES(:owner_id, :object_id, :item_id, :count, :enchant_level, :loc)')
            ->bindParam('owner_id', $ownerId, PDO::PARAM_INT)
            ->bindParam('item_id', $itemId, PDO::PARAM_INT)
            ->bindParam('count', $count, PDO::PARAM_INT)
            ->bindParam('enchant_level', $enchantLevel, PDO::PARAM_INT)
            ->bindParam('loc', 'INVENTORY', PDO::PARAM_STR)
            ->bindParam('object_id', $maxId, PDO::PARAM_INT)
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
        $maxId = $this->_db->createCommand('SELECT MAX(object_id) + 1 FROM `items`')->queryScalar();

        // Заменяю enchant на enchant_level, добавляю object_id
        foreach ($items as $k => $v) {
            $items[$k]['object_id'] = $maxId++;
            $items[$k]['enchant_level'] = $v['enchant'];
            unset($items[$k]['enchant']);
        }

        $command = $this->_db->schema->commandBuilder->createMultipleInsertCommand('items', $items);

        return $command->execute();
    }

    /**
     * Возвращает кол-во людей
     *
     * @return int
     */
    public function getCountRaceHuman()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во эльфов
     *
     * @return int
     */
    public function getCountRaceElf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 1')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во тэмных эльфов
     *
     * @return int
     */
    public function getCountRaceDarkElf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 2')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во орков
     *
     * @return int
     */
    public function getCountRaceOrk()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 3')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во гномов
     *
     * @return int
     */
    public function getCountRaceDwarf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 4')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во камаэлей
     *
     * @return int
     */
    public function getCountRaceKamael()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 5')
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

        $command->order = 'clan_level DESC, reputation_score DESC';
        $command->limit = $limit;
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
castle.id, castle.name, castle.taxPercent, castle.siegeDate, clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, ally_name, ally_crest_id AS ally_crest, ally_id 
FROM castle 
LEFT JOIN clan_data ON castle.id = clan_data.hasCastle
        */
        return $this->_db->createCommand()
            ->select('castle.id, castle.name, castle.taxPercent, castle.siegeDate, clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, ally_name, ally_crest_id AS ally_crest, ally_id')
            ->leftJoin('clan_data', 'castle.id = clan_data.hasCastle')
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
siege_clans.castle_id, siege_clans.type, clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, ally_name, ally_crest_id AS ally_crest, ally_id 
FROM siege_clans 
LEFT JOIN clan_data ON siege_clans.clan_id = clan_data.clan_id
        */
        return $this->_db->createCommand()
            ->select('siege_clans.castle_id, siege_clans.type, clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, ally_name, ally_crest_id AS ally_crest, ally_id')
            ->leftJoin('clan_data', 'siege_clans.clan_id = clan_data.clan_id')
            ->from('siege_clans')->queryAll();
    }

    /**
     * Хроники сервера
     *
     * @return string
     */
    public function getChronicle()
    {
        return 'it';
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
        return isset($this->_fields[$fieldName]) ? $this->_fields[$fieldName] : null;
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
     * Контроль предметов
     *
     * @param array $itemList (ID предметов по которым будет выборка)
     *
     * @return array
     */
    public function getItemsControl(array $itemList)
    {
        if (!$itemList) {
            return [];
        }

        $res = AllItems::model()->findAllByAttributes([
            'item_id' => $itemList,
        ]);

        $itemNames = [];

        foreach ($res as $row) {
            $itemNames[$row->getPrimaryKey()] = $row;
        }

        unset($res);

        /*
SELECT 
MAX(items.count) AS maxCountItems, COUNT(items.count) AS countItems, items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp, clan_data.clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score 
FROM items 
LEFT JOIN characters ON items.owner_id = characters.obj_Id
LEFT JOIN clan_data ON clan_data.clan_id = characters.clanid 
GROUP BY items.owner_id, items.item_id
        */
        $res = $this->_db->createCommand()
            ->select('MAX(items.count) AS maxCountItems, COUNT(items.count) AS countItems, items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data, characters.account_name, characters.obj_Id AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, characters.accesslevel AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp, clan_data.clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score')
            ->leftJoin('characters', 'items.owner_id = characters.obj_Id')
            ->leftJoin('clan_data', 'clan_data.clan_id = characters.clanid')
            ->andWhere(['in', 'item_id', $itemList])
            ->group('items.owner_id, items.item_id')
            ->from('items')
            ->queryAll();

        $characters = [];

        foreach ($res as $item) {
            if (!isset($characters[$item['item_id']]['maxTotalItems'])) {
                $characters[$item['item_id']]['maxTotalItems'] = 0;
            }

            $characters[$item['item_id']]['itemInfo'] = $itemNames[$item['item_id']];
            $characters[$item['item_id']]['characters'][] = $item;
            $characters[$item['item_id']]['maxTotalItems'] += $item['maxCountItems'];
            $characters[$item['item_id']]['totalItems'] = count($characters[$item['item_id']]['characters']);
        }

        foreach (array_diff_key($itemNames, $characters) as $item) {
            $characters[$item->item_id]['itemInfo'] = $item;
            $characters[$item->item_id]['characters'] = [];
            $characters[$item->item_id]['maxTotalItems'] = 0;
            $characters[$item->item_id]['totalItems'] = 0;
        }

        return $characters;
    }
}