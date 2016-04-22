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

    public function action_teachers()
    {
        $users_type = $this->request->param('type');

        if ($users_type) {
            $status = Model_User::USER_STATUS_TEACHER;
        } else {
            $status = Model_User::USER_STATUS_REGISTERED;
        }

        $this->view['users'] = Model_User::getUsersList($status); 

        $this->template->content = View::factory('templates/users_list', $this->view);
    }
}