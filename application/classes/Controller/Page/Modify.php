<?php

class Controller_Page_Modify extends Controller_Base_preDispatch
{

    public function action_save() {

        if (!$this->request->is_ajax() || !Security::check(Arr::get($_POST, 'csrf'))) {
            throw new HTTP_Exception_403();
        }

        /** check permissions for cteate or edit subpage */
        $page_parent = (int) Arr::get($_POST, 'parent', Arr::get($_GET, 'parent', 0));
        $parent = new Model_Page($page_parent);
        $is_valid_parent = $parent->id != 0 ? $this->user->id == $parent->author->id : true;

        /** check permissions for edit */
        $page_id = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
        $page = new Model_Page($page_id);
        $is_valid_author = $page_id ? $this->user->id == $page->author->id : true;

        $response = array(
            'success' => 0,
          );

        if (!$this->user->id || !$is_valid_parent || !$is_valid_author) {

            $response['message'] = 'Недостаточно прав для создания или редактирования страницы сайта';

            $this->auto_render = false;
            $this->response->body(@json_encode($response));

            return;

        }

        $page->title = Arr::get($_POST, 'title', $page->title);
        $page->content = Arr::get($_POST, 'content', $page->content);
        $page->id_parent = $page_parent;

        if(!$page->title) {

            $response['message'] = 'Не заполнен заголовок';

            $this->auto_render = false;
            $this->response->body(json_encode($response));

            return;

        }

        if ($page->id) {
            $page->update();
        } else {

            $page->author = $this->user;
            $page = $page->insert();

        }

        $response = array(
            'success' => 1,
            'message' => 'Страница успешно сохранена',
            'id'      => $page->id
        );

        $this->auto_render = false;
        $this->response->body(json_encode($response));

    }

    public function action_promote() {

        $this->auto_render = false;

        $id   = $this->request->param('id');
        $page = new Model_Page($id);

        $response = array(
            'success' => 0,
        );

        if (!$this->user->isAdmin) {
            $response['message'] = 'Недостаточно прав для добавления страницы в список';
            $this->response->body(json_encode($response));
            return;
        }

        $feed_key = Arr::get($_GET, 'list', '');

        $page->toggleFeed($feed_key);

        $response['success'] = 1;
        $response['message'] = 'Статус страницы изменен';
        $this->response->body(json_encode($response));

    }

    public function action_delete()
    {

        $id   = $this->request->param('id');
        $page = new Model_Page($id);

        $response = array(
            'success' => 0,
        );

        if ($this->user->isAdmin || $this->user->id == $page->author->id) {

            $page->parent = new Model_Page($page->id_parent);
            $page->setAsRemoved();

            $response = array(
                'success' => 1,
                'message' => 'Страница удалена',
                'redirect' => $page->getUrlToParentPage()
            );

        } else {

            $response['message'] = 'Недостаточно прав для удаления страницы';

        }

        $this->auto_render = false;
        $this->response->body(json_encode($response));

    }





}