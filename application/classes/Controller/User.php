<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{

    public function action_profile()
    {
        $user_id = $this->request->param('id');

        $act = Arr::get($_GET, 'act');

        $viewUser = Model_User::get($user_id);

        if ($this->user->isAdmin && $act) {
            $success = $viewUser->setUserStatus($act);
        }

        $this->view['userPages'] = $viewUser->getUserPages();
        $this->view['viewUser']  = $viewUser;
        $this->view['success']   = isset($success) ? TRUE : FALSE;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);

    }

}