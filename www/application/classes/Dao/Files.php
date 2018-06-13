<?php defined('SYSPATH') or die('No direct script access.');

class Dao_Files extends Dao_MySQL_Base
{
    protected $cache_key = 'Dao_Files';

    protected $table = 'files';
}
