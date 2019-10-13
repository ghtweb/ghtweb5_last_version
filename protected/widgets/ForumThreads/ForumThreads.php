<?php

class ForumThreads extends CWidget
{
    /**
     * @var CDbConnection
     */
    private $db;


    public function init()
    {
        $data = [
            'content' => [],
            'error' => 'Модуль отключен.',
        ];


        if (config('forum_threads.allow') == 1) {
            $data = cache()->get(CacheNames::FORUM_THREADS);

            if ($data === false) {
                $data = [];

                try {
                    // Подключаюсь к БД
                    $this->db = Yii::createComponent([
                        'class' => 'CDbConnection',
                        'connectionString' => 'mysql:host=' . config('forum_threads.db_host') . ';port=' . config('forum_threads.db_port') . ';dbname=' . config('forum_threads.db_name'),
                        'enableProfiling' => YII_DEBUG,
                        'enableParamLogging' => true,
                        'username' => config('forum_threads.db_user'),
                        'password' => config('forum_threads.db_pass'),
                        'charset' => 'utf8',
                        'emulatePrepare' => true,
                        'tablePrefix' => config('forum_threads.prefix'),
                    ]);

                    app()->setComponent('ForumThreadsDb', $this->db);

                    $forumType = config('forum_threads.type');

                    if (method_exists($this, $forumType)) {
                        $data['content'] = $this->$forumType();

                        foreach ($data['content'] as $k => $v) {
                            $data['content'][$k]['user_link'] = $this->getUserLink($v['starter_id'], $v['starter_name']);
                            $data['content'][$k]['theme_link'] = $this->getForumLink($v['id_topic'], $v['title'], $v['id_forum']);
                            $data['content'][$k]['start_date'] = $this->getStartDate($v['start_date']);
                        }

                        if (config('forum_threads.cache')) {
                            cache()->set(CacheNames::FORUM_THREADS, $data, config('forum_threads.cache') * 60);
                        }
                    } else {
                        $data['error'] = 'Метод для обработки форума: <b>' . $forumType . '</b> не найден.';
                    }
                } catch (Exception $e) {
                    $data['error'] = $e->getMessage();
                }
            }
        }

        app()->controller->renderPartial('//forum-threads', $data);
    }

    /**
     * Ссылка на форум
     *
     * @param int $id_topic
     * @param string $title
     * @param int $id_forum
     *
     * @return string
     */
    private function getForumLink($id_topic, $title, $id_forum)
    {
        $link = rtrim(config('forum_threads.link'), '/') . '/';

        switch (config('forum_threads.type')) {
            case 'ipb':
                $link .= 'index.php?/topic/' . $id_topic . '-' . $title . '/';
                break;
            case 'smf':
                $link .= 'index.php?topic=' . $id_topic . '.0';
                break;
            case 'phpbb':
                $link .= 'viewtopic.php?f=' . $id_forum . '&t=' . $id_topic;
                break;
            case 'vanilla':
                $link .= 'discussion/' . $id_forum . '/' . $title;
                break;
            case 'vBulletin':
                $link .= 'showthread.php?' . $id_forum . '-' . $title;
                break;
            case 'xenForo':
                $link .= 'index.php?threads/' . $id_topic;
                break;
        }

        return $link;
    }

    /**
     * Ссылка на автора темы
     *
     * @param int $user_id
     * @param string $user_name
     *
     * @return string
     */
    private function getUserLink($user_id, $user_name)
    {
        $link = rtrim(config('forum_threads.link'), '/') . '/';

        switch (config('forum_threads.type')) {
            case 'ipb':
                $link .= 'index.php?/user/' . $user_id . '-' . $user_name . '/';
                break;
            case 'smf':
                $link .= 'index.php?action=profile;u=' . $user_id;
                break;
            case 'phpbb':
                $link .= 'memberlist.php?mode=viewprofile&u=' . $user_id;
                break;
            case 'vanilla':
                $link .= 'profile/' . $user_id . '/' . $user_name;
                break;
            case 'vBulletin':
                $link .= 'member.php?' . $user_id . '-' . $user_name;
                break;
            case 'xenForo':
                $link .= 'index.php?members/' . $user_id;
                break;
        }

        return $link;
    }

    /**
     * Форматирует дату
     *
     * @param int|string $time
     *
     * @return string
     */
    private function getStartDate($time)
    {
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }

        return date(config('forum_threads.date_format'), $time);
    }

    /**
     * Запрос к форуму xenForo
     *
     * @return array
     */
    private function xenForo()
    {
        $limit = (int)config('forum_threads.limit');

        $command = $this->db->createCommand()
            ->select('thread_id AS id_topic,title,node_id AS id_forum,post_date AS start_date,user_id AS starter_id,username AS starter_name')
            ->where('discussion_state = :discussion_state', [':discussion_state' => 'visible'])
            ->from('{{thread}}')
            ->order('last_post_date DESC')
            ->limit($limit);

        if (config('forum_threads.id_deny') != '') {
            $ids = explode(',', config('forum_threads.id_deny'));
            $ids = $this->filterIds($ids);

            $command->andWhere(['not in', 'node_id', $ids]);
        }

        return $command->queryAll();
    }

    /**
     * Запросы к форуму Ipb
     *
     * @return array
     */
    private function ipb()
    {
        $limit = (int)config('forum_threads.limit');

        $command = $this->db->createCommand()
            ->select('tid AS id_topic,start_date,starter_name,starter_id,forum_id AS id_forum,title')
            ->where('tdelete_time = 0 AND approved = 1')
            ->from('{{topics}}')
            ->order('start_date DESC')
            ->limit($limit);

        if (config('forum_threads.id_deny') != '') {
            $ids = explode(',', config('forum_threads.id_deny'));
            $ids = $this->filterIds($ids);

            $command->where(['not in', 'forum_id', $ids]);
        }

        return $command->queryAll();
    }

    /**
     * Запросы к форуму Smf
     *
     * @return array
     */
    private function smf()
    {
        $limit = (int)config('forum_threads.limit');

        $command = $this->db->createCommand()
            ->select('subject AS title,poster_time AS start_date,poster_name AS starter_name,id_member AS starter_id,id_board AS id_forum,id_topic')
            ->from('{{messages}}')
            ->group('id_topic')
            ->order('start_date DESC')
            ->limit($limit);

        if (config('forum_threads.id_deny') != '') {
            $ids = explode(',', config('forum_threads.id_deny'));
            $ids = $this->filterIds($ids);

            $command->where(['not in', 'id_board', $ids]);
        }

        return $command->queryAll();
    }

    /**
     * Запросы к форуму Phpbb
     *
     * @return array
     */
    private function phpbb()
    {
        $limit = (int)config('forum_threads.limit');

        $command = $this->db->createCommand()
            ->select('topic_id AS id_topic,topic_time AS start_date,topic_first_poster_name AS starter_name,topic_poster AS starter_id,forum_id AS id_forum,topic_title AS title')
            ->from('{{topics}}')
            ->order('start_date DESC')
            ->limit($limit);

        if (config('forum_threads.id_deny') != '') {
            $ids = explode(',', config('forum_threads.id_deny'));
            $ids = $this->filterIds($ids);

            $command->where(['not in', 'forum_id', $ids]);
        }

        return $command->queryAll();
    }

    /**
     * Запросы к форуму Vanilla
     *
     * @return array
     */
    private function vanilla()
    {
        $limit = (int)config('forum_threads.limit');

        $command = $this->db->createCommand()
            ->select('gdn_discussion.InsertUserID AS starter_id,gdn_discussion.DiscussionID AS id_forum,gdn_discussion.`Name` AS title,UNIX_TIMESTAMP(gdn_discussion.DateInserted) AS start_date,gdn_user.`Name` AS starter_name,gdn_discussion.CategoryID AS id_topi')
            ->leftJoin('gdn_user', 'gdn_discussion.InsertUserID = gdn_user.UserID')
            ->from('{{gdn_discussion}}')
            ->order('gdn_discussion.DateInserted DESC')
            ->limit($limit);

        if (config('forum_threads.id_deny') != '') {
            $ids = explode(',', config('forum_threads.id_deny'));
            $ids = $this->filterIds($ids);

            $command->where(['not in', 'DiscussionID', $ids]);
        }

        return $command->queryAll();
    }

    /**
     * Запросы к форуму vBulletin
     *
     * @return array
     */
    private function vBulletin()
    {
        $limit = (int)config('forum_threads.limit');

        $command = $this->db->createCommand()
            ->select('forumid as id_topic, dateline AS start_date, postusername AS starter_name, lastposterid AS starter_id, threadid AS id_forum, title')
            ->from('{{thread}}')
            ->order('start_date DESC')
            ->limit($limit);

        if (config('forum_threads.id_deny') != '') {
            $ids = explode(',', config('forum_threads.id_deny'));
            $ids = $this->filterIds($ids);

            $command->where(['not in', 'forumid', $ids]);
        }

        return $command->queryAll();
    }

    private function filterIds($ids)
    {
        return array_map('trim', $ids);
    }
}
 