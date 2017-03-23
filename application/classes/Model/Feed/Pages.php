<?php

class Model_Feed_Pages extends Model_Feed_Abstract {

    /**
     * Key prefix for redis to identify Codex.Org keys
     */
    const KEY_PREFIX     = 'codex.org:';

    const TYPE_ALL       = 'all';
    const TYPE_TEACHERS  = 'teachers';
    const TYPE_NEWS      = 'news';
    const TYPE_MENU      = 'menu';

    public function __construct($type = self::TYPE_ALL,$prefix = '')
    {

        $this->timeline_key = self::KEY_PREFIX . $type;

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
     * @param int $numberOfItems - количество элементов, которое хотим получить. Если не указан - получаем все
     *
     * @return bool|array
     * @throws Exception
     */
    public function get($numberOfItems = 0, $offset = 0)
    {
        $items = parent::get($numberOfItems, $offset);

        if (is_array($items)) {

            $models_list = array();

            foreach ($items as $id) {

                $page = new Model_Page($id, true); //sets $escapeHTML param true to escape HTML entities

                $models_list[] = $page;
            }

            return $models_list;
        }

        return false;
    }
}
