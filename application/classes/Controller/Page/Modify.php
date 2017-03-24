<?php

class Controller_Page_Modify extends Controller_Base_preDispatch
{

    /**
     * @var Model_Page - current page to modify
     */
    public $page = null;

    public function before() {

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
    public function action_save() {

        $response = array(
            'success' => 0,
        );

        $csrf = Arr::get($_POST, 'csrf', '');

        if (!$this->page->canModify($this->user) || !Security::check($csrf)) {

            $response['message'] = 'Похоже, у вас нет доступа';

            $this->response->body(json_encode($this->page));
            return;

        }

        $this->page->title = Arr::get($_POST, 'title', $this->page->title);
        $this->page->content = Arr::get($_POST, 'content', $this->page->content);

        $error = $this->getErrors(array(
            'title' => Arr::get($_POST, 'title', '')
        ));

        if ($error) {

            $response['message'] = $error['message'];

            $this->response->body(json_encode($response));

            return;

        }

        if ($this->page->id) {
            $this->page->update();
        } else {

            $this->page = $this->page->insert();
            $this->page->addToFeed(Model_Feed_Pages::TYPE_ALL);

            if ($this->page->author->isTeacher()) {
                $this->page->addToFeed(Model_Feed_Pages::TYPE_TEACHERS);
            }

        }

        $response = array(
            'success'  => 1,
            'message'  => 'Страница успешно сохранена',
            'redirect' => '/p/' . $this->page->id . '/' . $this->page->uri
        );

        $this->auto_render = false;
        $this->response->body(json_encode($response));

    }

    /**
     * Adds or removes page from News or Menu feeds
     */
    public function action_promote() {

        $response = array(
            'success' => 0,
        );

        if (!$this->user->isAdmin) {
            $response['message'] = 'Вы не можете изменить статус статьи';
            $this->response->body(json_encode($response));
            return;
        }

        $feed_key = Arr::get($_GET, 'list', '');

        $this->page->toggleFeed($feed_key);

        $response['success'] = 1;

        switch ($feed_key) {
            case 'menu':
                $response['menu'] = View::factory('/templates/components/menu', array('site_menu' => Model_Methods::getSiteMenu()))->render();
                $response['message'] = $this->page->isMenuItem() ? 'Страница добавлена в меню' : 'Страница удалена из меню';
                $response['buttonText'] = $this->page->isMenuItem() ? 'Убрать из меню' : 'Добавить в меню';
                break;

            case 'news':
                $response['message'] = $this->page->isNewsPage() ? 'Страница добавлена в новости' : 'Страница удалена из новостей';
                $response['buttonText'] = $this->page->isNewsPage() ? 'Убрать из новостей' : 'Добавить в новости';
                break;

        }


        $this->response->body(json_encode($response));

    }

    /**
     * Sets page status as removed
     */
    public function action_delete() {

        $response = array(
            'success' => 0,
        );

        if ($this->page->canModify($this->user)) {

            $this->page->setAsRemoved();

            $response = array(
                'success' => 1,
                'message' => 'Страница удалена',
                'redirect' => $this->page->getUrlToParentPage()
            );

        } else {

            $response['message'] = 'Вы не можете удалить эту страницу';

        }

        $this->auto_render = false;
        $this->response->body(json_encode($response));

    }

    /**
     * Gets current page model.
     * Data can be contained in request param or in $_POST array;
     *
     */
    private function getPage() {

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

        $page             = new Model_Page($id);
        $page->author     = $this->user;
        $page->id_parent  = $parent_id;
        $page->parent     = new Model_Page($parent_id);

        return $page;

    }

    /**
     * Validate form fields and returns array with error or false if all right
     *
     * @param $fields
     * @return array|bool
     */
    private function getErrors($fields) {

        if (!Valid::not_empty($fields['title'])) {
            return array(
                'message' => 'Не заполнен заголовок'
            );
        }

        return false;

    }



}