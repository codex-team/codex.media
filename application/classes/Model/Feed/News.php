<?php

class Model_Feed_News extends Model_Feed_Abstract {

    protected $timeline_key = 'news';

    /**
     * Получаем массив моделей новостей.
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

                $models_list[] = new Model_Page($id);

            }

            return $models_list;
        }

        return false;
    }
}
