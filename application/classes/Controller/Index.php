<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Base_preDispatch
{

    public function action_index()
    {
        $this->view['pages'] = Model_Page::getPages(Model_Page::TYPE_SITE_NEWS, 10, 0, 0, true);
        $this->template->content = View::factory('templates/index', $this->view);
    }

    public function action_contacts()
    {
        $this->template->title = 'Контакты';
        $this->template->content = View::factory('templates/contacts', $this->view);
    }

    public function action_users_list()
    {   
        $status = Model_User::USER_STATUS_REGISTERED;

        if ($this->request->param('type') == 'teachers')
        {
            $status = Model_User::USER_STATUS_TEACHER;           
        }

        $this->view['users']    = Model_User::getUsersList($status);
        $this->view['status']   = $status;

        $this->template->content = View::factory('templates/users/list', $this->view);
    }
}