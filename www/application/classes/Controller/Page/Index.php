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
     * Number of events to show in events block
     */
    const PORTION_OF_EVENTS = 3;

    /**
     * Tabs on community page
     */
    const LIST_PAGES = 'pages';
    const LIST_EVENTS = 'events';

    /**
     * Gets page data and sends it into View template /page/pages
     *
     * @throws HTTP_Exception_404 if page not found
     */
    public function action_show()
    {
        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = new Model_Page($id);

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
        $this->view['isWide'] = $page->type == Model_Page::EVENT || !$page->type == Model_Page::COMMUNITY && count($page->blocks) > self::BLOCKS_TO_WIDE;

        if ($this->view['isWide']) {
            $this->template->contentOnly = true;
        }

        if ($page->type == Model_Page::COMMUNITY) {
            $community_events = self::communityEvents($page->children);
            $total_events = count($community_events);
            $events_promo = self::communityEventsPromo($community_events);
            $list = $this->request->param('list') ?: self::LIST_PAGES;

            switch ($list) {

                case self::LIST_EVENTS:
                    $communityFeed = array_reverse($community_events, false);
                    break;

                case self::LIST_PAGES:
                    $communityFeed = array_reverse($page->children, false);
                    break;

                default:
                    $communityFeed = [];
                    break;
            }

            $this->template->aside = View::factory('templates/components/community_aside', ['page' => $page]);
            $this->template->content = View::factory('templates/pages/community_page', [
                'page' => $page,
                'events' => $events_promo,
                'total_events' => $total_events,
                'pages' => $communityFeed,
                'list' => $list
            ]);
        } else {
            $this->template->content = View::factory('templates/pages/page', $this->view);
        }
    }

    /**
     * Filter child events of specific community
     *
     * @param Model_Page[] $community_children Community child pages
     *
     * @return Model_Page[] $community_events Array of community child events
     */
    public function communityEvents($community_children)
    {
        $community_events = [];
        foreach ($community_children as $child) {
            if ($child->type == Model_Page::EVENT) {
                $community_events[] = $child;
            }
        }

        return $community_events;
    }

    /**
     * If community has more than 3 events, show them in promo block
     *
     * @param Model_Page[] $community_events Array of all community events
     *
     * @return Model_Page[] $community_events_promo Array of events to show in promo block
     */
    public function communityEventsPromo($community_events)
    {
        $community_events_promo = [];

        if (is_array($community_events) && count($community_events) >= self::PORTION_OF_EVENTS) {
            $community_events = array_reverse($community_events, true);
            foreach ($community_events as $event) {
                if (count($community_events_promo) < self::PORTION_OF_EVENTS) {
                    $community_events_promo[] = $event;
                }
            }
        }

        return $community_events_promo;
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
            $page->content = Arr::get($_POST, 'content', '{blocks:[]}');
            $page->type = Arr::get($_POST, 'type');
            $page->author = $this->user;

            /**
             * Insert page options depending on page type
             */
            switch ($page->type)
            {
                case Model_Page::COMMUNITY:
                    $page->options['short_description'] = Arr::get($_POST, 'short_description');
                    break;

                case Model_Page::EVENT:
                    $page->options['event_date'] = Arr::get($_POST, 'event_date');
                    $page->options['is_paid'] = Arr::get($_POST, 'is_paid');
                    break;

                default:
                    break;
            }
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
