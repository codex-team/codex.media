<?php defined('SYSPATH') or die('No direct script access.');

use Elasticsearch\ClientBuilder;
use \EditorJS\EditorJS;

class Task_Elastic extends Minion_Task
{
    private $index = "pages";
    private $type = "page";

    protected function _execute(array $params)
    {
        Elastic::deleteAllOfType($this->index, $this->type);

        $pages = Dao_Pages::select()->execute();

        foreach ($pages as $page) {
            Elastic::create($this->index, $this->type, $this->transformPageData($page));
        }

        /**
         * To check result open http://localhost:9200/pages/_search?size=1000 in browser
         */
    }

    private function getListData($items) {
        $data = [];
        foreach ($items as $item) {
            array_push($data, $item);
        }

        return $data;
    }

    private function transformPageData($page_data)
    {
        $editor = new EditorJS($page_data['content'], $this->getEditorConfig());
        $content = $editor->getBlocks();

        $text = [];
        foreach ($content as $content_block) {
            switch ($content_block['type']) {
                case 'list':
                    array_push(
                        $text,
                        implode("\n", self::getListData($content_block['data']['items']))
                    );
                    break;
                case 'paragraph':
                case 'header':
                    array_push($text, $content_block['data']['text']);
                    break;
                default:
                    break;
            }
        }

        return [
            'title' => $page_data['title'],
            'id' => $page_data['id'],
            'text' => empty($text) ? "" : implode("\n", $text)
        ];
    }

    private function getEditorConfig()
    {
        try {
            return file_get_contents(APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'editorjs-config.json');
        } catch (Exception $e) {
            throw new Exceptions_ConfigMissedException('EditorJS config not found');
        }
    }
}
