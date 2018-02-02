<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_412 extends HTTP_Exception
{

    /**
     * @var int HTTP 412 Precondition Failed
     */
    protected $_code = 412;
}
