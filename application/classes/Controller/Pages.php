<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{
    public function action_show_page()
    {
        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id);

        if ($page->title) {

            if ($uri != $page->uri) $this->redirect('/p/' . $page->id . '/' . $page->uri);

            $page->childrens = Model_Page::getChildrenPagesByParent($page->id);
            $page->files     = Model_File::getPageFiles($page->id, Model_File::PAGE_FILE);
            $page->images    = Model_File::getPageFiles($page->id, Model_File::PAGE_IMAGE);

            $this->view['can_modify_this_page'] = $this->user->isAdmin || ($this->user->id == $page->author->id && $this->user->isTeacher);
            $this->view['comments']             = Model_Comment::getCommentsByPageId($id);
            $this->view['navigation']           = self::get_navigation_path_array($page->id);
            $this->view['page']                 = $page;

            $this->template->content = View::factory('templates/page', $this->view);

        } else {

            self::error_page('Запрашиваемая страница не найдена');
            return FALSE;
        }
    }

    public function action_save()
    {
        if (!$this->user->isTeacher()) {

            self::error_page('Недостаточно прав для создания страницы');
            return FALSE;
        }

        if (!$this->user->isAdmin() && $page->type != Model_Page::TYPE_USER_PAGE) {

            self::error_page('Недостаточно прав для создания новости или страницы сайта');
            return FALSE;
        }

        $errors    = array();
        $csrfToken = Arr::get($_POST, 'csrf');

        if (Security::check($csrfToken)) {

            /** Сабмит формы */
            $page = self::get_form();

            if ($page->title && Arr::get($_POST, 'title')) {

                if ($page->id) {

                    $page->update();

                } else {

                    $page = $page->insert();
                }

                /**
                * Link attached files to current page
                */
                $this->savePageFiles($page->id);

                if ($page->type == Model_Page::TYPE_SITE_NEWS) {

                    $this->redirect('/');

                } else {

                    $this->redirect('/p/' . $page->id . '/' . $page->uri);
                }

            } else {

                $errors['title'] = 'Заголовок страницы не может быть пустым';
            }

        } else {

            /** Открытие формы */
            $page_id = (int) Arr::get($_GET, 'id', 0);
            $page    = new Model_Page($page_id);

            $page->files  = Model_File::getPageFiles($page->id, Model_File::PAGE_FILE);
            $page->images = Model_File::getPageFiles($page->id, Model_File::PAGE_IMAGE);

            /** Нам необходимо получить только ОДИН из параметров:
             * id       для редактирования существующей страницы
             * type     для создания новости или страницы
             * parent   для создания подстраницы
             */

            if (!$page_id)    $page->type      = (int) Arr::get($_GET, 'type', 0);
            if (!$page->type) $page->id_parent = (int) Arr::get($_GET, 'parent', 0);

        }

        $this->view['page']   = $page;
        $this->view['errors'] = $errors;

        $this->template->content = View::factory('templates/pages/new', $this->view);
    }

    public function action_delete_page()
    {
        $id   = $this->request->param('id');
        $page = new Model_Page($id);

        if ($this->user->isAdmin || ($this->user->id == $page->author->id && $this->user->isTeacher)) {

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

        while ($id != 0) {

            $page = new Model_Page($id);

            array_unshift($navig_array, $page);

            $id = $page->id_parent;
        }

        return $navig_array;
    }

    public function get_form()
    {
        $id   = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
        $page = new Model_Page($id);

        $page->type          = (int) Arr::get($_POST, 'type',         0);
        $page->id_parent     = (int) Arr::get($_POST, 'id_parent',    0);
        $page->title         =       Arr::get($_POST, 'title',        '');
        $page->content       =       Arr::get($_POST, 'content',      '');
        $page->is_menu_item  = (int) Arr::get($_POST, 'is_menu_item', 0);
        $page->rich_view     = (int) Arr::get($_POST, 'rich_view',    0);
        $page->dt_pin        =       Arr::get($_POST, 'dt_pin',       null);
        $page->source_link   =       Arr::get($_POST, 'source_link',  '');
        $page->author        =       $this->user;

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

    /**
    * Gets json-encoded attaches list from input
    * Writes this
    */
    private function savePageFiles($page_id)
    {
        $attaches = Arr::get($_POST, 'attaches');
        $attaches = json_decode($attaches, true);

        foreach ($attaches as $id => $file_row) {

            $file = new Model_File($id);
            $file->page  = $page_id;
            $file->title = $file_row['title'];
            $file->update();
        }
    }
}
