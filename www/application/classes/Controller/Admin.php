<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller_Base_preDispatch
{
    public static $categories = ['pages', 'page', 'index', 'news', 'users', 'parser', 'base'];

    public function action_index()
    {
        if (!$this->user->id) {
            $this->redirect('/');
        }

        $this->title = $this->view['title'] = 'Панель управления сайтом';

        $page = $this->view['category'] = $this->request->param('page');

        $form_saved = false;

        switch ($page) {

            case 'pages': $form_saved = self::pages(Model_Page::TYPE_SITE_PAGE); break;
            case 'news': $form_saved = self::pages(Model_Page::TYPE_SITE_NEWS); break;
            case 'users': self::users(); break;
            case 'parser': self::parser(); break;
            case 'index': default: self::adminIndexPage();
        }

        $this->view['form_saved'] = $form_saved;
        $this->template->content = View::factory('templates/admin/index', $this->view);
    }

    public function adminIndexPage()
    {
        $this->view['category'] = 'index';
    }
}
