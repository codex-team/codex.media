<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Parser extends Controller_Base_preDispatch
{
    public function action_get_page()
    {
        $url = Arr::get($_GET, 'url', '');

        $response = self::getPageTitleAndArticleByUrl($url);

        $response['success'] = 0;

        if ($response['title'] != $response['article']) {
            $response['success'] = 1;
        }

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body(@json_encode($response));
    }

    public function getPageTitleAndArticleByUrl($url)
    {
        $response = ["title" => "", "article" => ""];

        if ($url) {
            $page = self::getPageHtmlByUrl($url);

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);

            $doc->recover = true;
            $doc->strictErrorChecking = false;

            $encoding = mb_detect_encoding($page);
            $doc->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', $encoding));

            libxml_clear_errors();

            $response['title'] = self::getTitle($doc);
            $response['article'] = self::getArticleText($doc);
        }

        return $response;
    }

    /**
     * Получаем код страницы
     *
     * @param mixed $url
     */
    public function getPageHtmlByUrl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:31.0) Gecko/20100101 Firefox/31.0 ");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /**
     * Получаем заголовок страницы
     *
     * @param mixed $doc
     */
    public function getTitle($doc)
    {
        $pageTitle = '';

        $h1 = $doc->getElementsByTagName('h1');
        $title = $doc->getElementsByTagName('title');

        /** получаем h1 или title */
        if ($h1->length) {
            $pageTitle = $h1->item(0)->nodeValue;
        } elseif ($title->length) {
            $pageTitle = $title->item(0)->nodeValue;
        }

        $pageTitle = trim($pageTitle);

        return $pageTitle;
    }

    /*
    * Parses html page and extracts article text
    * Looks for node with many paragraph-tags
    */
    public function getArticleText($doc)
    {
        /**
         * Working with all paragraphs on page
         */
        $paragraphs = $doc->getElementsByTagName('p');

        /**
         * Collect information about each <p> parent element.
         *
         * Save it in parents array:
         *   array = (
         *       'DIV@article_content' => array(
         *           node   => DOMElement    // cursor to node
         *           childs => 12            // paragraphs count
         *       ),
         *       ...
         *   )
         */
        $parents = [];

        for ($i = 0; $i < $paragraphs->length; $i++) {
            $parentNode = $paragraphs->item($i)->parentNode;

            /** Compose node text-identifier looks like 'TAGNAME@classname' */
            $parentNodeIdentifier = self::getNodeIdentifier($parentNode);

            if (!isset($parents[$parentNodeIdentifier])) {
                $parents[$parentNodeIdentifier] = [
                    'node' => $parentNode,
                    'childs' => 1
                ];
            } else {
                $parents[$parentNodeIdentifier]['childs']++;
            }
        }

        /**
         * Now, get parent-node with maximum paragraphs count
         * It might be an article we look for.
         */
        $maximumParagraphsCount = 0;
        $nodeWithMaximumParagraphs = null;

        foreach ($parents as $item) {
            if ($item['childs'] > $maximumParagraphsCount) {
                $maximumParagraphsCount = $item['childs'];
                $nodeWithMaximumParagraphs = $item['node'];
            }
        }

        /**
         * Extract HTML-content from article node
         */
        $articleContent = self::DOMinnerHTML($nodeWithMaximumParagraphs);

        return $articleContent;
    }

    /**
     * Compose node text-identifier
     *
     * @todo add another attributes. Many elements can be without classname
     *
     * @return string tagname@classname. Example: 'DIV@article_content'
     */
    private static function getNodeIdentifier(DOMNode $node)
    {
        $tagName = $node->nodeName;
        $className = '';

        if ($classAttr = $node->attributes->getNamedItem('class')) {
            $className = $classAttr->nodeValue;
        }

        return $tagName . '@' . $className;
    }

    /**
     * Returns DOMNode inner html content
     */
    private static function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = '';
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    /**
     * fetch Action
     *
     * @public
     *
     * Returns JSON response
     */
    public function action_fetchURL()
    {
        $response = $this->parseLink();

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body(@json_encode($response));
    }

    /**
     * @private
     *
     * Parses link by ajax request
     */
    private function parseLink()
    {
        $URL = Arr::get($_GET, 'url');

        $response = [];
        $response['success'] = 0;

        if (empty($URL) || !filter_var($URL, FILTER_VALIDATE_URL)) {
            $response['message'] = 'Неправильный URL';
            goto finish;
        }

        /**
         * Make external request
         * Use Kohana Native Request Factory
         */
        $request = Request::factory($URL, array(
            'follow' => true
        ))->execute();

        if ($request->status() != '200') {
            $response['message'] = 'Ошибка при обработке ссылки';
            goto finish;
        } else {
            $htmlContent = $request->body();
            $response = array(
                'meta' => array_merge(
                    $this->getLinkInfo($URL),
                    $this->getMetaFromHTML($htmlContent)
                )
            );

            if (!trim($response['meta']['title']) && !trim($response['meta']['description'])) {
                $response['message'] = 'Данные не найдены';
            } else {
                $response['success'] = 1;
            }
        }

        finish:
        return $response;
    }

    /**
     * Gets information about link : params, path and so on
     *
     * @param $URL
     *
     * @return array
     */
    private function getLinkInfo($URL)
    {
        $URLParams = parse_url($URL);

        return [
            'linkUrl' => $URL,
            'linkText' => Arr::get($URLParams, 'host') . Arr::get($URLParams, 'path', ''),
        ];
    }

    /**
     * Parses DOM Document
     *
     * @param $html
     *
     * @return array
     */
    private function getMetaFromHTML($html)
    {
        $DOMdocument = new DOMDocument();
        @$DOMdocument->loadHTML($html);
        $DOMdocument->preserveWhiteSpace = false;

        $nodes = $DOMdocument->getElementsByTagName('title');

        if ($nodes->length > 0) {
            $title = $nodes->item(0)->nodeValue;
            $title = utf8_decode($title);
        }

        $description = "";
        $keywords = "";
        $image = "";

        $metaData = $DOMdocument->getElementsByTagName('meta');

        for ($i = 0; $i < $metaData->length; $i++) {
            $data = $metaData->item($i);

            if ($data->getAttribute('name') == 'description') {
                $description = $data->getAttribute('content');
                $description = utf8_decode($description);
            }

            if ($data->getAttribute('name') == 'keywords') {
                $keywords = $data->getAttribute('content');
                $keywords = utf8_decode($keywords);
            }

            if ($data->getAttribute('property') == 'og:image') {
                $image = $data->getAttribute('content');
            }
        }

        if (empty($image)) {
            $images = $DOMdocument->getElementsByTagName('img');

            if ($images->length > 0) {
                $image = $images->item(0)->getAttribute('src');
            }
        }

        return [
            'image' => isset($image) ? $image : '',
            'title' => isset($title) ? $title : '',
            'description' => isset($description) ? $description : '',
        ];
    }
}
