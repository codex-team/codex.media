<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  Class for work with vk.api
 *  - get code
 *  - get token
 *  - get userinfo (static fields)
 *
 *  @author Demyashev Alexander
 */

class Model_Social_Vk extends Model_preDispatch
{
    /**
     *  Static strings to forming full uri
     *
     *  @var string $url_auth
     *  @var string $url_token
     *  @var string $url_method
     */
    private $url_auth = "https://oauth.vk.com/authorize";
    private $url_token = "https://oauth.vk.com/access_token";
    private $url_method = "https://api.vk.com/method/";

    /**
     *  HTTPS or HTTP protocol
     *
     *  @var int $https # 0 or 1
     */
    private $https = 1;

    /**
     *  Contain token after auth with code
     *
     *  @var string $token
     */
    private $token;

    /**
     *  Make and load URL to catch code for auth
     *
     *  @param string $state # login, attach or remove vk profile
     */
    public function getCode($state)
    {
        $url = $this->getAuthUri($state);

        header("Location: {$url}");
        exit();
    }

    /**
     *  Get token and some std user info
     *
     *  @param  string $code
     *
     *  @return object $response
     */
    public function auth($code)
    {
        $response = $this->exec($this->getTokenUri($code));

        $this->token = $response->access_token;

        return $response;
    }

    /**
     *  Get user info by static fields
     *
     *  @param  string $id # user id
     *
     *  @return object
     */
    public function getUserInfo($id)
    {
        $params = [
            'user_ids' => $id,
            'fields' => 'photo_50,photo_100,photo_max,domain,maiden_name,first_name,last_name',
            'name_case' => 'nom'
        ];

        return $this->method('users.get', $params);
    }

    /**
     *  Make uri and exec him by method name and params
     *
     *  @param  string $method
     *  @param  array  $params # fields after url
     *
     *  @return object
     */
    private function method($method, $params)
    {
        $uri = $this->getMethodUri($method, $params);

        return current($this->exec($uri)->response);
    }

    /**
     *  Create url for get code
     *
     *  @param  string $state # login, attach or remove profile. Used in controller
     *
     *  @return string
     */
    private function getAuthUri($state = '')
    {
        $settings = Kohana::$config->load('social.vk');

        return "{$this->url_auth}?" .
            "client_id={$settings['client_id']}" .
            "&scope={$settings['scopes']}" .
            "&redirect_uri={$settings['redirect_uri']}" .
            "&display=page" .
            "&response_type=code" .
            "&https=1" .
            "&v=5.40" .
            "&https={$this->https}" .
            "&state={$state}";
    }

    /**
     *  Create url for get token
     *
     *  @param  string $code
     *
     *  @return string
     */
    private function getTokenUri($code = null)
    {
        $settings = Kohana::$config->load('social.vk');

        if ($code === null) {
            throw new Exception("Code not setted");
        } else {
            return "{$this->url_token}?" .
                "client_id={$settings['client_id']}" .
                "&client_secret={$settings['client_secret']}" .
                "&redirect_uri={$settings['redirect_uri']}" .
                "&code={$code}" .
                "&https={$this->https}";
        }
    }

    /**
     *  Execute some url
     *
     *  @param  string $uri
     *
     *  @return object
     */
    private function exec($uri)
    {
        $response = file_get_contents($uri);

        return json_decode($response);
    }

    /**
     *  Create url uses method name + params
     *
     *  @param  string $method
     *  @param  array  $params
     *
     *  @return string
     */
    private function getMethodUri($method, $params)
    {
        $parameters = http_build_query($params);

        return "{$this->url_method}{$method}?{$parameters}" .
            "&access_token={$this->token}" .
            "&https={$this->https}";
    }
}
