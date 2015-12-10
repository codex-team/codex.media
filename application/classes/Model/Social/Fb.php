<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  Class for work with facebook api
 *  @author Alexander Demyashev
 */
class Model_Social_Fb extends Model_preDispatch
{
    /**
     *  Contain static url for creating auth dialog uri with parameters
     *  @var string $url_dialog
     */
    private $url_dialog = "https://www.facebook.com/dialog/oauth";
    private $url_token  = "https://graph.facebook.com/v2.3/oauth/access_token";
    private $url_user   = "https://graph.facebook.com/me";

    /**
     *  Create uri for auth with fb
     *  @return string
     */
    public function auth($state) {

        $settings = Kohana::$config->load('social.facebook');

        $uri = "{$this->url_dialog}" .
            "?client_id={$settings['client_id']}" .
            "&redirect_uri={$settings['redirect_uri']}".
            "&state={$state}" .
            "&response_type={$settings['response_type']}".
            "&scope={$settings['scope']}";

        header("Location: {$uri}");
        exit();
    }

    public function getToken($code) {

        $settings = Kohana::$config->load('social.facebook');
        
        $uri = "{$this->url_token}" .
            "?client_id={$settings['client_id']}" .
            "&redirect_uri={$settings['redirect_uri']}".
            "&client_secret={$settings['client_secret']}" .
            "&code={$code}";

        return $this->exec($uri);
    }

    public function getUser($token) {
        $fields = "id,name,email,picture,domains";
        $uri = "{$this->url_user}?fields={$fields}&access_token={$token}";

        $userdata = $this->exec($uri);

        foreach (array('100', '200', '500') as $size) {
            $pictures[$size] = "https://graph.facebook.com/{$userdata->id}/picture?height={$size}&width={$size}";
        }

        $userdata = (array) $userdata;
        $userdata['picture'] = $pictures;

        return (object) $userdata;
    }

    private function exec($uri) {
        $response = file_get_contents($uri);

        return json_decode($response);
    }
}