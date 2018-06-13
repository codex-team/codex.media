<?php defined('SYSPATH') or die('No direct script access.');

class HTTP_Exception_403 extends Kohana_HTTP_Exception_403
{
    public function get_response()
    {
        $response = Response::factory();

        $view = View::factory('templates/errors/403');

        // We're inside an instance of Exception here, all the normal stuff is available.
        $view->message = $this->getMessage();

        $response->status(403)->body($view->render());

        return $response;
    }
}
