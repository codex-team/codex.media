<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_302 extends HTTP_Exception_Redirect
{

    /**
     * @var int HTTP 302 Found
     */
    protected $_code = 302;
}
