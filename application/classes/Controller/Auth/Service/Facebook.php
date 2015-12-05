<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Service_Facebook
{
    protected $session;

    protected $oAppId;
    protected $oAppSecret;
    protected $oRedirectUri;
    protected $oCode = NULL;

    protected $oInstance = NULL;

    public function __construct()
    {
        $config = Kohana::$config->load('social_spark');
        $this->config = $config->facebook;

        $this->oAppId = $this->config['appId'];
        $this->oAppSecret = $this->config['secret'];
        $this->oRedirectUri = $this->config['next'];

        $this->session = Session::instance();
    }

    static public function GetInstance() {
        if(self::$oInstance == NULL) {
            self::$oInstance = new self;
        }
        return self::$oInstance;
    }

    protected function getCode() {

        if(isset($_REQUEST["code"])) {
            $this->oCode = $_REQUEST["code"];
        }

    }

    public function getLink($link = '') {

        $this->getCode();

        if(!$this->oCode) {
            $redirect = $link ? urlencode($link) : urlencode($this->oRedirectUri);
            $this->session->set('state', md5(uniqid(rand(), TRUE)));
            $oDialogUrl = "http://www.facebook.com/dialog/oauth?client_id=" . $this->oAppId . "&redirect_uri=" . $redirect . "&state=" . $this->session->get('state');
            return $oDialogUrl;
        }

    }

    public function getUser($link = '') {
        if(isset($_REQUEST['state']) and $this->session->get('state')) {
            if($_REQUEST['state'] == $this->session->get('state')) {
                $redirect = $link ? urlencode($link) : urlencode($this->oRedirectUri);
                $this->getCode();
                $tokenUrl = "https://graph.facebook.com/oauth/access_token?client_id=" . $this->oAppId . "&redirect_uri=" . $redirect . "&client_secret=" . $this->oAppSecret . "&code=" . $this->oCode;
                $oResponse = file_get_contents($tokenUrl);
                $params = NULL;
                parse_str($oResponse, $params);
                $graphUrl = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
                $oUser = json_decode(file_get_contents($graphUrl));
                return $oUser;
            }

        } else {
            return false;
        }
    }

}