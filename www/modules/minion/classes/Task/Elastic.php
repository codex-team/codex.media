<?php defined('SYSPATH') or die('No direct script access.');

class Task_Elastic extends Minion_Task
{
    private $index = "pages";
    private $type = "page";

    protected function _execute(array $params)
    {
        Elastic::deleteAllOfType($this->index, $this->type);

        $pages = Dao_Pages::select()->execute();
        $page_models = Model_Page::rowsToModels($pages);

        foreach ($page_models as $page) {
            Elastic::create(
                $this->index,
                $this->type,
                $page->id,
                Model_Page::toElasticFormat($page)
            );
        }

        /**
         * To check result open http://localhost:9200/pages/_search?size=1000 in browser
         */
    }
}
