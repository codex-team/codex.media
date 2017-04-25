<?php

/**
 * Class Controller_Page_Index
 *
 * Controller for page performance actions like Article page (/p/<id>/<uri>) or Editing page (/p/writing)
 */
class Controller_Page_Index extends Controller_Base_preDispatch
{

    /**
     * Gets page data and sends it into View template /page/pages
     *
     * @throws HTTP_Exception_404 if page not found
     */
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

        $page->children = $page->getChildrenPages();
        $page->comments = $page->getComments();

        $this->view['page'] = $page;

        $this->template->content = View::factory('templates/pages/page', $this->view);


    }

    /**
     * Gets article data for Editing page template
     *
     * @throws HTTP_Exception_403 if user has no access for editing
     */
    public function action_writing() {

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
            $page->content = Arr::get($_POST, 'content', '{items:[]}');
            $page->author  = $this->user;

        }

        if (!$page->id_parent) $page->id_parent = $parent_id;

        $page->isNewsPage   = Arr::get($_POST, 'isNews', $page->isNewsPage);
        $page->isPostedInVK = Arr::get($_POST, 'vkPost', $page->isPostedInVK);

        $this->template->content = View::factory('templates/pages/writing', array('page' => $page));
        $this->template->contentOnly = true;

    }

}
