<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_416 extends HTTP_Exception
{

    /**
     * @var int HTTP 416 Request Range Not Satisfiable
     */
    protected $_code = 416;
}
