<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_408 extends HTTP_Exception
{

    /**
     * @var int HTTP 408 Request Timeout
     */
    protected $_code = 408;
}
