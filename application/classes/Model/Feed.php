<?php

class Model_Feed extends Model {

    private $redis;
    private $type;
    private $redis_key;

    public function __construct($type = '')
    {
        $this->redis = Controller_Base_preDispatch::_redis();
        $this->type      = $type;
        $this->redis_key = 'feed';
    }

    /**
     * Получаем значение, которое будет записано в Redis ([article|course]:<id>)
     *
     * @param $id
     * @return string
     */

    public function composeValueIdentity($id)
    {
        return $this->type . ':' . $id;
    }

    /**
     * Меняем порядок элементов в фиде
     *
     * @param $item_id - id элемента, который переставляем
     * @param $item_below_value - value элемента,
     * после которого в sorted set вставляем $item (перед которым $item будет выводиться)
     *
     * @return void|bool
     */
    public function putAbove($item_id, $item_below_value)
    {
        $item_value = $this->composeValueIdentity($item_id);

        if ($this->redis->zRank($this->redis_key, $item_value) === false) {
            return false;
        }

        if($this->redis->zRank($this->redis_key, $item_below_value) === false) {
            return false;
        }

        $interval = $this->redis->zScore($this->redis_key, $item_below_value) - $this->redis->zScore($this->redis_key, $item_value);

        $this->redis->zIncrBy($this->redis_key, $interval + 1, $item_value);
    }

    /**
     * Добавляем элемент в фид
     *
     * @param $item_id
     * @param $item_score string time
     * @return bool|void
     */
    public function add($item_id, $item_score = "now")
    {
        $value = $this->composeValueIdentity($item_id);

        if ($this->redis->zRank($this->redis_key, $value) !== false) {
            return false;
        }

        $this->redis->zAdd($this->redis_key, strtotime($item_score), $value);

    }

    /**
     * Удаляем элемент из фида
     *
     * @param $item_id
     */
    public function remove($item_id)
    {
        $value = $this->composeValueIdentity($item_id);

        $this->redis->zRem($this->redis_key, $value);
    }


    /**
     * Добавляем все опубликованные статьи в фид (для инициализации фида в Redis)
     */
    public function addActiveArticles()
    {

        $pages = Model_Page::getPages();

        $this->clear();

        $this->type = Model_Article::FEED_TYPE;

        foreach ($articles as $article) {

            $this->add($article->id, $article->dt_create);
        }

    }


    /**
     * Получаем первые id элементов в фиде в количестве $numberOfItems.
     * Если п$numberOfItems не указано, то получаем все элементы в фиде.
     *
     * @param int $numberOfItems
     *
     * @return bool|array - массив моделей статей и курсов
     * @throws Exception
     */
    public function get($numberOfItems = 0) {

        $numberOfItems = $this->redis->zCard($this->redis_key) > $numberOfItems ? $numberOfItems : 0;

        if ($numberOfItems) {

            $list = $this->redis->zRevRange($this->redis_key, 0, $numberOfItems - 1);

        } else {

            $list = $this->redis->zRevRange($this->redis_key, 0, -1);
        }

        if (is_array($list)) {

            $models_list = array();

            foreach ($list as $item) {

                $debug_text = 'Feed item';
                Log::instance()->add(Log::DEBUG, ':debug_text - :item', array(
                    ':debug_text' => $debug_text,
                    ':item'       => $item
                ));

                list($type, $id) = explode(':', $item);

                switch ($type) {

                    case 'article':
                        $models_list[] = Model_Article::get($id);
                        break;

                    case 'course':
                        $models_list[] = Model_Courses::get($id);
                        break;

                    default:
                        $error_text = 'Invalid feed type';
                        Log::instance()->add(Log::ERROR, ':error_text - :type', array(
                            ':error_text' => $error_text,
                            ':type'       => $type
                        ));
                        Log::instance()->write();
                        throw new Exception($error_text);
                }
            }
            return $models_list;
        }

        return false;
    }

    /**
     * Очистить фид
     */
    public function clear() {

        $this->redis->del($this->redis_key);
    }
}
