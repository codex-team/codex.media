<?php defined('SYSPATH') or die('No direct script access.');

class Dao_User extends Dao_MySQL_Base {

    protected $cache_key = 'Dao_User';

    protected $table = 'users';

}
