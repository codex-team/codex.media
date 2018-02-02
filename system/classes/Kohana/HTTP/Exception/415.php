<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_415 extends HTTP_Exception
{

    /**
     * @var int HTTP 415 Unsupported Media Type
     */
    protected $_code = 415;
}
