<?php defined('SYSPATH') or die('No direct script access.');


class Kohana_Exception extends Kohana_Kohana_Exception
{
    public static function response($e) {

        \Hawk\HawkCatcher::catchException($e);

        if (Kohana::$environment == Kohana::DEVELOPMENT) {

            return parent::response($e);

        } else {

            $view = new View('templates/errors/500');

            $response = Response::factory()->status(500)->body($view->render());

            return $response;
        }
    }
}
