<?php defined('SYSPATH') or die('No direct script access.');

class Dao_PageOptions extends Dao_MySQL_Base
{
    protected $cache_key = 'Dao_PageOptions';

    protected $table = 'page_options';
}
