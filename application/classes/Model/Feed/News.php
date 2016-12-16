<?php

class Model_Feed_News extends Model_Feed_Abstract {

    protected $timeline_key = 'news';

    /**
     * Добавляем элемент в фид, передав в score дату создания
     *
     * @param int $item_id
     * @param int $item_dt_create
     *
     * @return bool|int
     */
    public function add($item_id, $item_dt_create) {
        return parent::add($item_id, strtotime($item_dt_create));
    }

    /**
     * Получаем массив моделей статей и курсов.
     *
     * @param int $numberOfItems - количество элементов, которое хотим получить. Если не указан - получаем все
     *
     * @return bool|array
     * @throws Exception
     */
    public function get($numberOfItems = 0) {

        $items = parent::get($numberOfItems);

        if (is_array($items)) {

            $models_list = array();

            foreach ($items as $id) {

                $models_list[] = new Model_Page($id);

            }
            return $models_list;
        }
        return false;
    }
}
