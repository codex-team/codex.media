<?php defined('SYSPATH') or die('No direct script access.');

class Dao_Comments extends Dao_MySQL_Base
{
    protected $cache_key = 'Dao_Comments';

    protected $table = 'comments';
}
