<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_413 extends HTTP_Exception
{

    /**
     * @var int HTTP 413 Request Entity Too Large
     */
    protected $_code = 413;
}
