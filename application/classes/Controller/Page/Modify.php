<?php

class Controller_Page_Modify extends Controller_Base_preDispatch
{

    public $page = null;

    public function before() {

        parent::before();

        $this->auto_render = false;

        if (!$this->request->is_ajax()) {
            throw new HTTP_Exception_403();
        }

        $this->init();

    }

    public function action_save() {

        $response = array(
            'success' => 0,
        );

        $csrf = Arr::get($_POST, 'csrf', '');

        if (!$this->page->canModify($this->user) || !Security::check($csrf)) {

            $response['message'] = 'Недостаточно прав для создания или редактирования страницы сайта';

            $this->response->body(json_encode($this->page));
            return;

        }

        $this->page->title = Arr::get($_POST, 'title', $this->page->title);
        $this->page->content = Arr::get($_POST, 'content', $this->page->content);

        if(!$this->page->title) {

            $response['message'] = 'Не заполнен заголовок';

            $this->auto_render = false;
            $this->response->body(json_encode($response));

            return;

        }

        if ($this->page->id) {
            $this->page->update();
        } else {
            $this->page = $this->page->insert();
        }

        $response = array(
            'success' => 1,
            'message' => 'Страница успешно сохранена',
            'id'      => $this->page->id
        );

        $this->auto_render = false;
        $this->response->body(json_encode($response));

    }

    public function action_promote() {


        $response = array(
            'success' => 0,
        );

        if (!$this->user->isAdmin) {
            $response['message'] = 'Недостаточно прав для добавления страницы в список';
            $this->response->body(json_encode($response));
            return;
        }

        $feed_key = Arr::get($_GET, 'list', '');

        $this->page->toggleFeed($feed_key);

        $response['success'] = 1;
        $response['message'] = 'Статус страницы изменен';
        $this->response->body(json_encode($response));

    }

    public function action_delete()
    {


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

            $response['message'] = 'Недостаточно прав для удаления страницы';

        }

        $this->auto_render = false;
        $this->response->body(json_encode($response));

    }

    private function init() {

        $id = $this->request->param('id');

        if ($id) {
            $this->page = new Model_Page($id);
            return;
        }

        $id = (int) Arr::get($_POST, 'id', 0);
        $parent_id = (int) Arr::get($_POST, 'id_parent', 0);

        $this->page             = new Model_Page($id);
        $this->page->author     = $this->user;
        $this->page->id_parent  = $parent_id;
        $this->page->parent     = new Model_Page($parent_id);


    }



}