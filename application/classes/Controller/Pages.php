<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{
    public function action_show_page()
    {
        $id  = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id);

        if ($page->title && $page->status <> Model_Page::STATUS_REMOVED_PAGE) {

            if ($uri != $page->uri) {

                $this->redirect('/p/' . $page->id . '/' . $page->uri);
            }

            $page->parent    = new Model_Page($page->id_parent);
            $page->childrens = Model_Page::getChildrenPagesByParent($page->id);
            $page->files     = Model_File::getPageFiles($page->id, Model_File::PAGE_FILE);
            $page->images    = Model_File::getPageFiles($page->id, Model_File::PAGE_IMAGE);

            $this->view['can_modify_this_page'] = $this->user->isAdmin || $this->user->id == $page->author->id;
            $this->view['comments']             = Model_Comment::getCommentsByPageId($id);
            $this->view['page']                 = $page;

            $this->template->content = View::factory('templates/page', $this->view);

        } else {

            self::error_page('Запрашиваемая страница не найдена');
            return FALSE;
        }
    }

    public function action_save()
    {
        if (!$this->user->id) {

            self::error_page('Недостаточно прав для создания страницы');
            return FALSE;
        }

        /**
         * если пользователь не админ, то надо проверить, не подменил ли он
         * type, parent или редактирует не свою страницу
         */
        if (!$this->user->isAdmin()) {

            /** проверка при создании подстраницы */
            $page_parent = (int) Arr::get($_POST, 'parent', Arr::get($_GET, 'parent', 0));
            $parent = new Model_Page($page_parent);
            $is_valid_parent = $parent->id != 0 ? $this->user->id == $parent->author->id : true;

            /** проверка типа */
            $page_type = (int) Arr::get($_POST, 'type', Arr::get($_GET, 'type', Model_Page::TYPE_SITE_PAGE));
            $is_valid_type = $parent->id == 0 ? $page_type != Model_Page::TYPE_SITE_NEWS : true;

            /** проверка на право редактирования */
            $page_id = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
            $page = new Model_Page($page_id);
            $is_valid_author = $page_id ? $this->user->id == $page->author->id : true;

            if (!$is_valid_parent || !$is_valid_type || !$is_valid_author) {

                self::error_page('Недостаточно прав для создания новости или страницы сайта');
                return FALSE;
            }
        }

        $errors    = array();
        $csrfToken = Arr::get($_POST, 'csrf');

        if (Security::check($csrfToken)) {

            /** Сабмит формы */
            $page = self::get_form();

            echo Debug::vars(json_decode($page->content));

            if ($page->title && Arr::get($_POST, 'title', 'no-title')) {

                if ($page->id) {

                    $page->update();

                } else {

                    $page = $page->insert();
                }

                /**
                * Link attached files to current page
                */
                $this->savePageFiles($page->id);

                $page->addPageToFeed();

                $this->redirect('/p/' . $page->id . '/' . $page->uri);

            } else {

                $errors['title'] = 'Заголовок страницы не может быть пустым';
            }

        } else {

            /** Открытие формы */
            $page_id = (int) Arr::get($_GET, 'id', 0);
            $page    = new Model_Page($page_id);

            $page->files  = Model_File::getPageFiles($page->id, Model_File::PAGE_FILE);
            $page->images = Model_File::getPageFiles($page->id, Model_File::PAGE_IMAGE);

            $page->attachments = Model_File::getPageFiles($page->id, false, true);
            $this->view['attachments'] = json_encode($page->attachments);

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
        $this->view['attachments'] = json_encode($page->attachments);

        $this->template->content = View::factory('templates/pages/new', $this->view);
    }

    public function action_delete_page()
    {
        $id   = $this->request->param('id');
        $page = new Model_Page($id);

        if ($this->user->isAdmin || $this->user->id == $page->author->id) {

            $page->parent = new Model_Page($page->id_parent);
            $page->setAsRemoved();

            $page->removePageFromFeed();

            $url = self::getUrlToParentPage($page);

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

        $page->type          = (int) Arr::get($_POST, 'type',         1);
        $page->id_parent     = (int) Arr::get($_POST, 'id_parent',    0);
        $page->title         =       Arr::get($_POST, 'title',        'no-title');
        $page->content       =       Arr::get($_POST, 'content',      '');
        $page->is_menu_item  = (int) Arr::get($_POST, 'is_menu_item', 0);
        $page->rich_view     = (int) Arr::get($_POST, 'rich_view',    0);
        $page->dt_pin        =       Arr::get($_POST, 'dt_pin',       null);
        $page->source_link   =       Arr::get($_POST, 'source_link',  '');
        $page->author        =       $this->user;

        return $page;
    }

    public function getUrlToParentPage($page)
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
        Log::instance()->add(Log::ERROR, ':error_text by :user_name (id :user_id) at :url',array(
            ':url' => $_SERVER['REQUEST_URI'],
            ':user_id' => $this->user->id,
            ':user_name' => $this->user->name,
            ':error_text' => $error_text,
        ));

        $this->view['error_text'] = $error_text;
        $this->template->content = View::factory('templates/error', $this->view);
    }

    /**
    * Gets json-encoded attaches list from input
    * Writes this
    */
    private function savePageFiles($page_id)
    {
        $new_attaches_list = Arr::get($_POST, 'attaches');
        $new_attaches_list = json_decode($new_attaches_list, true);
        $old_attaches_list = Model_File::getPageFiles($page_id);

        /**
         * Delete files
         */
        foreach ($old_attaches_list as $id => $file) {

            if (!array_key_exists($file->id, $new_attaches_list)) {

                $file = new Model_File($file->id);
                $file->is_removed = 1;

                $file->update();
            }
        }

        /**
         * Save files
         */
        foreach ($new_attaches_list as $id => $file_row) {

            $file = new Model_File($file_row['id']);

            if (!$file->page) {

                $file->page  = $page_id;
                $file->title = $file_row['title'];

                $file->update();
            }
        }
    }
}
