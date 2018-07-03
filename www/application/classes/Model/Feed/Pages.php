<?php

class Model_Feed_Pages extends Model_Feed_Abstract
{
    const FEED_PREFIX = 'feed:';

    const ALL = '1';
    const TEACHERS = '2';
    const MAIN = '3';
    const MENU = '4';
    const EVENTS = '5';

    public function __construct($type = self::ALL, $prefix = '')
    {
        $redis_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:');

        $this->timeline_key = $redis_prefix . self::FEED_PREFIX . $type;

        parent::__construct($prefix);
    }

    /**
     * Добавляем элемент в фид, передав в score дату создания
     *
     * @param int $item_id
     * @param int $item_dt_create
     *
     * @return bool|int
     */
    public function add($item_id, $item_dt_create = '')
    {
        return parent::add($item_id, $item_dt_create);
    }

    /**
     * Получаем массив моделей страниц.
     *
     * @param int   $numberOfItems - количество элементов, которое хотим получить. Если не указан - получаем все
     * @param mixed $offset
     *
     * @throws Exception
     *
     * @return bool|array
     */
    public function get($numberOfItems = 0, $offset = 0)
    {
        $items = parent::get($numberOfItems, $offset);

        if (is_array($items)) {
            $models_list = [];

            foreach ($items as $id) {
                $page = new Model_Page($id, true); //sets $escapeHTML param true to escape HTML entities

                $page->isPinned = $this->isPinned($id);
                $page->children = $page->getChildrenPages();

                /** if page with $id doen't exist then ignore it */
                if (!$page->id) {
                    continue;
                }

                $models_list[] = $page;
            }

            return $models_list;
        }

        return false;
    }

    /**
     * Return feed ids
     *
     * @param int limit
     * @param int offset
     * @return array
     */
    public function ids($limit = 0, $offset = 0)
    {
        return parent::get($limit, $offset);
    }
}
