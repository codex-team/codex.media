<?php defined('SYSPATH') or die('No direct script access.');

use SendGrid\Email;
use SendGrid\Content;
use SendGrid\Mail;

class Controller_Index extends Controller_Base_preDispatch
{
    const NEWS_LIMIT_PER_PAGE = 7;

    public function action_index()
    {
        $feed_key    = $this->request->param('feed_key') ?: Model_Feed_Pages::TYPE_NEWS;
        $page_number = $this->request->param('page_number') ?: 1;

        $offset = ($page_number - 1) * self::NEWS_LIMIT_PER_PAGE;

        $feed = new Model_Feed_Pages($feed_key);

        $pages = $feed->get(self::NEWS_LIMIT_PER_PAGE + 1, $offset);

        /** Check if next page exist */
        $next_page = Model_Methods::isNextPageExist($pages, self::NEWS_LIMIT_PER_PAGE);

        if ($next_page) {
            unset($pages[self::NEWS_LIMIT_PER_PAGE]);
        }
        /***/

        if (Model_Methods::isAjax()) {

            $response = array();
            $response['success']    = 1;
            $response['next_page']  = $next_page;
            $response['list']       = View::factory('templates/pages/list', array('pages' => $pages))->render();

            $this->auto_render = false;
            $this->response->headers('Content-Type', 'application/json; charset=utf-8');
            $this->response->body( json_encode($response) );

        } else {

            $this->view['pages']        = $pages;
            $this->view['next_page']    = $next_page;
            $this->view['page_number']  = $page_number;
            $this->view['active_tab']   = $feed_key ?: Model_Feed_Pages::TYPE_NEWS;

            $this->template->content = View::factory('templates/index', $this->view);
        }
    }

    public function action_contacts()
    {
        $this->template->title = 'Контакты';
        $this->template->content = View::factory('templates/contacts', $this->view);
    }

    public function action_users_list()
    {
        $role = Model_User::REGISTERED;

        if ($this->request->param('type') == 'teachers') {

            $role = Model_User::TEACHER;
        }

        $this->view['users']  = Model_User::getUsersList($role);
        $this->view['role'] = $role;

        $this->template->content = View::factory('templates/users/list', $this->view);
    }
}
