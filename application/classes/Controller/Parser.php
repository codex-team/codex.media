<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Parser extends Controller_Base_preDispatch {

    public function action_get_page()
    {
        $url = Arr::get($_GET, 'url', '');

        // ############################################################
        // echo '<form action="/ajax/get_page/" method="GET">';
        // echo '<input type="text" name="url" value="' . $url . '" style="width:100%" />';
        // echo '<button type="submit">load</button>';
        // echo '</form>';
        // echo '<hr>';
        // ############################################################

        if ($url)
        {
            $page   = self::htmlRequest($url);

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);

            $encoding = mb_detect_encoding($page);
            $doc->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', $encoding));

            libxml_clear_errors();
            
            $return = [
                'title'     => self::getTitle($doc),
                'article'   => self::getBody($doc)
                ];

            #var_dump($return);    
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
    public function getBody($doc)
    {
        $pageArticle    = '';
        $article        = $doc->getElementsByTagName('article');

        if ($article->length) {
            $pageArticle = $article->item(0)->nodeValue;
        }

        $pageArticle = trim($pageArticle);

        return $pageArticle;
    }

}    