<?php defined('SYSPATH') or die('No direct script access.');

class Model_preDispatch extends Model
{
    public $redis;

    public function __construct()
    {
        $this->redis = Controller_Base_preDispatch::_redis();
    }
}
