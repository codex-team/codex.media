<?php defined('SYSPATH') or die('No Direct Script Access');
/**
 * Содержит функции для работы с redis и
 * статистикой просмотров.
 *
 * @author Ivan Zhuravlev
 */
class Model_Stats extends Model
{
    const PAGE = 'page';
    private $redis;


    public function __construct()
    {
        $this->redis = Controller_Base_preDispatch::_redis();
    }

    /*
    * Если статистики с такими параметрами нет, то
    * создает ее.
    * Если есть, то инкрементирует (увеличивает на 1).
    */
    public function hit($type, $id, $time = 0)
    {
        if (!$this->redis) {
            return;
        }

        $key = self::getKey($type, $id, $time);

        if (!$this->redis->get($key)) {
            $this->redis->set($key, 0);
        }

        return $this->redis->incr($key);
    }

    /*
    * Форматирует ключ для статистики.
    * Принимает на вход тип статистики, цель (то, к чему она применяется) и дату.
    * Возвращает ключ.
    */
    public function getKey($type, $id, $time)
    {
        $key = 'stats:' . $type . ':target:' . $id . ':time:' . $time;

        return $key;
    }

    public function get($type, $id, $time = 0)
    {
        if (!$this->redis) {
            return;
        }

        $key = self::getKey($type, $id, $time);

        $views = $this->redis->get($key);

        return $views;
    }
}
