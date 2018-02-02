<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_304 extends HTTP_Exception_Expected
{

    /**
     * @var int HTTP 304 Not Modified
     */
    protected $_code = 304;
}
