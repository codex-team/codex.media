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
        $this->view['teachers'] = Model_User::getTeachersList(); 

        $this->template->content = View::factory('templates/teachers', $this->view);
    }
}