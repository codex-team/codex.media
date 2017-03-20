<?php defined('SYSPATH') or die('No direct script access.');

use CodexEditor\CodexEditor;

class Controller_Pages extends Controller_Base_preDispatch
{
    /**
     * Show page
     */
    public function action_show()
    {

        $id  = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id);

        $page->is_removed = $page->status == Model_Page::STATUS_REMOVED_PAGE;

        if ($page->title && !$page->is_removed) {

            if ($uri != $page->uri) {
                $this->redirect('/p/' . $page->id . '/' . $page->uri);
            }

            $escapeHTML   = true;
            $page->blocks = $page->getBlocks($escapeHTML);

            $page->parent    = new Model_Page($page->id_parent);
            $page->childrens = Model_Page::getChildrenPagesByParent($page->id);
            $page->comments  = Model_Comment::getCommentsByPageId($id);

            /** Render blocks */
            $page->blocks_array = array();

            for ( $i = 0; $i < count($page->blocks); $i++) {
                $page->blocks_array[] = View::factory(
                    'templates/editor/plugins/' . $page->blocks[$i]->type,
                    array(
                        'block' => $page->blocks[$i]->data
                    )
                )->render();
            }
            /***/

            $this->view['can_modify_this_page'] = $this->user->id == $page->author->id;
            $this->view['page']                 = $page;

            $this->template->content = View::factory('templates/pages/page', $this->view);

        } else {

            self::error_page('Запрашиваемая страница не найдена');
            return FALSE;
        }
    }

    /**
     * Open form for edit page or save it
     */
    public function action_save()
    {
        /** check permissions for cteate or edit subpage */
        $page_parent = (int) Arr::get($_POST, 'parent', Arr::get($_GET, 'parent', 0));
        $parent = new Model_Page($page_parent);
        $is_valid_parent = $parent->id != 0 ? $this->user->id == $parent->author->id : true;

        /** check permissions for edit */
        $page_id = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));


        $page = new Model_Page($page_id);

        $is_valid_author = $page_id ? $this->user->id == $page->author->id : true;

        if (!$this->user->id || !$is_valid_parent || !$is_valid_author) {

            self::error_page('Недостаточно прав для создания или редактирования страницы сайта');
            return FALSE;
        }

        /**
         * @var Compose Blocks list
         */
        $escapeHTML = false;
        $page->blocks = $page->getBlocks($escapeHTML);


        $errors    = array();
        $csrfToken = Arr::get($_POST, 'csrf');

        $openFullScreen = (int) Arr::get($_POST, 'openFullScreen') === 1;

        if (Security::check($csrfToken)) {

            /** Сабмит формы */
            $page = self::get_form();

            if ($openFullScreen) {

                $this->view['page']   = $page;
                $this->view['errors'] = $errors;
                $this->view['attachments'] = json_encode($page->attachments);

                $this->template->content = View::factory('templates/pages/writing', $this->view);
                return;

            }

            if ($page->title && Arr::get($_POST, 'title', '')) {

                if ($page->id) {
                    $page->update();
                } else {
                    $page = $page->insert();
                }

                /* Link attached files to current page */
                // $this->savePageFiles($page->id);

                /* insert page id to feeds */
                // $page->addPageToFeeds();

                $this->redirect('/p/' . $page->id . '/' . $page->uri);

            } else {

                $errors['title'] = 'Заголовок страницы не может быть пустым';
            }

        } else {
        /** open form */

            /** no need cause we've already got $page with $page_id */
            // $page_id = (int) Arr::get($_GET, 'id', 0);
            // $page    = new Model_Page($page_id);

            // $page->attachments = Model_File::getPageFiles($page->id, false, true);
            // $page->files       = Model_File::getPageFiles($page->id, Model_File::PAGE_FILE);
            // $page->images      = Model_File::getPageFiles($page->id, Model_File::PAGE_IMAGE);

            // $this->view['attachments'] = json_encode($page->attachments);

            if (!$page_id) $page->id_parent = $page_parent;

        }

        $this->view['page']   = $page;
        $this->view['errors'] = $errors;
        // $this->view['attachments'] = json_encode($page->attachments);

        $this->template->content = View::factory('templates/pages/writing', $this->view);
    }

    /**
     * Set "as removed" for page
     */
    public function action_delete()
    {
        $id   = $this->request->param('id');
        $page = new Model_Page($id);

        if ($this->user->isAdmin || $this->user->id == $page->author->id) {

            $page->parent = new Model_Page($page->id_parent);
            $page->setAsRemoved();

            $url = self::getUrlToParentPage($page);

        } else {

            self::error_page('Недостаточно прав для удаления страницы');
            return FALSE;
        }

        $this->redirect($url);
    }

    /**
     * Add or remove page to feed list or menu list
     */
    public function action_promote()
    {
        $id   = $this->request->param('id');
        $page = new Model_Page($id);

        if (!$this->user->isAdmin) {
            self::error_page('Недостаточно прав для добавления страницы в список');
            return FALSE;
        }

        $toggle_to_list = Arr::get($_GET, 'list', '');

        switch ($toggle_to_list) {
            case 'news':
                $page->is_news_page = 1 - $page->is_news_page;
                $page->togglePageInFeed('news', $page->is_news_page);
                break;

            case 'menu':
                $page->is_menu_item = 1 - $page->is_menu_item;
                break;
        }

        $page->update();

        $this->redirect('/p/' . $page->id . '/' . $page->uri);
    }

    public function get_form()
    {
        $config = Kohana::$config->load('editor');
        $content = Arr::get($_POST, 'content', '');

        try {

            $editor = new CodexEditor($content, $config);
            $JSONData = $editor->getData();

        } catch ( Exception $e) {

            // Empty JSON
            $JSONData = json_encode('[]');


        }

        $id   = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
        $page = new Model_Page($id);

        $page->id_parent     = (int) Arr::get($_POST, 'id_parent',    0);
        $page->title         =       Arr::get($_POST, 'title',        '');
        // $page->is_menu_item  = (int) Arr::get($_POST, 'is_menu_item', 0);
        // $page->is_news_page  = (int) Arr::get($_POST, 'is_news_page', 0);
        $page->rich_view     = (int) Arr::get($_POST, 'rich_view',    0);
        $page->dt_pin        =       Arr::get($_POST, 'dt_pin',       null);
        $page->author        =       $this->user;

        try {

            $pageContent = json_decode($JSONData);

            // get only blocks from JSON data
            if (property_exists($pageContent, 'data')) {
                $page->blocks  = $pageContent->data;

                // save content if everything is okay
                $page->content = $JSONData;
            }

        } catch (Exception $e) {

            throw new Kohana_Exception("Syntax Error" . $e->getMessage());
        }

        return $page;
    }

    public function getUrlToParentPage($page)
    {
        if ($page->id_parent != 0) {

            return '/p/' . $page->parent->id . '/' . $page->parent->uri;

        } elseif (!$page->is_news_page) {

            return '/user/' . $page->author->id;

        } else {

            return '/';
        }
    }

    /**
     * Log an error and show error page
     */
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

}
