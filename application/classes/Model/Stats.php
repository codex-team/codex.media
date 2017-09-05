<?php defined('SYSPATH') or die('No Direct Script Access');

/**
 * Stats module stores page views in Redis.
 */
class Model_Stats extends Model {

    /**
     * Const with type
     */
    const PAGE = 'page';

    /**
     * Redis instance
     */
    private $redis;

    /**
     * Number of seconds in one rank to collect hits
     *
     * You can set any amount of seconds or use Kohana Date class.
     *
     * Date::YEAR   = 31556926;
   	 * Date::MONTH  = 2629744;
   	 * Date::WEEK   = 604800;
   	 * Date::DAY    = 86400;
   	 * Date::HOUR   = 3600;
   	 * Date::MINUTE = 60;
     */
    private $sensetivity = Date::DAY;

    /**
     * Item's params
     */
    private $type;
    private $id;
    private $key;

    /**
     * Stats for items
     *
     * @param $type     Pass a type name for this item
     * @param $id       This item uniq identifier
     */
    public function __construct($type, $id)
    {
        $this->redis = Controller_Base_preDispatch::_redis();

        if (!$this->redis) {
            return;
        }

        $this->type = $type;
        $this->id = $id;
        $this->key = $this->generateKey();
    }

    /**
     * Generate key for sorted set
     *
     * @return $key     Key for this item set
     */
    private function generateKey()
    {
        $key = 'stats:' . $this->type . ':' . $this->id;

        return $key;
    }

    /**
     * Return group name (sorted set member) for this event by time
     *
     * @return $member    Group for this event by current sensetivity
     */
    private function generateGroup()
    {
        $time = strtotime("now");

        $member = floor($time / $this->sensetivity) * $this->sensetivity;

        return $member;
    }

    /**
     * Increment item's stats
     */
    public function hit()
    {
        $group = $this->generateGroup();

        $updated = $this->redis->zIncrBy($this->key, 1, $group);

        return $updated;
    }

    /**
     * Return a sum of hits for target interval
     *
     * @param $start    (optional) timestamp for the start of interval
     * @param $end      (optional) timestamp for the end of interval
     *
     * @return $sum     Sum of hits for target interval
     */
    public function get($start = 0, $end = false)
    {
        if ($end === false) {
            $end = strtotime("now");
        }

        $items = $this->redis->zRevRange($this->key, 0, -1);

        $sum = 0;

        foreach ($items as $item) {
            if (($start <= $item) && ($item <= $end)) {
                $value = $this->redis->zScore($this->key, $item);
                $sum += (int) $value;
            }
        };

        return $sum;
    }

    /**
     * Used to get stats for last perion in seconds
     *
     * You can set any amount of seconds or use Kohana Date class.
     *
     * Date::YEAR   = 31556926;
   	 * Date::MONTH  = 2629744;
   	 * Date::WEEK   = 604800;
   	 * Date::DAY    = 86400;
   	 * Date::HOUR   = 3600;
   	 * Date::MINUTE = 60;
     *
     * @param $interval     For which last number of seconds you need to get stats
     * @return              Sum of hits for target period
     */
    public function getLast($interval)
    {
      $intervalStart = strtotime("now") - $interval;

      return $this->get($intervalStart);
    }
}
