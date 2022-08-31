<?php defined('SYSPATH') or die('No direct script access.');

use EditorJS\EditorJS;

class Task_07RemoveSecondTitleFieldInAttachesTool extends Minion_Task
{
    /**
     * Main function to be run
     *
     * @example ./minion 07RemoveSecondTitleFieldInAttachesTool
     *
     * @param array $params
     */
    protected function _execute(array $params)
    {
        /**
         * Get all pages from DB
         */
        $pages = Dao_Pages::select()->execute();

        /**
         * For each page we need to convert its content to keep up
         * with the second version of EditorJS
         */
        foreach ($pages as $page) {
            /**
             * Get and decode old page's content
             */
            $oldContentString = $page['content'];

            /**
             * Try to validate and detect articles with bad json
             */
            try {
                $this->validateNewContent($oldContentString);
            } catch (Exception $e) {
                echo "Article " . $page['id'] . " needs to be fixed. Converting... ";

                /**
                 * Convert content and get json string
                 */
                $oldContent = json_decode($oldContentString);

                $newContentString = $this->convertContent($oldContent);

                /**
                 * Validate and save article
                 */
                $this->validateNewContent($newContentString);

                Dao_Pages::update()
                    ->where('id', '=', $page['id'])
                    ->set('content', $newContentString)
                    ->execute();

                echo "Ok\n";
            }
        }

        echo 'Database has been updated';
    }

    /**
     * Convert page's content
     *
     * @param stdClass $oldContent - old page's content
     *
     * @return false|string - new encoded content
     */
    private function convertContent($oldContent)
    {
        /**
         * Converted data
         */
        $newContent = [
            'blocks' => []
        ];

        /**
         * Convert each block
         */
        foreach ($oldContent->blocks as $block) {
            $newBlock = $this->convertBlock($block);

            if ($newBlock) {
                array_push($newContent['blocks'], $newBlock);
            }
        }

        /**
         * Return encoded content
         */
        return json_encode($newContent);
    }

    /**
     * Convert block's params
     *
     * @param stdClass $oldBlock - old block's data
     *
     * @return array|boolean - converted block's data
     */
    private function convertBlock($oldBlock)
    {
        $type = $oldBlock->type;
        $data = $oldBlock->data;

        if ($type == 'attaches') {
            $data = [
                'title' => isset($data->title) ? $data->title : $data->file->title,
                'file' => [
                    'url' => isset($data->file->url) ? $data->file->url : '',
                    'name' => isset($data->file->name) ? $data->file->name : '',
                    'extension' => isset($data->file->extension) ? $data->file->extension : '',
                    'size' => isset($data->file->size) ? $data->file->size : ''
                ],
            ];
        }

        return [
            'type' => $type,
            'data' => $data
        ];
    }

    /**
     * Create an EditorJS class to check for errors in content
     *
     * @param string $contentString - article's data
     *
     * @throws Exceptions_ConfigMissedException
     * @throws \EditorJS\EditorJSException
     */
    private function validateNewContent($contentString)
    {
        $editor = new EditorJS($contentString, $this->getEditorConfig());
        $content = $editor->getBlocks();
    }

    /**
     * Get EditorJS config
     *
     * @throws Exceptions_ConfigMissedException
     *
     * @return false|string - EditorJs config data
     */
    private function getEditorConfig()
    {
        try {
            return file_get_contents(APPPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'editorjs-config.json');
        } catch (Exception $e) {
            throw new Exceptions_ConfigMissedException('EditorJS config not found');
        }
    }
}
