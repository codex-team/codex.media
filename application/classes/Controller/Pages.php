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
                # если адрес без uri /page/24
                # то редирект на страницу вида /page/24/o-shkole
                $this->redirect('/page/' . $page->id . '/' . $page->uri);
            }

            if ($page->id_parent)
            {
                $page->parent = Model_Page::get($page->id_parent);
            }

            $page->childrens  = Model_Page::getChildrenPagesByParent($page->id);

            $this->view['page']      = $page;
            $this->view['files']     = $this->methods->getPageFiles($page->id);
            $this->template->content = View::factory('templates/page', $this->view);

        } else {
            # TODO ошибка: статья не найдена
        }
    }

    public function action_news_add()
    {
        if (!$this->user->isAdmin()) {
            # TODO ошибка: недостаточно прав
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
            # TODO ошибка: недостаточно прав
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
            # TODO ошибка: недостаточно прав
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
        $id = $this->request->param('id');
        $page = Model_Page::get($id);

        if ($this->user->isAdmin || $this->user-id == $page->author)
        {
            if (Security::check(Arr::get($_POST, 'csrf'))) {
                self::save_form();
            }

            $this->view['page'] = $page;
            $this->template->content = View::factory('templates/page_form', $this->view);
        } else {
            # TODO ошибка: недостаточно прав
            $this->redirect('/');
        }
    }

    public function action_delete()
    {
        $id = $this->request->param('id');
        $page = Model_Page::get($id);

        if ($this->user->isAdmin || $this->user-id == $page->author)
        {
            $page->delete();

            # правила редиректа
            if ($page->id_parent != 0) {
                $url = '/page/' . $page->id_parent;
            } elseif ($page->type != Controller_Pages::TYPE_SITE_NEWS) {
                $url = '/user/' . $page->author;
            } else {
                $url = '/';
            }
        } else {
            # TODO ошибка: недостаточно прав
            $url = '/';
        }

        $this->redirect($url);
    }

    public function save_form()
    {
        $id     = (int)Arr::get($_POST, 'id');
        $type   = (int)Arr::get($_POST, 'type');

        if ($type) {
            $page = new Model_Page();
            $page->type          = $type;
            $page->author        = $this->user->id;
            $page->id_parent     = (int)Arr::get($_POST, 'id_parent', 0);
            $page->title         = Arr::get($_POST, 'title');
            $page->content       = Arr::get($_POST, 'content');
            $page->is_menu_item  = Arr::get($_POST, 'is_menu_item', 0);

            if ($page->title)
            {
                if ($id) {
                    $page->id = $id;
                    $page->update();
                    $url = '/page/' . $id;
                } else {
                    $page = $page->insert();
                    $url = '/page/' . $page->id;
                }
                $this->redirect($url);

            } else {

                return FALSE;
                # TODO ошибка: отсутствует заголовок

            }
        }
    }
}