<?php

class Rss
{
    /**
     * Откуда парсить
     * @var string
     */
    private $_url;

    /**
     * Формат даты
     * @var string
     */
    private $_date_format;

    /**
     * Время кэширования (в минутах)
     * @var int
     */
    private $_cache_time;


    public function __construct($url, $date_format = 'Y-m-d H:i:s', $cache_time = 15)
    {
        $this->_url = $url;
        $this->_date_format = $date_format;
        $this->_cache_time = $cache_time;
    }

    public function parse()
    {
        if (($data = cache()->get(CacheNames::RSS)) === false) {
            $xml = @simplexml_load_file($this->_url);

            if (!isset($xml->channel->item)) {
                throw new Exception('Не удалось подключиться к RSS, URL: ' . $this->_url);
            }

            $data = [];

            foreach ($xml->channel->item as $item) {
                $item = (array)$item;

                $data[] = [
                    'title' => $this->getTitle($item),
                    'description' => $this->getDescription($item),
                    'link' => $this->getLink($item),
                    'date' => $this->getDate($item),
                ];
            }

            if ($this->_cache_time > 0) {
                cache()->set(CacheNames::RSS, $data, $this->_cache_time * 60);
            }
        }

        return $data;
    }

    /**
     * Возвращает название
     *
     * @param array $item
     *
     * @return string
     */
    private function getTitle($item)
    {
        return isset($item['title']) ? $item['title'] : '';
    }

    /**
     * Возвращает описание
     *
     * @param array $item
     *
     * @return string
     */
    private function getDescription($item)
    {
        return isset($item['description']) ? str_replace(['<![CDATA[', ']]>'], '', $item['description']) : '';
    }

    /**
     * Возвращает ссылку
     *
     * @param array $item
     *
     * @return string
     */
    private function getLink($item)
    {
        return isset($item['link']) ? $item['link'] : '';
    }

    /**
     * возвращает дату
     *
     * @param array $item
     *
     * @return string
     */
    private function getDate($item)
    {
        return isset($item['pubDate']) ? date($this->_date_format, strtotime($item['pubDate'])) : '';
    }
}
