<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_303 extends HTTP_Exception_Redirect
{

    /**
     * @var int HTTP 303 See Other
     */
    protected $_code = 303;
}
