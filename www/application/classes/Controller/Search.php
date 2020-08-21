<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Base_preDispatch
{
    /**
     * Maximum elastic search results returned to user
     */
    const MAX_SEARCH_RESULTS = 5;

    public function action_search()
    {
        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');

        /**
         * Perform search for specified phrase using *word* pattern
         */
        $word = Arr::get($_GET, 'word', '');
        $response = $this->elastic->searchByField(
            Model_Page::ELASTIC_TYPE,
            self::MAX_SEARCH_RESULTS,
            Model_Page::ELASTIC_SEARCH_FIELD,
            $word
        );

        /**
         * Return Model_Page[] to user
         */
        $result = array_map(function ($item) {
            return new Model_Page($item['_id']);
        }, $response['hits']['hits']);

        $this->response->body(@json_encode($result));
    }
}
