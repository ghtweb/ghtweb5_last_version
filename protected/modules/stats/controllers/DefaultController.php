<?php

class DefaultController extends FrontendBaseController
{
    /**
     * @var Gs[]
     */
    protected $_gs_list;

    /**
     * @var Gs
     */
    protected $_gs;

    /**
     * @var int
     */
    protected $_gs_id;

    /**
     * @var int
     */
    protected $_clan_id;

    /**
     * @var array
     */
    protected $_stats_types = [];


    public function init()
    {
        parent::init();

        $dependency = new CDbCacheDependency("SELECT MAX(UNIX_TIMESTAMP(updated_at)) FROM {{gs}} WHERE status = :status");
        $dependency->params = [
            'status' => ActiveRecord::STATUS_ON,
        ];
        $dependency->reuseDependentData = true;

        $gsList = Gs::model()->cache(3600 * 24, $dependency)->opened()->findAll();

        foreach ($gsList as $row) {
            $this->_gs_list[$row->getPrimaryKey()] = $row;
        }

        require_once Yii::getPathOfAlias('application.helpers') . DIRECTORY_SEPARATOR . 'lineage.php';
    }

    public function actionIndex($gs_id = null, $type = '', $clan_id = null)
    {
        $type = str_replace('-', '_', $type);

        $content = '';
        $stats_list = [];

        // Выбран сервер
        if ($gs_id !== null && isset($this->_gs_list[$gs_id])) {
            $this->_gs_id = $gs_id;
            $this->_clan_id = $clan_id;

            $this->_gs = $this->_gs_list[$gs_id];

            // Типы статистики
            $this->statsTypes();

            if ($type == '' && isset($this->_stats_types[0])) {
                $this->redirect(['/stats/default/index', 'gs_id' => $gs_id, 'type' => $this->_stats_types[0]]);
            }

            if ($type != '' && !in_array($type, $this->_stats_types)) {
                $content = 'Статистика недоступна.';
            } elseif ($type != '' && in_array($type, $this->_stats_types)) {
                if (method_exists($this, $type)) {
                    $cacheName = 'stats.' . $type . '.' . $this->_gs_id . ($this->_clan_id ? '.' . $this->_clan_id : '');

                    if (($content = cache()->get($cacheName)) === false) {
                        try {
                            $content = $this->$type();

                            if ($this->_gs->stats_cache_time > 0) {
                                cache()->set($cacheName, $content, $this->_gs->stats_cache_time * 60);
                            }
                        } catch (Exception $e) {
                            $content = $e->getMessage();
                        }
                    }
                } else {
                    $content = 'Метод для обработки статистики не найден.';
                }
            }
        }

        if (is_numeric($key = array_search('clan_info', $this->_stats_types))) {
            unset($this->_stats_types[$key]);
        }

        $this->render('//stats/index', [
            'gs_list' => $this->_gs_list,
            'gs_id' => $this->_gs_id,
            'content' => $content,
            'stats_list' => $stats_list,
            'type' => $type,
            'stats_types' => $this->_stats_types,
        ]);
    }

    private function statsTypes()
    {
        // Статистика отключена
        if ($this->_gs['stats_allow'] == 0) {
            return;
        }

        foreach ($this->_gs as $k => $v) {
            if (strpos($k, 'stats') !== false && $v == 1 && !in_array($k, ['stats_allow', 'stats_cache_time', 'stats_count_results'])) {
                $this->_stats_types[] = str_replace('stats_', '', $k);
            }
        }
    }


    private function total()
    {
        $ls = l2('ls', $this->_gs->login_id)->connect();

        // Кол-во аккаунтов
        $data['countAccounts'] = (int)$ls->getCountAccounts();


        $gs = l2('gs', $this->_gs_id)->connect();

        // Кол-во персонажей
        $data['countCharacters'] = (int)$gs->getCountCharacters();

        // В игре
        $data['countOnline'] = (int)$gs->getCountOnlineCharacters();

        // Fake online
        if ($this->_gs->fake_online > 0) {
            $data['countOnline'] += Lineage::fakeOnline($data['countOnline'], $this->_gs->fake_online);
        }

        // Кол-во кланов
        $data['countClans'] = (int)$gs->getCountClans();

        // Men
        $data['countMen'] = $gs->getCountMen();

        // Female
        $data['countFemale'] = $gs->getCountWomen();


        // Races
        $data['races'] = [
            'human' => $gs->getCountRaceHuman(),
            'elf' => $gs->getCountRaceElf(),
            'dark_elf' => $gs->getCountRaceDarkElf(),
            'ork' => $gs->getCountRaceOrk(),
            'dwarf' => $gs->getCountRaceDwarf(),
        ];

        $data['races_percentage'] = [
            'human' => ($data['races']['human'] > 0 ? round($data['races']['human'] / $data['countCharacters'] * 100) : 0),
            'elf' => ($data['races']['elf'] > 0 ? round($data['races']['elf'] / $data['countCharacters'] * 100) : 0),
            'dark_elf' => ($data['races']['dark_elf'] > 0 ? round($data['races']['dark_elf'] / $data['countCharacters'] * 100) : 0),
            'ork' => ($data['races']['ork'] > 0 ? round($data['races']['ork'] / $data['countCharacters'] * 100) : 0),
            'dwarf' => ($data['races']['dwarf'] > 0 ? round($data['races']['dwarf'] / $data['countCharacters'] * 100) : 0),
        ];

        if ($gs->getChronicle() != 'interlude') {
            $data['races']['kamael'] = $gs->getCountRaceKamael();
            $data['races_percentage']['kamael'] = ($data['races']['kamael'] > 0 ? round($data['races']['kamael'] / $data['countCharacters'] * 100) : 0);
        }

        return $this->renderPartial('//stats/' . __FUNCTION__, $data, true);
    }

    private function pvp()
    {
        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getTopPvp($this->_gs->stats_count_results),
        ], true);
    }

    private function pk()
    {
        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getTopPk($this->_gs->stats_count_results),
        ], true);
    }

    private function clans()
    {
        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getTopClans($this->_gs->stats_count_results),
        ], true);
    }

    private function rich()
    {
        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getTopRich($this->_gs->stats_count_results),
        ], true);
    }

    private function castles()
    {
        $gs = l2('gs', $this->_gs_id)->connect();

        $castles = $gs->getCastles();
        $content = [];

        foreach ($castles as $castle) {
            $content[$castle['id']] = [
                'name' => $castle['name'],
                'castle_id' => $castle['id'],
                'tax_percent' => $castle['taxPercent'],
                'sieg_date' => $castle['siegeDate'],
                'owner' => $castle['clan_name'],
                'owner_id' => $castle['clan_id'],
                'forwards' => [],
                'defenders' => [],
            ];
        }

        unset($castles);

        $fd = $gs->getSiege();

        if ($fd) {
            foreach ($fd as $row) {
                if (isset($content[$row['castle_id']])) {
                    if ($row['type'] == 1) {
                        $content[$row['castle_id']]['forwards'][] = $row;
                    } elseif ($row['type'] == 2) {
                        $content[$row['castle_id']]['defenders'][] = $row;
                    }
                }
            }
        }

        unset($fd);

        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => $content,
        ], true);
    }

    public function clan_info()
    {
        $l2 = l2('gs', $this->_gs_id)->connect();

        $command = $l2->getDb()->createCommand();
        $command->where('clan_data.clan_id = :clan_id', [':clan_id' => $this->_clan_id]);

        $clanInfo = $l2->clans($command)->queryRow();

        if ($clanInfo) {
            $command = $l2->getDb()->createCommand();
            $clanIdFieldName = $l2->getField('clan_data.clan_id');

            $command->where($clanIdFieldName . ' = :clan_id', [':clan_id' => $this->_clan_id]);

            // Если есть поле access_level, то делаю выборку только игроков
            if ($alf = $l2->getField('characters.access_level')) {
                $command->andWhere($alf . ' = 0');
            }

            $clanCharacters = $l2->characters($command)->queryAll();
        }

        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'clan_info' => $clanInfo,
            'clanCharacters' => $clanCharacters,
        ], true);
    }

    private function online()
    {
        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getOnline($this->_gs->stats_count_results),
        ], true);
    }

    private function top()
    {
        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getTop($this->_gs->stats_count_results),
        ], true);
    }

    private function items()
    {
        $items = [];

        if ($this->_gs->stats_items_list != '') {
            $items = explode(',', $this->_gs->stats_items_list);
            $items = array_map('intval', $items);
        }

        return $this->renderPartial('//stats/' . __FUNCTION__, [
            'content' => l2('gs', $this->_gs_id)->connect()->getItemsControl($items),
        ], true);
    }
}
