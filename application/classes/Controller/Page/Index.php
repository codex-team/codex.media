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

}