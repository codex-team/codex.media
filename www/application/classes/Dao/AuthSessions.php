<?php defined('SYSPATH') or die('No direct script access.');

class Dao_AuthSessions extends Dao_MySQL_Base
{
    protected $cache_key = 'Dao_Auth_Sessions';

    protected $table = 'users_sessions';
}
