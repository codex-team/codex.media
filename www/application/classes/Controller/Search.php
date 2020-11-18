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
         * Perform search for specified phrase using *query* pattern
         */
        $query = htmlspecialchars(Arr::get($_GET, 'query', ''));

        $response = [];
        $success = 0;
        $error = "";

        try {
            $response = $this->elastic->searchByField(
                Model_Page::ELASTIC_TYPE,
                self::MAX_SEARCH_RESULTS,
                Model_Page::ELASTIC_SEARCH_FIELDS,
                $query
            );

            $success = 1;
        } catch (Exception $err) {
            $error = $err->getMessage();
        }

        if ($success) {
            /**
             * Return pages search result to user
             */
            $result = array_map(function ($item) {
                return new Model_Page($item['_id']);
            }, $response['hits']['hits']);

            /**
             * Sort by date: display newest first
             */
            usort($result, function($first, $second){
                return $first->date < $second->date;
            });

            $search_result['html'] = View::factory(
                'templates/pages/list',
                array(
                    'pages' => $result,
                    'active_tab' => Model_Feed_Pages::ALL,
                    'emptyListMessage' => 'Ничего не найдено'
                )
            )->render();
        } else {
            $search_result['error'] = $error;
        }

        $this->response->body(@json_encode($search_result));
    }
}
