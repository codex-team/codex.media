<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Modify extends Controller_Base_preDispatch
{
    const TOGGLE_BAN = -1;
    const TOGGLE_PROMOTE = 2;

    public function action_changeStatus()
    {
        $response = array();
        $response['success'] = 1;

        $status = Arr::get($_GET, 'status', '');

        switch ($type) {
            case self::LIST_COMMENTS:
                $response['list'] = View::factory('templates/users/comments', array('user_feed' => $models))->render();
                break;

            case self::LIST_PAGES:
                $response['list'] = View::factory('templates/users/pages', array('user_feed' => $models))->render();
                break;

            default:
                $response['list'] = '';
                break;
        }

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($response) );
    }
}
