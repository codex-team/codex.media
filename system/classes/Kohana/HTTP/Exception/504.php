<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_504 extends HTTP_Exception
{

    /**
     * @var int HTTP 504 Gateway Timeout
     */
    protected $_code = 504;
}
