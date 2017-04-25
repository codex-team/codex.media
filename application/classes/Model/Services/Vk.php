<?php

/**
 * Class for create posts on public page's wall
 *
 * @author Taly Guryn https://github.com/talyguryn
 */
class Model_Services_Vk extends Model_preDispatch
{
    const URLS = array(
        "wall.post"    => "https://api.vk.com/method/wall.post",
        "wall.edit"    => "https://api.vk.com/method/wall.edit",
        "wall.delete"  => "https://api.vk.com/method/wall.delete",
        "wall.restore" => "https://api.vk.com/method/wall.restore",
    );

    private $groupId;
    private $adminKey;

    private $postId    = 0;
    private $articleId = 0;

    private $isConfigOk = 0;


    public function __construct($articleId = 0)
    {
        $this->isConfigOk = $this->loadConfig();

        if ( !$this->isConfigOk ) {
            return false;
        }

        $this->articleId = $articleId;
        $this->postId = self::getPostIdByArticleId($articleId);
    }

    private function loadConfig()
    {
        $configFilename = 'social-public-pages-keys';
        $config = Kohana::$config->load($configFilename);

        /** Check is config exist. If it doesn't then do nothing */
        if ( empty((array) $config) ) {
            return false;
        }

        if (!property_exists($config, 'vk')) {
            throw new Kohana_Exception("No $configFilename config file was found!");

            return false;
        }

        $this->groupId  = Arr::get($config->vk, "group_id", "");
        $this->adminKey = Arr::get($config->vk, "admin_key", "");

        if (!$this->groupId || !$this->adminKey) {
            throw new Kohana_Exception("Invalid configuration of $configFilename config file ");

            return false;
        }

        return true;
    }

    public function post($text)
    {
        // if ($this->postId) {
        //     return $this->restore();
        // }

        $params = array(
            "message"       => $text,
            "owner_id"      => $this->groupId,
            "from_group"    => 1,
            "access_token"  => $this->adminKey
        );

        $url = self::URLS["wall.post"];

        /**
         * @return post_id of false
         */
        $response = $this->sendRequest($url, $params);

        if ($response) {

            $this->postId = $response->post_id;

            $this->addToFeed();

            return $this->postId;
        }

        return false;
    }

    public function edit($text)
    {
        if (!$this->postId) return true;

        $params = array(
            "message"       => $text,
            "owner_id"      => $this->groupId,
            "post_id"       => $this->postId,
            "access_token"  => $this->adminKey
        );

        $url = self::URLS["wall.edit"];

        /**
         * @return 1 or false
         */
        $response = $this->sendRequest($url, $params);

        return $response;
    }

    public function delete()
    {
        if (!$this->postId) return true;

        $params = array(
            "owner_id"      => $this->groupId,
            "post_id"       => $this->postId,
            "access_token"  => $this->adminKey
        );

        $url = self::URLS["wall.delete"];

        /**
         * @return 1 or false
         */
        $response = $this->sendRequest($url, $params);

        if ($response) {
            $this->removeFromFeed();
        }

        return $response;
    }

    // public function restore()
    // {
    //     if (!$this->postId) return true;
    //
    //     $params = array(
    //         "owner_id"      => $this->groupId,
    //         "post_id"       => $this->postId,
    //         "access_token"  => $this->adminKey
    //     );
    //
    //     $url = self::URLS["wall.restore"];
    //
    //     /**
    //      * @return 1 or false
    //      */
    //     $response = $this->sendRequest($url, $params);
    //
    //     return $response;
    // }

    /**
     * sendRequest - send post request to $url with $params
     *
     * @param  {String} $url
     * @param  {Array}  $params
     * @return {Array}            decoded json positive response or false on error
     */
    private function sendRequest($url = "", $params = array())
    {
        if ( !$this->isConfigOk ) {
            return false;
        }

        $response = Model_Methods::sendPostRequest($url, $params);

        $response = json_decode($response);

        /** get positive response */
        if (property_exists($response, "response")) {

            return $response->response;

        }
        /***/

        /** pack response from vk to show error */
        $response = mb_convert_encoding(json_encode($response, JSON_UNESCAPED_UNICODE), 'cp1251', 'utf8');

        throw new Kohana_Exception("Error while trying to use vk api.\nVK response: " . $response);

        return false;
    }

    private function addToFeed()
    {
        $feed = new Model_Feed_VkPosts();
        $feed->add($this->articleId, $this->postId);
    }

    private function removeFromFeed()
    {
        $feed = new Model_Feed_VkPosts();
        $feed->remove($this->articleId);
    }

    public static function getPostIdByArticleId($articleId = 0)
    {
        if (!$articleId) {
            return 0;
        }

        $feed = new Model_Feed_VkPosts();
        $post_id = (int) $feed->getScore($articleId);

        return $post_id;
    }
}
