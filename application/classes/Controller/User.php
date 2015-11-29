<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{

    public function action_profile()
    {
        $uid = $this->request->param('id');
        $act = Arr::get($_GET, 'act');

        $this->view['success'] = FALSE;

        $viewUser = new Model_User($uid);

        switch ($act) {
        	case 'rise'    : $this->view['success'] = $viewUser->setUserStatus(1); break;        	
        	case 'ban'     : $this->view['success'] = $viewUser->setUserStatus(2); break;
        	case 'degrade' :
        	case 'unban'   : $this->view['success'] = $viewUser->setUserStatus(0); break;        	
        }


        

        $this->view['viewUser']  = $viewUser;
        $this->template->title   = $viewUser->real_name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);


    }

}