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
     * You can set any anount of seconds or use Kohana Date class.
     *
     * Date::YEAR   = 31556926;
   	 * Date::MONTH  = 2629744;
   	 * Date::WEEK   = 604800;
   	 * Date::DAY    = 86400;
   	 * Date::HOUR   = 3600;
   	 * Date::MINUTE = 60;
     */
    private $sensetivity = Date::HOUR;

    /**
     * Item's params
     */
    private $type;
    private $id;
    private $key;

    /**
     * TODO decription
     *
     * @param $type
     * @param $id
     */
    public function __construct($type, $id)
    {
        $this->redis = Controller_Base_preDispatch::_redis();

        if (!$this->redis) {
            return;
        }

        $this->type = $type;
        # TODO check for type existing of throw an error

        $this->id = $id;

        $this->key = $this->generateKey();

        # TODO create a set
    }

    /**
     * Generates key for set
     *
     * @return $key     Key for this item set
     */
    public function generateKey()
    {
        $key = 'stats:' . $this->type . ':' . $this->id;

        return $key;
    }

    /**
     * TODO Incr by 1
     *
     * @param $time
     */
    public function hit($time = strtotime("now"))
    {
        # TODO get a set

        # TODO one hit to set by timestamp

        # TODO return result of hitting (boolean)

        // if (!$this->redis->get($this->key)) {
        //     $this->redis->set($this->key, 0);
        // }
        //
        // return $this->redis->incr($this->key);
    }



    /**
     * Return a sum of hits for target interval
     *
     ??????* @param $interval (optional)
     * @param $start    (optional) timestamp for the start of interval
     * @param $end      (optional) timestamp for the end of interval
     *
     * @return $sum     Sum of hits for target interval
     */
    public function get($interval = null, $start = 0, $end = strtotime("now"))
    {
        #  TODO get a set

        #

        // $views = $this->redis->get($key);
        //
        // return $views;
    }
}
