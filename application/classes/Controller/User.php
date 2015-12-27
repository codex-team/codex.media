<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{

    public function action_profile()
    {
        $user_id = $this->request->param('id');

        $act = Arr::get($_GET, 'newStatus');

        $viewUser = new Model_User($user_id);

        if ($this->user->isAdmin && $act) {
            $success = self::set_user_status($viewUser, $act);
        }

        $this->view['userPages'] = $viewUser->getUserPages();
        $this->view['viewUser']  = $viewUser;
        $this->view['success']   = isset($success) ? TRUE : FALSE;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);

    }

    public function set_user_status($viewUser, $act)
    {
        switch ($act) {
            case 'teacher'    :
                $status = Model_User::USER_STATUS_TEACHER;
                break;
            case 'banned'     :
                $status = Model_User::USER_STATUS_BANNED;
                break;
            case 'student'   :
                $status = Model_User::USER_STATUS_REGISTERED;
                break;
            default        :
                return FALSE;
        }
        return $viewUser->setUserStatus($status);
    }

}