<?php

/**
 * Class for create posts on public page's wall
 *
 * Read this before using:
 * https://github.com/codex-team/codex.edu/issues/119#issuecomment-296349880
 *
 * @author Taly Guryn https://github.com/talyguryn
 */
class Model_Services_Vk extends Model_preDispatch
{
    const URLS = array(
        'wall.post'    => 'https://api.vk.com/method/wall.post',
        'wall.edit'    => 'https://api.vk.com/method/wall.edit',
        'wall.delete'  => 'https://api.vk.com/method/wall.delete',
        'wall.restore' => 'https://api.vk.com/method/wall.restore',
    );

    /** Config params for requests */
    private $groupId;
    private $adminKey;

    private $isConfigOk = 0;

    /** Pair */
    private $postId    = 0;
    private $articleId = 0;

    /**
     * This model combines artile's id and post's id on the public wall
     *
     * @param integer $articleId
     */
    public function __construct($articleId = 0)
    {
        $this->isConfigOk = $this->loadConfig();

        if ( !$this->isConfigOk ) {
            return false;
        }

        $this->articleId = $articleId;
        $this->postId = self::getPostIdByArticleId($articleId);
    }

    /**
     * Load params from config file
     */
    private function loadConfig()
    {
        $configFilename = 'communities';
        $config = Kohana::$config->load($configFilename);

        /** Check is config exist. If it doesn't then do nothing */
        if ( empty((array) $config) ) {
            return false;
        }

        /** If config doesn't contain params for vk */
        if (!property_exists($config, 'vk')) {

            throw new Kohana_Exception("No configuration for VK was found in $configFilename config file!");

            return false;
        }

        /** Trying to get params */
        $this->groupId  = Arr::get($config->vk, 'group_id', '');
        $this->adminKey = Arr::get($config->vk, 'admin_key', '');

        if (!$this->groupId || !$this->adminKey) {

            throw new Kohana_Exception("Invalid configuration of $configFilename config file ");

            return false;
        }

        /** All right */
        return true;
    }

    /**
     * Create post on the wall
     *
     * $values['text'] — New text for the post
     * $values['link'] — Attache link
     *
     * @param  array $values
     * @return integer $post_id — new post's id or 0
     */
    public function post($values = array())
    {
        $params = array(
            'message'       => $values['text'],
            'owner_id'      => $this->groupId,
            'from_group'    => 1,
            'access_token'  => $this->adminKey,
            'attachments'   => $values['link']
        );

        $url = self::URLS['wall.post'];

        $response = $this->sendRequest($url, $params);

        if ($response) {

            $this->postId = $response->post_id;

            $this->addToFeed();

            return $this->postId;
        }

        return 0;
    }

    /**
     * Edit post on the wall
     *
     * $values['text'] — New text for the post
     * $values['link'] — Attache link
     *
     * @param  array $values
     * @return boolean — result
     */
    public function edit($values = array())
    {
        if (!$this->postId) return true;

        $params = array(
            'message'       => $values['text'],
            'owner_id'      => $this->groupId,
            'post_id'       => $this->postId,
            'access_token'  => $this->adminKey,
            'attachments'   => $values['link']
        );

        $url = self::URLS['wall.edit'];

        $response = $this->sendRequest($url, $params);

        return (boolean) $response;
    }

    /**
     * Delete post from the public's wall
     *
     * @return boolean — result
     */
    public function delete()
    {
        if (!$this->postId) return true;

        $params = array(
            'owner_id'      => $this->groupId,
            'post_id'      => $this->postId,
            'access_token'  => $this->adminKey
        );

        $url = self::URLS['wall.delete'];

        $response = $this->sendRequest($url, $params);

        if ($response) {
            $this->removeFromFeed();
        }

        return (boolean) $response;
    }

    /**
     * sendRequest - send post request to $url with $params
     *
     * @param  string $url    — Url for this request
     * @param  array  $params — POST params
     * @return array          — decoded json positive response or false on error
     */
    private function sendRequest($url = '', $params = array())
    {
        if ( !$this->isConfigOk ) {
            return false;
        }

        $response = Model_Methods::sendPostRequest($url, $params);

        $response = json_decode($response);

        /** Good, we've got a positive response */
        if (property_exists($response, 'response')) {

            return $response->response;

        }
        /***/

        /** Bad. Need to pack response from vk to show error */
        $response = mb_convert_encoding(json_encode($response, JSON_UNESCAPED_UNICODE), 'cp1251', 'utf8');

        throw new Kohana_Exception("Error while trying to use vk api.\nVK response: " . $response);

        return false;
        /***/
    }

    /**
     * Save pade_id from vk wall for current article to Redis list
     */
    private function addToFeed()
    {
        $feed = new Model_Feed_VkPosts();
        $feed->add($this->articleId, $this->postId);
    }

    /**
     * Remove pade_id from Redis list
     */
    private function removeFromFeed()
    {
        $feed = new Model_Feed_VkPosts();
        $feed->remove($this->articleId);
    }

    /**
     * Check is this post for this article exist on the public's wall
     *
     * @param  string  $articleId — article->id
     * @return integer $post_id   — post's id or 0 if no post for the article exists
     */
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
