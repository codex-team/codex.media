<?php defined('SYSPATH') or die('No direct script access.');

class Model_Social_Vk extends Model_preDispatch
{
    private $url_auth   = "https://oauth.vk.com/authorize";
    private $url_token  = "https://oauth.vk.com/access_token";
    private $url_method = "https://api.vk.com/method/";
    private $https      = 1;

    private $token;

    public function getCode($state) {
        $url = $this->getAuthUri($state);

        header("Location: {$url}");
        exit();
    }

    public function auth($code)
    {
        $response = $this->exec( $this->getTokenUri( $code ) );

        $this->token = $response->access_token;

        return $response;
    }

    public function getUserInfo($id)
    {
        $params = array(
            'user_ids' => $id,
            'fields' => 'photo_50,photo_100,photo_max,domain,maiden_name,first_name,last_name',
            'name_case' => 'nom'
        );

        return $this->method('users.get', $params);
    }

    private function method($method = NULL, $params)
    {
        $uri = $this->getMethodUri( $method, $params );

        return current( $this->exec( $uri )->response );
    }

    private function getAuthUri($state='') {

        $settings = Kohana::$config->load('social.vk');

        return "{$this->url_auth}?".
            "client_id={$settings['client_id']}".
            "&scope={$settings['scopes']}" . 
            "&redirect_uri={$settings['redirect_uri']}" .
            "&display=page" . 
            "&response_type=code".
            "&https=1".
            "&v=5.40".
            "&https={$this->https}" .
            "&state={$state}"; 
    }

    private function getTokenUri($code = NULL) {

        $settings = Kohana::$config->load('social.vk');

        if ($code === NULL) throw new Exception("Code not setted");
        else {
            return "{$this->url_token}?" .
                "client_id={$settings['client_id']}" .
                "&client_secret={$settings['client_secret']}" .
                "&redirect_uri={$settings['redirect_uri']}" .
                "&code={$code}".
                "&https={$this->https}";
        }
    }

    private function exec( $uri ) {
        $response = file_get_contents($uri);

        return json_decode($response);
    }

    private function getMethodUri( $method, $params ) {
        $parameters = http_build_query($params);

        return "{$this->url_method}{$method}?{$parameters}".
            "&access_token={$this->token}".
            "&https={$this->https}";
    }
}