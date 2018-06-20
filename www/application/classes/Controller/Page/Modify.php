<?php

class Controller_Page_Modify extends Controller_Base_preDispatch
{

    /**
     * @var Model_Page - current page to modify
     */
    public $page = null;

    /**
     * @var array - response for AJAX-request
     */
    public $ajax_response = [
        'success' => 0,
    ];

    public function before()
    {
        parent::before();

        $this->auto_render = false;

        if (!$this->request->is_ajax()) {
            throw new HTTP_Exception_403();
        }

        $this->page = $this->getPage();
    }

    /**
     * Saves new page or existing page changes
     */
    public function action_save()
    {
        $csrf = Arr::get($_POST, 'csrf', '');

        if (!$this->page->canModify($this->user) || !Security::check($csrf)) {
            $this->ajax_response['message'] = 'Похоже, у вас нет доступа';

            $this->response->body(json_encode($this->ajax_response));

            return;
        }

        $this->page->title = Arr::get($_POST, 'title', $this->page->title);
        $this->page->content = Arr::get($_POST, 'content', $this->page->content);

        $errors = $this->getErrors($_POST);

        if ($errors) {
            $this->ajax_response['message'] = implode(', ', $errors);
            $this->response->body(json_encode($this->ajax_response));
            return;
        }

        if ($this->page->id) {
            $this->page = $this->page->update();
        } else {
            $this->page = $this->page->insert();
            $this->page->addToFeed(Model_Feed_Pages::ALL);

            if ($this->page->author->isTeacher()) {
                $isPersonalBlog = Arr::get($_POST, 'isPersonalBlog', '');

                if (!$this->page->author->isAdmin() || !empty($isPersonalBlog)) {
                    $this->page->addToFeed(Model_Feed_Pages::TEACHERS);
                }
            }
        }

        if ($this->user->isAdmin()) {
            if (Arr::get($_POST, 'vkPost')) {
                /** Create or edit post on public's wall */
                if (!$this->page->isPostedInVK) {
                    $VkPost = $this->vkWall()->post($this->buildVKPost());
                    // } else {
                //     $VkPost = $this->vkWall()->edit($this->buildVKPost());
                }
                /***/
            } else {

                /** Delete post from public's wall */
                if ($this->page->isPostedInVK) {
                    $VkPost = $this->vkWall()->delete();
                }
                /***/
            }
        }

        if (Arr::get($_POST, 'isNews')) {
            if (!$this->page->isPageOnMain && $this->user->isAdmin()) {
                $this->page->addToFeed(Model_Feed_Pages::MAIN);
            }
        } else {
            if ($this->user->isAdmin()) {
                $this->page->removeFromFeed(Model_Feed_Pages::MAIN);
            }
        }

        $this->ajax_response = [
            'success' => 1,
            'message' => 'Страница успешно сохранена',
            'redirect' => '/p/' . $this->page->id . '/' . $this->page->uri
        ];

        $this->auto_render = false;
        $this->response->body(json_encode($this->ajax_response));
    }

    /**
     * Adds or removes page from News or Menu feeds
     */
    public function action_promote()
    {
        if (!$this->user->isAdmin) {
            $this->ajax_response['message'] = 'Вы не можете изменить статус статьи';
            $this->response->body(json_encode($this->ajax_response));

            return;
        }

        $feed_key = Arr::get($_GET, 'list', '');

        $this->page->toggleFeed($feed_key);

        $this->ajax_response['success'] = 1;

        switch ($feed_key) {
            case Model_Feed_Pages::MENU:
                $this->ajax_response['menu'] = View::factory('/templates/components/menu', ['site_menu' => Model_Methods::getSiteMenu()])->render();
                $this->ajax_response['message'] = $this->page->isMenuItem() ? 'Страница добавлена в меню' : 'Страница удалена из меню';
                $this->ajax_response['buttonText'] = $this->page->isMenuItem() ? 'Убрать из меню' : 'Добавить в меню';
                break;

            case Model_Feed_Pages::MAIN:
                $this->ajax_response['message'] = $this->page->isPageOnMain() ? 'Вывели на главную' : 'Убрали с главной';
                $this->ajax_response['buttonText'] = $this->page->isPageOnMain() ? 'Убрать с главной' : 'На главную';
                break;

        }


        $this->response->body(json_encode($this->ajax_response));
    }

    public function action_pin()
    {
        $id = $this->request->param('id');

        if (!$this->user->isAdmin()) {
            $this->ajax_response['message'] = 'Похоже, у вас нет доступа';
            goto finish;
        }

        $feed = new Model_Feed_Pages(Model_Feed_Pages::MAIN);
        $feed->togglePin($id);

        $this->ajax_response['success'] = 1;
        $this->ajax_response['message'] = $feed->isPinned($id) ? 'Запись закреплена' : 'Запись откреплена';
        $this->ajax_response['buttonText'] = $feed->isPinned($id) ? 'Открепить' : 'Закрепить';


        finish:
            $this->response->body(json_encode($this->ajax_response));
    }

    /**
     * Sets page status as removed
     */
    public function action_delete()
    {
        if ($this->page->canModify($this->user)) {
            $this->page->setAsRemoved();

            /** Delete post from public's wall */
            $this->vkWall()->delete();
            /***/

            $this->ajax_response = [
                'success' => 1,
                'message' => 'Страница удалена',
                'redirect' => $this->page->getUrlToParentPage()
            ];
        } else {
            $this->ajax_response['message'] = 'Вы не можете удалить эту страницу';
        }

        $this->auto_render = false;
        $this->response->body(json_encode($this->ajax_response));
    }

    /**
     * Gets current page model.
     * Data can be contained in request param or in $_POST array;
     */
    private function getPage()
    {
        $id = $this->request->param('id');

        /**
         * If page id found at request param this is existing page
         */
        if ($id) {
            return new Model_Page($id);
        }

        /**
         * If page id not found at request param, we should fill empty page model with data from $_POST
         */
        $id = (int) Arr::get($_POST, 'id', 0);
        $parent_id = (int) Arr::get($_POST, 'id_parent', 0);

        $page = new Model_Page($id);
        $page->author = $this->user;
        $page->id_parent = $parent_id;
        $page->parent = new Model_Page($parent_id);

        return $page;
    }

    /**
     * Validate form fields and returns array with error or false if all right
     *
     * @param $fields
     *
     * @return array|bool
     */
    private function getErrors($fields)
    {
        $errors = [];
        if (!Valid::not_empty($fields['title'])) {
            $errors[] = 'Не заполнен заголовок';
        }

        if (!Valid::not_empty($fields['content'])) {
            $errors[] = 'Некорректные данные, попробуйте обновить страницу';
        }

        return $errors ?: false;
    }

    /**
     * Create an instance of the class Model_Services_Vk for using
     *
     * @return object Model_Services_Vk
     */
    private function vkWall()
    {
        return new Model_Services_Vk($this->page->id);
    }

    /**
     * Function for getting text for post
     *
     * @return array — text and link for post
     */
    private function buildVKPost()
    {
        /** Take an instance of class for getting right description */
        $this->page = new Model_Page($this->page->id);

        $server_name = 'http' . ((Arr::get($_SERVER, 'HTTPS')) ? 's' : '') . '://' . Arr::get($_SERVER, 'HTTP_HOST');
        $link = "{$server_name}" . "/p/{$this->page->id}/{$this->page->uri}";

        $description = strip_tags($this->page->description);

        $text = "{$this->page->title}\n";
        $text .= "\n";
        $text .= "{$description}\n";
        // $text .= "\n";
        // $text .= $link;

        return ['text' => $text, 'link' => $link];
    }
}
