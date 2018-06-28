<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Base_preDispatch
{
    const NEWS_LIMIT_PER_PAGE = 7;
    const PORTION_OF_EVENTS = 3;

    public function action_index()
    {
        $feed_key = $this->request->param('feed_key') ?: Model_Feed_Pages::MAIN;
        $page_number = $this->request->param('page_number') ?: 1;

        $offset = ($page_number - 1) * self::NEWS_LIMIT_PER_PAGE;

        $feed = new Model_Feed_Pages($feed_key);

        $pages = $feed->get(self::NEWS_LIMIT_PER_PAGE + 1, $offset);


        $events_feed = new Model_Feed_Pages(Model_Feed_Pages::EVENTS);

        $events = $events_feed->get(self::PORTION_OF_EVENTS);
        $total_events = count($events_feed->get());

        /** Check if next page exist */
        $next_page = Model_Methods::isNextPageExist($pages, self::NEWS_LIMIT_PER_PAGE);

        if ($next_page) {
            unset($pages[self::NEWS_LIMIT_PER_PAGE]);
        }
        /***/

        if (Model_Methods::isAjax()) {
            $response = [];
            $response['success'] = 1;
            $response['next_page'] = $next_page;
            $response['list'] = View::factory('templates/pages/list', ['pages' => $pages, 'active_tab' => $feed_key])->render();

            $this->auto_render = false;
            $this->response->headers('Content-Type', 'application/json; charset=utf-8');
            $this->response->body(json_encode($response));
        } else {
            $this->view['tabs'] = Kohana::$config->load('index-tabs');
            $this->view['pages'] = $pages;
            $this->view['events'] = $events;
            $this->view['total_events'] = $total_events;
            $this->view['events_uri'] = Model_Feed_Pages::EVENTS;
            $this->view['next_page'] = $next_page;
            $this->view['about_page_data'] = self::getAboutPageData();
            $this->view['page_number'] = $page_number;
            $this->view['active_tab'] = $feed_key ?: Model_Feed_Pages::MAIN;

            $this->template->content = View::factory('templates/index', $this->view);
        }
    }

    /**
     * Get from the database in table 'settings' page id with site description
     * Fill array with title, description and uri of this page
     *
     * @return array - title, description and uri
     */
    public static function getAboutPageData()
    {
        $site_settings = new Model_Settings();
        $site_settings = $site_settings->getAll();
        $page = new Model_Page($site_settings['about_page']);
        $about_page_data = [];
        if ($page) {
            $about_page_data['title'] = $page->title;
            $about_page_data['description'] = $page->description;
            $about_page_data['uri'] = '/p/' . $page->id;
        }

        return $about_page_data;
    }
}
