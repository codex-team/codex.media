<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{

    const USER_STATUS_ADMIN     = 2;
    const USER_STATUS_TEACHER   = 1;
    const USER_STATUS_STUDENT   = 0;
    const USER_STATUS_BANNED    = -1;


    public function action_profile()
    {
        $uid = $this->request->param('id');

        $act = Arr::get($_GET, 'act');

        $this->view['success'] = FALSE;

        $viewUser = new Model_User($uid);

        if ($this->user->isAdmin() && $act)
        {
            switch ($act) {
                case 'rise'    :
                    $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_TEACHER);
                    break;
                case 'ban'     :
                    $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_BANNED);
                    break;
                case 'degrade' :
                case 'unban'   :
                    $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_STUDENT);
                    break;
            }
        }

        $this->view['userPages'] = $viewUser->getUserPages($uid);
        $this->view['viewUser']  = $viewUser;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);


    }

}