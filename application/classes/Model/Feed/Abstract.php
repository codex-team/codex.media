<?php

class Model_Feed_Abstract extends Model {

    protected $redis;
    protected $prefix;

    protected $timeline_key = null;

    /**
     * Timeline constructor.
     * При создании экземпляра класса можно передать префикс для хранения в фиде различных сущностей.
     * По умолчанию префикс отсутствует.
     *
     * @param string $prefix
     */
    public function __construct($prefix = '')
    {
        $this->redis = Controller_Base_preDispatch::_redis();

        $this->prefix = $prefix;
    }

    /**
     * Получаем идентефикатор, с учетом типа (префикса) элемента
     *
     * @param $id
     * @return string
     */
    public function composeValueIdentity($id)
    {
        if (!$this->prefix) {
            return $id;
        }

        return $this->prefix.':'.$id;
    }

    /**
     * Разбиваем идентефикатор на префих и id
     *
     * @param string $identity
     *
     * @return array - массив из двух элементов (prefix, id)
     */
    public function decomposeValueIdentity($identity)
    {
        return explode(':', $identity);
    }

    /**
     * Меняем порядок элементов в фиде
     *
     * @param $item_id - id элемента, который переставляем
     * @param $item_below_value - value элемента,
     * после которого в sorted set вставляем $item (перед которым $item будет выводиться)
     *
     * @return string|bool - false при ошибке или новое значение score, представленное в строке
     */
    public function putAbove($item_id, $item_below_value)
    {
        $item_value = $this->composeValueIdentity($item_id);

        if ($this->redis->zRank($this->timeline_key, $item_value) === false) {
            return false;
        }

        if($this->redis->zRank($this->timeline_key, $item_below_value) === false) {
            return false;
        }

        $interval = $this->redis->zScore($this->timeline_key, $item_below_value) - $this->redis->zScore($this->timeline_key, $item_value);

        return $this->redis->zIncrBy($this->timeline_key, $interval + 1, $item_value);
    }

    /**
     * Добавляем элемент в фид.
     * Идентефикатор сформируется с учетом префикса текущего экземпляра класса
     *
     * @param int $item_id
     * @param int $item_score
     * @return bool|int
     */
    public function add($item_id, $item_score)
    {
        $value = $this->composeValueIdentity($item_id);

        if ($this->redis->zRank($this->timeline_key, $value) !== false) {
            return false;
        }

        return $this->redis->zAdd($this->timeline_key, $item_score, $value);
    }

    /**
     * Удаляем элемент из фида
     *
     * @param $item_id
     */
    public function remove($item_id)
    {
        $value = $this->composeValueIdentity($item_id);

        $this->redis->zRem($this->timeline_key, $value);
    }


    /**
     * Получаем индентефикаторы первых $numberOfItems элементов в фиде,
     * сделав отступ в $offset элементов (для пагинации).
     * Если $numberOfItems не указано, то получаем весь фид.
     *
     * @param int $numberOfItems
     * @param int $offset
     * @return array - массив идентефикаторов элементов
     */
    public function get($numberOfItems = 0, $offset = 0)
    {
        $numberOfItems = $this->redis->zCard($this->timeline_key) > $numberOfItems + $offset ? $numberOfItems + $offset : 0;

        $items = $this->redis->zRevRange($this->timeline_key, $offset, $numberOfItems - 1);

        return $items;
    }

    /**
     * Метод для первой инициализации фида.
     *
     * @param {array} $items -  ассоциативный массив идентефикаторов элементов с датой создания(поля 'id' и 'dt_create')
     */
    public function init($items)
    {
        foreach ($items as $item) {

            $this->add($item['id'], $item['dt_create']);
        }
    }

    /**
     * Очистить фид
     */
    public function clear()
    {
        $this->redis->del($this->timeline_key);
    }
}
