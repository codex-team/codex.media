<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{


    public function action_show_page()
    {
        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id);

        if ($page->title)
        {
            if ($uri != $page->uri)
            {
                $this->redirect('/p/' . $page->id . '/' . $page->uri);
            }

            $page->childrens  = Model_Page::getChildrenPagesByParent($page->id);

            $this->view['can_modify_this_page'] = $this->user->isAdmin || ($this->user->id == $page->author->id && $this->user->isTeacher);

            $this->view['navigation'] = self::get_navigation_path_array($page->id);
            $this->view['page']       = $page;
            $this->view['files']      = $this->methods->getPageFiles($page->id);
            $this->template->content  = View::factory('templates/page', $this->view);

        } else {

            self::error_page('Запрашиваемая страница не найдена');
            return FALSE;
        }
    }

    public function action_add_page()
    {
        if (!$this->user->isTeacher())
        {
            self::error_page('Недостаточно прав для создания страницы');
            return FALSE;
        }

        $page = new Model_Page();

        $param_type = $this->request->param('type');
        $page->id_parent = $this->request->param('id');

        $page->type = self::set_page_type($param_type, $page);

        if (!$this->user->isAdmin() && $page->type != Model_Page::TYPE_USER_PAGE)
        {
            self::error_page('Недостаточно прав для создания новости или страницы сайта');
            return FALSE;
        }

        $errors = array();

        if (Security::check(Arr::get($_POST, 'csrf')))
        {
            $page = self::get_form();

            if ($page->title)
            {
                $page = self::save_page($page);
                $this->redirect('/p/' . $page->id . '/' . $page->uri);

            } else {

                $errors['title'] = 'Заголовок страницы не может быть пустым';
            }
        }

        $this->view['page']      = $page;
        $this->view['errors']    = $errors;
        $this->template->content = View::factory('templates/page_form', $this->view);
    }

    public function action_edit_page()
    {
        $id = $this->request->param('id');
        $page = new Model_Page($id);

        $errors = array();

        if ($this->user->isAdmin || ($this->user->id == $page->author->id && $this->user->isTeacher))
        {
            if (Security::check(Arr::get($_POST, 'csrf')))
            {
                $page = self::get_form();

                if ($page->title)
                {
                    $page = self::save_page($page);
                    $this->redirect('/p/' . $page->id . '/' . $page->uri);
                } else {
                    $errors['title'] = 'Заголовок страницы не может быть пустым';
                }
            }

            $this->view['page']      = $page;
            $this->view['errors']    = $errors;
            $this->template->content = View::factory('templates/page_form', $this->view);

        } else {

            self::error_page('Недостаточно прав для редактирования страницы');
            return FALSE;
        }
    }

    public function action_delete_page()
    {
        $id = $this->request->param('id');
        $page = new Model_Page($id);

        if ($this->user->isAdmin || ($this->user->id == $page->author->id && $this->user->isTeacher))
        {
            $page->parent = new Model_Page($page->id_parent);
            $page->setAsRemoved();

            $url = self::get_url_to_parent_page($page);

        } else {

            self::error_page('Недостаточно прав для удаления страницы');
            return FALSE;
        }

        $this->redirect($url);
    }

    /**
     * Returns new page's type
     *
     * @author              Taly
     * @param $type         var $type from params request
     * @param $page         object Model_Page
     * @return int          page's type
     */
    public function set_page_type($type, $page)
    {
        if ($type == 'news')
        {
            return Model_Page::TYPE_SITE_NEWS;

        } else {

            $page->parent = new Model_Page($page->id_parent);

            if ($page->parent->type == Model_Page::TYPE_USER_PAGE || $page->parent->id == 0){
                return Model_Page::TYPE_USER_PAGE;
            } else {
                return Model_Page::TYPE_SITE_PAGE;
            }
        }
    }

    /**
     * Function for getting path from root (main page or user's page) to this page
     * Returns array of Pages
     *
     * @author Taly
     * @param $id               this page id
     * @return array            array of objects, parent pages from root + this page
     */
    public function get_navigation_path_array($id)
    {
        $navig_array = array();

        while ($id != 0)
        {
            $page = new Model_Page($id);

            array_unshift($navig_array, $page);

            $id = $page->id_parent;
        }

        return $navig_array;
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
        $page->rich_view     = Arr::get($_POST, 'rich_view', 0);
        $page->dt_pin        = Arr::get($_POST, 'dt_pin');

        return $page;
    }

    public function save_page($page)
    {
        if ($page->id) {
            $page->update();
        } else {
            $page = $page->insert();
        }

        return $page;
    }

    public function get_url_to_parent_page($page)
    {
        if ($page->id_parent != 0) {
            return '/p/' . $page->parent->id . '/' . $page->parent->uri;
        } elseif ($page->type != Model_Page::TYPE_SITE_NEWS) {
            return '/user/' . $page->author->id;
        } else {
            return '/';
        }
    }

    public function error_page($error_text)
    {
        $this->view['error_text'] = $error_text;

        $this->template->content = View::factory('templates/error', $this->view);
    }


}
