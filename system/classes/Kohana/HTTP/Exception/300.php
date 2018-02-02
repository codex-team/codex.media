<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_300 extends HTTP_Exception_Redirect
{

    /**
     * @var int HTTP 300 Multiple Choices
     */
    protected $_code = 300;
}
