<?php

class Controller_Page_Index extends Controller_Base_preDispatch
{

    public function action_show() {

        $id  = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id);

        if (!$page->id || $page->status == Model_Page::STATUS_REMOVED_PAGE) {

            throw new HTTP_Exception_404();
            //self::error_page('Запрашиваемая страница не найдена');
            //return FALSE;
        }

        if ($uri != $page->uri) {
            $this->redirect('/p/' . $page->id . '/' . $page->uri);
        }

        $this->view['can_modify_this_page'] = $this->user->id == $page->author->id;
        $this->view['page']                 = $page;

        $this->template->content = View::factory('templates/pages/page', $this->view);


    }

    public function action_writing() {

        /** check permissions for cteate or edit subpage */
        $page_parent = (int) Arr::get($_POST, 'parent', Arr::get($_GET, 'parent', 0));
        $parent = new Model_Page($page_parent);
        $is_valid_parent = $parent->id != 0 ? $this->user->id == $parent->author->id : true;

        /** check permissions for edit */
        $page_id = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
        $page = new Model_Page($page_id);

        $is_valid_author = $page_id ? $this->user->id == $page->author->id : true;

        if (!$this->user->id || !$is_valid_parent || !$is_valid_author) {

            //self::error_page('Недостаточно прав для создания или редактирования страницы сайта');
            return FALSE;

        }

        if (Security::check(Arr::get($_POST, 'csrf'))) {

            $page->title   = Arr::get($_POST, 'title');
            $page->content = Arr::get($_POST, 'content', '{data:[]}');

        }

        if (!$page->id_parent) $page->id_parent = $page_parent;

        $this->template->content = View::factory('templates/pages/writing', array('page' => $page));

    }

}