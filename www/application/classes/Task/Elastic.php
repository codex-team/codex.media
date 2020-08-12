<?php defined('SYSPATH') or die('No direct script access.');

class Task_Elastic extends Minion_Task
{
    private $elastic;

    protected function __construct()
    {
        parent::__construct();
        $this->elastic = new Model_Elastic();
    }

    protected function _execute(array $params)
    {
        $this->elastic->deleteAllOfType(Model_Page::ELASTIC_TYPE);

        $pages_data = Dao_Pages::select('id')->execute();

        foreach ($pages_data as $page_data) {
            $page = new Model_Page($page_data['id']);

            $this->elastic->create(
                Model_Page::ELASTIC_TYPE,
                $page->id,
                $page->toElasticFormat()
            );
        }
    }
}
