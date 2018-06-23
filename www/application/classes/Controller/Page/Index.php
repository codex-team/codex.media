<?php

/**
 * Class Controller_Page_Index
 *
 * Controller for page performance actions like Article page (/p/<id>/<uri>) or Editing page (/p/writing)
 */
class Controller_Page_Index extends Controller_Base_preDispatch
{

    /**
     * If page has many blocks, show wide article template
     */
    const BLOCKS_TO_WIDE = 3;

    /**
     * Gets page data and sends it into View template /page/pages
     *
     * @throws HTTP_Exception_404 if page not found
     */
    public function action_show()
    {
        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id, true);

        if (!$page->id || $page->status == Model_Page::STATUS_REMOVED_PAGE) {
            throw new HTTP_Exception_404();
        }

        if ($uri != $page->uri) {
            $this->redirect('/p/' . $page->id . '/' . $page->uri);
        }

        $page->stats->hit();
        $page->views += 1;

        $page->children = $page->getChildrenPages();
        $page->comments = $page->getComments();

        $this->view['page'] = $page;

        $this->title = $page->title;
        $this->view['isWide'] = count($page->blocks) > self::BLOCKS_TO_WIDE;

        if ($this->view['isWide']) {
            $this->template->contentOnly = true;
        }

        if ($page->is_community) {
            $this->template->aside = View::factory('templates/components/community_aside',['page' => $page]);
            $pageChildren = $page->getChildrenPages();
            $this->template->content = View::factory('templates/pages/community_page', [
                'page' => $page,
                'pageChildren' => $pageChildren
            ]);
        } else {
            $this->template->content = View::factory('templates/pages/page', $this->view);
        }

    }

    /**
     * Gets article data for Editing page template
     *
     * @throws HTTP_Exception_403 if user has no access for editing
     */
    public function action_writing()
    {
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
            $page->title = Arr::get($_POST, 'title');
            $page->content = Arr::get($_POST, 'content', '{items:[]}');
            $page->author = $this->user;
        }

        if (!$page->id_parent) {
            $page->id_parent = $parent_id;
        }

        $page->isPageOnMain = Arr::get($_POST, 'isNews', $page->isPageOnMain);
        $page->isPostedInVK = Arr::get($_POST, 'vkPost', $page->isPostedInVK);
        $isPersonalBlog = Arr::get($_POST, 'isPersonalBlog', '');

        $this->template->content = View::factory('templates/pages/writing', ['page' => $page, 'isPersonalBlog' => $isPersonalBlog]);
        $this->template->contentOnly = true;
        $this->template->hideBranding = true;
    }
}
