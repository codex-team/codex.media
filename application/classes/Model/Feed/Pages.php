<?php

class Model_Feed_Pages extends Model_Feed_Abstract {

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

                $page = new Model_Page($id);
                $page->blocks = $page->getBlocks(true); // escapeHTML = true
                $page->description = $page->getDescription();

                $models_list[] = $page;
            }

            return $models_list;
        }

        return false;
    }
}
