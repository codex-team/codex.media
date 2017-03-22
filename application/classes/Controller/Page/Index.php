<?php

class Controller_Page_Index extends Controller_Base_preDispatch
{

    public function action_show() {

        $id  = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id, true);

        if (!$page->id || $page->status == Model_Page::STATUS_REMOVED_PAGE) {

            throw new HTTP_Exception_404();
        }

        if ($uri != $page->uri) {
            $this->redirect('/p/' . $page->id . '/' . $page->uri);
        }

        $page->getChildrenPages();
        $page->getComments();

        $this->view['can_modify_this_page'] = $page->canModify($this->user);
        $this->view['page']                 = $page;

        $this->template->content = View::factory('templates/pages/page', $this->view);


    }

    public function action_writing() {

        if (!$this->user->id) {
            throw new HTTP_Exception_403();
        }

        $page_id = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
        $parent_id = (int) Arr::get($_POST, 'parent', Arr::get($_GET, 'parent', 0));

        $page = new Model_Page($page_id);

        if (!$page->id) {
            $page->author = $this->user;
            $page->parent = new Model_Page($parent_id);
        }

        if (!$page->canModify($this->user)) {
            throw new HTTP_Exception_403();
        }

        if (Security::check(Arr::get($_POST, 'csrf'))) {

            $page->title   = Arr::get($_POST, 'title');
            $page->content = Arr::get($_POST, 'content', '{data:[]}');
            $page->author  = $this->user;

        }

        if (!$page->id_parent) $page->id_parent = $parent_id;

        $this->template->content = View::factory('templates/pages/writing', array('page' => $page));

    }

}