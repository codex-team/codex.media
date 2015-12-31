<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{


    public function action_show_page()
    {
        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = Model_Page::get($id);
        if ($page->title)
        {
            if (!$uri)
            {
                $this->redirect('/' . $page->id . '/' . $page->uri);
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

            self::error_page('Запрашиваемая страница не найдена');
            return FALSE;
        }
    }

    public function action_add_new()
    {
        if (!$this->user->isTeacher())
        {
            self::error_page('Недостаточно прав для совершения данного действия');
            return FALSE;
        }

        $page = new Model_Page();

        $type = $this->request->param('type');

        if (isset($type))
        {
            switch ($this->request->param('type')) {
                case 'news' :
                    $page->type = Model_Page::TYPE_SITE_NEWS;
                    break;
                case 'page' :
                default :
                    $page->type = Model_Page::TYPE_USER_PAGE;
            }
        } else {

            $page->id_parent = $this->request->param('id');

            if ($page->id_parent)
            {
                $page->parent = Model_Page::get($page->id_parent);

                switch ($page->parent->type)
                {
                    case Model_Page::TYPE_USER_PAGE :
                                $page_type = Model_Page::TYPE_USER_PAGE; break;
                    default :   $page_type = Model_Page::TYPE_SITE_PAGE;
                }
                $page->type = $page_type;
            }
        }

        if (!$this->user->isAdmin() && $page->type != Model_Page::TYPE_USER_PAGE)
        {
            self::error_page('Недостаточно прав для совершения данного действия');
            return FALSE;
        }

        if (Security::check(Arr::get($_POST, 'csrf')))
        {
            $page = self::get_form();

            if ($page->title && $page->type)
            {
                $this->redirect(self::save_page($page));
            } else {
                $page->uri = 'no-title';
            }
        }

        $this->view['page']      = $page;
        $this->template->content = View::factory('templates/page_form', $this->view);
    }

    public function action_edit()
    {
        $id = $this->request->param('id');
        $page = Model_Page::get($id);

        if ($this->user->isAdmin || $this->user->id == $page->author->id)
        {
            if (Security::check(Arr::get($_POST, 'csrf')))
            {
                $page = self::get_form();

                if ($page->title && $page->type)
                {
                    $this->redirect(self::save_page($page));
                } else {
                    $page->uri = 'no-title';
                }
            }

            $this->view['page'] = $page;
            $this->template->content = View::factory('templates/page_form', $this->view);
        } else {

            self::error_page('Недостаточно прав для совершения данного действия');
            return FALSE;

        }
    }

    public function action_delete()
    {
        $id = $this->request->param('id');
        $page = Model_Page::get($id);

        if ($this->user->isAdmin || ($this->user->id == $page->author->id && $this->user->isTeacher))
        {
            $page->delete();

            # правила редиректа
            if ($page->id_parent != 0) {
                $url = '/' . $page->id_parent;
            } elseif ($page->type != Model_Page::TYPE_SITE_NEWS) {
                $url = '/user/' . $page->author->id;
            } else {
                $url = '/';
            }
        } else {

            self::error_page('Недостаточно прав для совершения данного действия');
            return FALSE;

        }

        $this->redirect($url);
    }

    public function get_form()
    {
        $page = new Model_Page();

        $page->id            = (int)Arr::get($_POST, 'id');
        $page->type          = (int)Arr::get($_POST, 'type');
        $page->author        = $this->user;
        $page->id_parent     = (int)Arr::get($_POST, 'id_parent', 0);
        $page->title         = Arr::get($_POST, 'title', '');
        $page->content       = Arr::get($_POST, 'content', '');
        $page->is_menu_item  = Arr::get($_POST, 'is_menu_item', 0);

        return $page;
    }
    
    public function save_page($page)
    {
        if ($page->id) {
            $page->update();
        } else {
            $page = $page->insert();
        }
        
        return '/' . $page->id;
    }

    public function error_page($error_text)
    {
        $this->view['error_text'] = $error_text;

        $this->template->content = View::factory('templates/error', $this->view);
    }


}