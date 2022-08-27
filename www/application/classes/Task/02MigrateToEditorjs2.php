<?php defined('SYSPATH') or die('No direct script access.');

use EditorJS\EditorJS;

class Task_02MigrateToEditorjs2 extends Minion_Task
{
    /**
     * Main function to be run
     *
     * @example ./minion 02MigrateToEditorjs2
     * @example ./minion 02MigrateToEditorjs2 apply
     *
     * @param array $params
     */
    protected function _execute(array $params)
    {
        /**
         * Check if you can safely convert all pages
         */
        $noErrorsFlag = true;

        /**
         * Get all pages from DB
         */
        $pages = Dao_Pages::select()->execute();

        /**
         * List of pages to be converted
         */
        $newPagesContent = [];

        /**
         * For each page we need to convert its content to keep up
         * with the second version of EditorJS
         */
        foreach ($pages as $page) {
            /**
             * Get and decode old page's content
             */
            $oldContent = json_decode($page['content']);

            /**
             * If cannot decode json content then clear data
             */
            if ($oldContent === null && json_last_error() !== JSON_ERROR_NONE) {
                echo "[Warning] Page " . $page['id'] . " has bad json content that will be erased on converting.\n";
                $newPagesContent[$page['id']] = '{"blocks":[]}';
                continue;
            }

            /**
             * Check if pages has been already converted
             */
            if (property_exists($oldContent, 'blocks')) {
                echo "Page " . $page['id'] . " has been already converted.\n";
                continue;
            }

            /**
             * Convert content and get json string
             */
            $newContentString = $this->convertContent($oldContent);

            try {
                /**
                 * Validate new data
                 */
                $this->validateNewContent($newContentString);

                /**
                 * If there is no error on validateNewContent
                 * then push pages to array as 'ready to be converted'
                 */
                $newPagesContent[$page['id']] = $newContentString;
            } catch (Exception $e) {
                /**
                 * Otherwise
                 * - show bad content
                 * - show error's text
                 * - set no errors flag to false
                 * - and exit foreach cycle
                 */
                var_dump(json_decode($page['content']));
                echo 'Error: ' . $e->getMessage();
                $noErrorsFlag = false;
                break;
            }
        }

        /**
         * Show error message
         */
        if (!$noErrorsFlag) {
            throw new Error('Cannot convert database.');
        } else {
            echo "\n";
            echo "Ready to convert database.\n";
            echo "Relaunch script with an 'apply' key.\n\n";
            echo "./minion migratetoeditorjs2 apply\n\n";
        }

        /**
         * Check if param 'apply' was passed
         * Then save result to database
         *
         * Get first array item
         */
        if (reset($params) == 'apply') {
            /**
             * Go through 'pages to be converted' array
             * Update data in database
             */
            foreach ($newPagesContent as $pageId => $pageContent) {
                echo "Page " . $pageId . " saving... ";
                Dao_Pages::update()
                    ->where('id', '=', $pageId)
                    ->set('content', $pageContent)
                    ->execute();
                echo "Ok\n";
            }

            echo 'Database has been updated';
        }
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
        foreach ($oldContent->items as $block) {
            $newBlock = $this->convertBlock($block);

            array_push($newContent['blocks'], $newBlock);
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
     * @return array - converted block's data
     */
    private function convertBlock($oldBlock)
    {
        $type = $oldBlock->type;
        $data = $oldBlock->data;

        switch ($type) {
            /**
             * Paragraph - https://github.com/editor-js/paragraph#output-data
             */
            case 'paragraph':
                $data = [
                    'text' => $data->text,
                ];
                break;

            /**
             * Header - https://github.com/editor-js/header#output-data
             */
            case 'header':
                $data = [
                    'text' => $data->text,
                    'level' => substr($data->{'heading-styles'}, -1)
                ];
                break;

            /**
             * List - https://github.com/editor-js/list#output-data
             */
            case 'list':
                $data = [
                    'style' => $data->type == 'OL' ? 'ordered' : 'unordered',
                    'items' => $data->items
                ];
                break;

            /**
             * Image - https://github.com/editor-js/image#output-data
             */
            case 'image':
                $data = [
                    'file' => [
                        'url' => $data->url,
                        'width' => $data->width,
                        'height' => $data->height
                    ],
                    'caption' => $data->caption,
                    'withBorder' => $data->border,
                    'withBackground' => $data->background,
                    'stretched' => $data->isstretch,
                ];
                break;

            /**
             * Code - https://github.com/editor-js/code#output-data
             */
            case 'code':
                $data = [
                    'code' => $data->text
                ];
                break;

            /**
             * Raw - https://github.com/editor-js/raw#output-data
             */
            case 'raw':
                $type = 'rawTool';
                $data = [
                    'html' => $data->raw
                ];
                break;

            /**
             * Quote - https://github.com/editor-js/quote#output-data
             */
            case 'quote':
                $data = [
                    'text' => $data->text,
                    'caption' => $data->cite || '',
                    'alignment' => 'left'
                ];
                break;

            /**
             * Link - https://github.com/editor-js/link#output-data
             */
            case 'link':
                $type = 'linkTool';
                $data = [
                    'link' => $data->linkUrl,
                    'meta' => [
                        'title' => $data->title,
                        'linkText' => $data->linkText,
                        'description' => $data->description,
                        'image' => $data->image
                    ]
                ];
                break;

            /**
             * Personality - https://github.com/editor-js/personality#output-data
             */
            case 'personality':
                $data = [
                    'name' => $data->name,
                    'description' => $data->cite,
                    'link' => $data->url ?: '',
                    'photo' => $data->photo ?: ''
                ];
                break;

            /**
             * Attaches - https://github.com/editor-js/attaches#output-data
             */
            case 'attaches':
                $data = [
                    'file' => [
                        'url' => $data->url,
                        'size' => $data->size,
                        'name' => $data->name,
                        'extension' => $data->extension
                    ],
                    'title' => $data->title
                ];
                break;

            default:
                throw new Error('Undefined block type: ' . $type);
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
