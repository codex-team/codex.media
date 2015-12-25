<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{
    const TYPE_SITE_PAGE = 1;
    const TYPE_SITE_NEWS = 2;
    const TYPE_USER_PAGE = 3;


    public function action_show_page()
    {

        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = Model_Page::get($id);

        if ($page->title)
        {
            if (!$uri)
            {
                $this->redirect('/page/' . $page->id . '/' . $page->uri);
            }

            if ($page->id_parent)
            {
                $page->parent = new Model_Page($page->id_parent);
            }

            $page->childrens  = Model_Page::getChildrenPagesByParent($page->id);

            $this->view['page']      = $page;
            //$this->view['files']     = $this->methods->getPageFiles($page->id);
            $this->template->content = View::factory('templates/page', $this->view);

        }
    }

    public function action_news_add()
    {
        if (!$this->user->isAdmin()) {
            $this->redirect('/');
        }

        $page = new Model_Page();

        $page->type = Controller_Pages::TYPE_SITE_NEWS;

        if (Security::check(Arr::get($_POST, 'csrf')))
        {
            self::save_form();
        }

        $this->view['page']      = $page;
        $this->template->content = View::factory('templates/page_form', $this->view);

    }

    public function action_page_add()
    {
        if (!$this->user->isTeacher) {
            $this->redirect('/');
        }

        $page = new Model_Page();

        $page->type = Controller_Pages::TYPE_USER_PAGE;

        if (Security::check(Arr::get($_POST, 'csrf')))
        {
            self::save_form();
        }

        $this->view['page']      = $page;
        $this->template->content = View::factory('templates/page_form', $this->view);
    }

    public function action_subpage_add()
    {
        if (!$this->user->isTeacher) {
            $this->redirect('/');
        }

        $page = new Model_Page();
        $page->id_parent = $this->request->param('id');

        if ($page->id_parent)
        {
            $page->parent = Model_Page::get($page->id_parent);

            switch ($page->parent->type)
            {
                case Controller_Pages::TYPE_USER_PAGE :
                            $page_type = Controller_Pages::TYPE_USER_PAGE; break;
                default :   $page_type = Controller_Pages::TYPE_SITE_PAGE;
            }
            $page->type = $page_type;
        }

        if (Security::check(Arr::get($_POST, 'csrf')))
        {
            self::save_form();
        }

        $this->view['page']      = $page;
        $this->template->content = View::factory('templates/page_form', $this->view);
    }

    public function action_edit()
    {
        if (!$this->user->isTeacher) {
            $this->redirect('/');
        }

        $id = $this->request->param('id');
        $page = new Model_Page($id);

        if (Security::check(Arr::get($_POST, 'csrf')))
        {
            self::save_form();
        }

        $this->view['page']      = $page;
        $this->template->content = View::factory('templates/page_form', $this->view);
    }

    public function save_form()
    {
        $id     = (int)Arr::get($_POST, 'id');
        $type   = (int)Arr::get($_POST, 'type');

        if ($type) {
            $data = array(
                'type'          => $type,
                'author'        => $this->user->id,
                'id_parent'     => (int)Arr::get($_POST, 'id_parent', 0),
                'title'         => Arr::get($_POST, 'title'),
                'content'       => Arr::get($_POST, 'content'),
                'uri'           => Arr::get($_POST, 'uri', 'seturi'),
                'html_content'  => Arr::get($_POST, 'html_content', NULL),
                'is_menu_item'  => Arr::get($_POST, 'is_menu_item', 0),
            );

            if ($data['title'])
            {
                if ($id) {
                    $page = $this->methods->updatePage($id, $data);
                    $url = '/page/' . $id;
                } else {
                    $page = $this->methods->newPage($data);
                    $url = '/page/' . $page[0];
                }

                $this->redirect($url);
            } else {

                return FALSE;

            }
        }
    }
}