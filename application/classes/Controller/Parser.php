<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Parser extends Controller_Base_preDispatch {

    public function action_get_page()
    {
        $url = Arr::get($_GET, 'url', '');
        $response = array("result" => "error");

        if ($url)
        {
            $page   = self::htmlRequest($url);

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);

            $doc->recover = true;
            $doc->strictErrorChecking = false;

            $encoding = mb_detect_encoding($page);
            $doc->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', $encoding));

            libxml_clear_errors();

            $return = [
                'title'     => self::getTitle($doc),
                'article'   => self::getArticleText($doc)
                ];   

            if ($return) {
                $response['result']     = 'ok';
                $response['title']      = $return['title'];
                $response['article']    = $return['article'];  
            }
            
            $this->auto_render = false;
            $this->response->headers('Content-Type', 'application/json; charset=utf-8');
            $this->response->body( @json_encode($response) );
        }
    }

    /**
    * Получаем код страницы
    */
    public function htmlRequest($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:31.0) Gecko/20100101 Firefox/31.0 " );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        #var_dump(curl_getinfo($ch, CURLINFO_CONTENT_TYPE));

        curl_close($ch);

        return $result;
    }

    /**
    * Получаем заголовок страницы
    */
    public function getTitle($doc)
    {
        #var_dump($doc);

        $pageTitle  = '';

        $h1         = $doc->getElementsByTagName('h1');
        $title      = $doc->getElementsByTagName('title');

        /** получаем h1 или title */
        if ($h1->length){

            $pageTitle = $h1->item(0)->nodeValue;

        } elseif ($title->length) {

            $pageTitle = $title->item(0)->nodeValue;
        }

        $pageTitle = trim($pageTitle);

        return $pageTitle;
    }

    /*
    * Получаем тело страницы
    */
    public function _getBody($doc)
    {
        $pageArticle    = '';
        $article        = $doc->getElementsByTagName('article');

        if ($article->length) {
            $pageArticle = $article->item(0)->nodeValue;
        }

        $pageArticle = trim($pageArticle);

        return $pageArticle;
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
        $parents = array();

        for ($i = 0; $i < $paragraphs->length; $i++) {

            $parentNode = $paragraphs->item($i)->parentNode;

            /** Compose node text-identifier looks like 'TAGNAME@classname' */
            $parentNodeIdentifier = self::getNodeIdentifier($parentNode);

            if ( !isset($parents[$parentNodeIdentifier]) ){

                $parents[$parentNodeIdentifier] = array(
                    'node'   => $parentNode,
                    'childs' => 1
                );

            } else {

                $parents[$parentNodeIdentifier]['childs']++;

            }

        }

        /**
        * Now, get parent-node with maximum paragraphs count
        * It might be an article we look for.
        */
        $maximumParagraphsCount    = 0;
        $nodeWithMaximumParagraphs = null;

        foreach ($parents as $item) {
            if ($item['childs'] > $maximumParagraphsCount) {
                $maximumParagraphsCount    = $item['childs'];
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
    * @todo add another attributes. Many elements can be without classname
    * @return string tagname@classname. Example: 'DIV@article_content'
    */
    private static function getNodeIdentifier(DOMNode $node)
    {
        $tagName   = $node->nodeName;
        $className = '';

        if ( $classAttr = $node->attributes->getNamedItem('class') ){
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
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;

    }

}