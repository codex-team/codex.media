<?php defined('SYSPATH') or die('No direct script access.');

class Dao_Users extends Dao_MySQL_Base {

    protected $cache_key = 'Dao_Users';

    protected $table = 'users';

}