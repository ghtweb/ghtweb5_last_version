<?php

// Version >= 8.3
class Lucera2 extends AbstractQuery
{
    /**
     * Поля из БД
     * @var array
     */
    private $_fields = [
        'accounts.access_level' => 'accounts.accessLevel',
        //'characters.access_level' => 'characters.access_level', // В таблице такого поля нет
        'characters.char_id' => 'characters.charId',
        'clan_data.clan_id' => 'clan_data.clan_id',
    ];

    /**
     * Создание игрового аккаунта
     *
     * @param $login
     * @param $password
     * @param int $access_level
     *
     * @return bool
     */
    public function insertAccount($login, $password, $access_level = 0)
    {
        return $this->_db->createCommand()->insert('accounts', [
            'login' => $login,
            'password' => $this->_context->passwordEncrypt($password),
            'accessLevel' => $access_level,
        ]);
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

        return $command->select('login, password, lastactive AS last_active, accessLevel AS access_level')
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

        /*$where = $command->getWhere();
        $acf = str_replace('.', '\.', $this->_fields['characters.access_level']);

        // Удаляю access_level так как в таблице нет такого поля
        if(preg_match('#' . $acf . ' = ([0-9+])#', $where, $m))
        {
            $where = preg_replace('#' . $acf . ' = ([0-9]+)#', '', $where);
            $command->where = str_replace(array('() AND', 'AND ()'), '', $where);
        }*/

        return $command
            ->select('characters.account_name,characters.charId AS char_id,characters.char_name,characters.sex,characters.x,characters.y,characters.z,characters.karma,characters.pvpkills,characters.pkkills,characters.clanid AS clan_id,characters.title,characters.`online`,
                characters.onlinetime,characters.base_class,characters.`level`,characters.exp,characters.sp,characters.maxHp,characters.curHp,characters.maxCp,characters.curCp,characters.maxMp,characters.curMp,clan_data.clan_level,clan_data.hasCastle,clan_data.hasFort,
                clan_data.crest_id AS clan_crest,clan_data.reputation_score,clan_data.clan_name,(SELECT IF(valueData>0,1,0) FROM character_data WHERE character_data.charId = characters.charId AND character_data.valueName = "jail" LIMIT 1) as jail,0 as access_level')
            ->leftJoin('clan_data', 'characters.clanid = clan_data.clan_id')
            ->from('characters');
    }

    /**
     * Кланы + инфа о лидере + кол-во персонажей в клане
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

        return $command
            ->select('clan_data.clan_id,clan_data.clan_name,clan_data.clan_level,clan_data.hasCastle,clan_data.hasFort,clan_data.ally_id,clan_data.ally_name,clan_data.leader_id,clan_data.crest_id AS clan_crest,clan_data.crest_large_id AS clan_crest_large,clan_data.ally_crest_id AS ally_crest,
                clan_data.reputation_score,characters.char_name,characters.account_name,characters.charId AS char_id,characters.`level`,characters.maxHp,characters.curHp,characters.maxCp,characters.curCp,characters.maxMp,characters.curMp,characters.sex,characters.x,
                characters.y,characters.z,characters.exp,characters.sp,characters.karma,characters.pvpkills,characters.pkkills,characters.base_class,characters.title,characters.`online`,characters.onlinetime,(SELECT COUNT(0) FROM characters WHERE clanid = clan_data.clan_id) as ccount')
            ->leftJoin('characters', 'clan_data.leader_id = characters.charId')
            ->from('clan_data');
    }

    /**
     * Возвращает предметы
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

        return $command
            ->select('owner_id,object_id,item_id,count,enchant_level,loc,loc_data')
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
        return $this->_db->createCommand()
            ->insert('items_external', [
                'owner_id' => $ownerId,
                'item_id' => $itemId,
                'count' => $count,
                'enchant_level' => $enchantLevel,
            ]);
    }

    /**
     * Добавление дохуя предметов в игру
     *
     * @param array $items
     *
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
        // Докидываю необходимые данные
        foreach ($items as $k => $v) {
            $items[$k]['description'] = 'GHTWEB';
            $items[$k]['issued'] = 0;
        }

        $builder = $this->_db->schema->commandBuilder;
        $command = $builder->createMultipleInsertCommand('items_external', $items);

        return $command->execute();
    }

    /**
     * Возвращает кол-во людей
     *
     * @return int
     */
    public function getCountRaceHuman()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `race` = 0")->queryScalar();
    }

    /**
     * Возвращает кол-во эльфов
     *
     * @return int
     */
    public function getCountRaceElf()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `race` = 1")->queryScalar();
    }

    /**
     * Возвращает кол-во тэмных эльфов
     *
     * @return int
     */
    public function getCountRaceDarkElf()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `race` = 2")->queryScalar();
    }

    /**
     * Возвращает кол-во орков
     *
     * @return int
     */
    public function getCountRaceOrk()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `race` = 3")->queryScalar();
    }

    /**
     * Возвращает кол-во гномов
     *
     * @return int
     */
    public function getCountRaceDwarf()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `race` = 4")->queryScalar();
    }


    /**
     * Возвращает кол-во аккаунтов
     *
     * @return int
     */
    public function getCountAccounts()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{accounts}}`")->queryScalar();
    }

    /**
     * Возвращает кол-во персонажей
     *
     * @return int
     */
    public function getCountCharacters()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}`")->queryScalar();
    }

    /**
     * Возвращает кол-во персонажей в игре
     *
     * @return int
     */
    public function getCountOnlineCharacters()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `online` = 1")->queryScalar();
    }

    /**
     * Возвращает кол-во кланов
     *
     * @return int
     */
    public function getCountClans()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{clan_data}}`")->queryScalar();
    }

    /**
     * Возвращает кол-во мужчин
     *
     * @return int
     */
    public function getCountMen()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `sex` = 0")->queryScalar();
    }

    /**
     * Возвращает кол-во женщин
     *
     * @return int
     */
    public function getCountWomen()
    {
        return $this->_db->createCommand("SELECT COUNT(0) FROM `{{characters}}` WHERE `sex` = 1")->queryScalar();
    }

    /**
     * Возвращает кол-во камаэлей
     *
     * @return int
     */
    public function getCountRaceKamael()
    {
        // На Interlude нет камаэлей
        return 0;
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
        $command = $this->_db->createCommand();

        $command->where = 'pvpkills > 0';
        $command->order = 'pvpkills DESC';
        $command->limit = $limit;
        $command->offset = $offset;

        return $this->characters($command)->queryAll();
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
        $command = $this->_db->createCommand();

        $command->where = 'pkkills > 0';
        $command->order = 'pkkills DESC';
        $command->limit = $limit;
        $command->offset = $offset;

        return $this->characters($command)->queryAll();
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
        $command = $this->_db->createCommand();

        $command->order = 'exp DESC';
        $command->limit = $limit;
        $command->offset = $offset;

        return $this->characters($command)->queryAll();
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

        $command->select = $command->select . ',SUM(items.count) AS adena_count';
        $command->where = 'items.item_id = 57';
        $command->order = 'adena_count DESC';
        $command->limit = $limit;
        $command->offset = $offset;
        $command->group = $this->getField('characters.char_id');
        $command->leftJoin('items', 'items.owner_id = ' . $this->getField('characters.char_id'));

        return $command->queryAll();
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
        $command = $this->_db->createCommand();

        $command->where = 'online = 1';
        $command->order = 'level DESC';
        $command->limit = $limit;
        $command->offset = $offset;

        return $this->characters($command)->queryAll();
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

        return $this->clans($command)->queryAll();
    }

    /**
     * Возвращает список замков и инфу о владельцах
     *
     * @return array
     */
    public function getCastles()
    {
        $command = $this->_db->createCommand();

        $command->select = 'castle.id,castle.name,castle.taxPercent,castle.siegeDate,clan_data.clan_id,clan_data.clan_name,clan_data.clan_level,clan_data.reputation_score,clan_data.hasCastle,clan_data.ally_id,clan_data.ally_name,clan_data.leader_id,
            clan_data.crest_id,clan_data.crest_large_id,clan_data.ally_crest_id';
        $command->join = 'LEFT JOIN clan_data ON clan_data.hasCastle = castle.id';
        $command->from = 'castle';

        return $command->queryAll();
    }

    public function getSiege()
    {
        $command = $this->_db->createCommand();

        $command->select = 'siege_clans.castle_id,siege_clans.clan_id,siege_clans.type,siege_clans.castle_owner,clan_data.clan_name,clan_data.clan_level,clan_data.reputation_score,clan_data.hasCastle,clan_data.ally_id,clan_data.ally_name,clan_data.leader_id,
            clan_data.crest_id AS clan_crest,clan_data.crest_large_id AS clan_crest_large,clan_data.ally_crest_id AS ally_crest';
        $command->join = 'LEFT JOIN clan_data ON siege_clans.clan_id = clan_data.clan_id';
        $command->from = 'siege_clans';

        return $command->queryAll();
    }

    /**
     * Хроники сервера
     *
     * @return string
     */
    public function getChronicle()
    {
        return 'interlude';
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
     * @param array $itemsIds
     *
     * @return array
     */
    public function getItemsControl(array $itemsIds)
    {
        if (!$itemsIds) {
            return [];
        }

        $res = AllItems::model()->findAllByAttributes([
            'item_id' => $itemsIds,
        ]);

        $itemNames = [];

        foreach ($res as $row) {
            $itemNames[$row->getPrimaryKey()] = $row;
        }

        unset($res);

        /*
            SELECT
            Max(items.count) AS maxCountItems,Count(items.count) AS countItems,items.owner_id,items.object_id,items.item_id,items.count,items.enchant_level,items.loc,items.loc_data,characters.charId AS char_id,characters.account_name,characters.char_name,
            characters.`level`,characters.curHp,characters.maxCp,characters.curCp,characters.maxMp,characters.curMp,characters.maxHp,characters.x,characters.y,characters.z,characters.exp,characters.sp,characters.pvpkills,characters.pkkills,characters.clanid AS clan_id,
            characters.base_class,characters.title,characters.`online`,characters.onlinetime,clan_data.clan_name,clan_data.clan_level,clan_data.hasCastle,clan_data.hasFort,clan_data.crest_id AS clan_crest,clan_data.reputation_score
            FROM
            items
            LEFT JOIN characters ON items.owner_id = characters.charId
            LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id
            WHERE
            items.item_id IN (57, 4037, 5588, 10)
            GROUP BY
            items.owner_id,
            items.item_id
            ORDER BY
            items.item_id ASC
         */
        $res = $this->_db->createCommand()
            ->select("Max(items.count) AS maxCountItems,Count(items.count) AS countItems,items.owner_id,items.object_id,items.item_id,items.count,items.enchant_level,items.loc,items.loc_data,characters.charId AS char_id,characters.account_name,characters.char_name,
                characters.level,characters.curHp,characters.maxCp,characters.curCp,characters.maxMp,characters.curMp,characters.maxHp,characters.x,characters.y,characters.z,characters.exp,characters.sp,characters.pvpkills,characters.pkkills,characters.clanid AS clan_id,
                characters.base_class,characters.title,characters.online,characters.onlinetime,clan_data.clan_name,clan_data.clan_level,clan_data.hasCastle,clan_data.hasFort,clan_data.crest_id AS clan_crest,clan_data.reputation_score")
            ->leftJoin('characters', 'items.owner_id = characters.charId')
            ->leftJoin('clan_data', 'characters.clanid = clan_data.clan_id')
            ->where(['in', 'item_id', $itemsIds])
            ->group('items.owner_id, items.item_id')
            ->order('maxCountItems DESC')
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
