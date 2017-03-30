<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Modify extends Controller_Base_preDispatch
{
    const TOGGLE_BAN = -1;
    const TOGGLE_PROMOTE = 2;

    public function action_changeStatus()
    {
        $response = array();

        if (!$this->user->isAdmin) {

            $response['success'] = 0;
            $response['error'] = 'Access denied';
            goto finish;

        }


        $response['success'] = 1;
        $userId = Arr::get($_GET, 'userId', 0);
        $status = Arr::get($_GET, 'status', '');

        $response['userId'] = $userId;
        $viewUser = Model_User($userId);

        switch ($status) {
            case self::TOGGLE_BAN:
                $response['status'] = 'ban';
                break;

            case self::TOGGLE_PROMOTE:
                $response['status'] = 'promote';
                break;

            default:
                break;
        }

        finish:

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($response) );
    }
}
