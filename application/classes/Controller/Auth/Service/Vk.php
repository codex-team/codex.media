<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Service_Vk{

    protected static $_instance;
    protected $config;
    protected $session;

    protected $access_token;
    protected $user_id;

    /**
     * Singelton pattern
     *
     * @return type Vk
     */
    /*public static function instance()
    {
        if(!isset(Controller_Auth_Vk::$_instance))
        {
            $config = Kohana::$config->load('vk');

            Vk::$_instance = new Controller_Auth_Vk($config);
        }
        return Vk::$_instance;
    }*/
    /**
     * Загрузка сессии и конфигурации
     *
     * @return type void
     */
    public function __construct($config = array())
    {
        $config = Kohana::$config->load('social');

        $this->config = $config->vk;
        $this->session = Session::instance();
    }
    /**
     * Генерирует ссылку для перехода к авторизации
     *
     * @return type String
     */
    public function get_link_login($redirect_url = '')
    {
        $array = array(
            '{CLIENT_ID}'       => $this->config['VK_APP_ID'],
            '{SCOPE}'           => implode(',',$this->config['SCOPE']),
            '{DISPLAY}'         => $this->config['DISPLAY'],
            '{REDIRECT_URI}'    => ($redirect_url ? $redirect_url : $this->config['REDIRECT_URI'])
        );
        return strtr($this->config['VK_URI_AUTH'],$array);
    }
    /**
     * Получение ACCESS TOKEN для дальнейших выполнения запросов к API
     *
     * @return type Boolean
     */
    protected function get_access_token($redirect_url = '')
    {
        $uri = Arr::get($_SERVER,'QUERY_STRING',NULL);
        parse_str($uri);
        if(!isset($error))
        {
            $array = array(
                '{CLIENT_ID}'       => $this->config['VK_APP_ID'],
                '{APP_SECRET}'      => $this->config['VK_APP_SECRET'],
                '{REDIRECT_URI}'    => $redirect_url ? $redirect_url : $this->config['REDIRECT_URI'],
                '{CODE}'            => $code
            );
            $url = strtr($this->config['VK_URI_ACCESS_TOKEN'],$array);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $json = curl_exec($ch);

            curl_close($ch);

            $result = json_decode($json);

            if(isset($result->error))
            {
                //throw new Kohana_Exception('Ошибка получения Access Token Error: '.$result->error.' , Description: '.$result->error_description);
            }else{
                Cookie::set('VK_ACCESS_TOKEN',$result->access_token,$result->expires_in);
                Cookie::set('VK_EXPIRES_OUT',intval($result->expires_in + time()));
                Cookie::set('VK_USER_ID',$result->user_id,$result->expires_in);

                $this->session->set('VK_ACCESS_TOKEN',$result->access_token);
                $this->session->set('VK_EXPIRES_OUT',intval($result->expires_in + time()));
                $this->session->set('VK_USER_ID',$result->user_id);

                return TRUE;
            }
        }else{
            //throw new Kohana_Exception('Ошибка Error: '.$error.' , Description: '.$error_description);
        }
    }
    /**
     * Проверка авторизован или нет
     * @return type Boolean
     */
    public function logged_in()
    {
        return $this->get_user();
    }
    /**
     * Получение ID юзера в контакте
     *
     * @return type Array
     */
    public function get_user()
    {
        if($this->session->get('VK_EXPIRES_OUT',FALSE))
        {
            $VK_EXPIRES_OUT = $this->session->get('VK_EXPIRES_OUT');
        }elseif(Cookie::get('VK_EXPIRES_OUT',FALSE))
        {
            $VK_EXPIRES_OUT = Cookie::get('VK_EXPIRES_OUT');
        }else{
            $VK_EXPIRES_OUT = 0;
        }
        if(time() <= $VK_EXPIRES_OUT)
        {
            if($this->session->get('VK_ACCESS_TOKEN',FALSE))
            {
                $this->access_token = $this->session->get('VK_ACCESS_TOKEN');
            }elseif(Cookie::get('VK_ACCESS_TOKEN',FALSE))
            {
                $this->access_token = Cookie::get('VK_ACCESS_TOKEN');
            }else{
                $this->access_token = FALSE;
            }
            if($this->session->get('VK_USER_ID',FALSE))
            {
                $this->user_id = $this->session->get('VK_USER_ID');
            }elseif(Cookie::get('VK_USER_ID',FALSE))
            {
                $this->user_id = Cookie::get('VK_USER_ID');
            }else{
                $this->user_id = FALSE;
            }
            if($this->access_token && $this->user_id)
            {
                return array('VK_ACCESS_TOKEN' => $this->access_token, 'VK_USER_ID' => $this->user_id);
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    /**
     * Авторизация
     *
     * @return type Boolean
     */
    public function login($redirect_url = '')
    {
        return $this->get_access_token($redirect_url);
    }
    /**
     * Сброс авторизации
     *
     * @return type Boolean
     */
    public function logout()
    {
        $this->session->delete('VK_ACCESS_TOKEN');
        $this->session->delete('VK_USER_ID');
        $this->session->delete('VK_EXPIRES_OUT');

        Cookie::delete('VK_ACCESS_TOKEN');
        Cookie::delete('VK_USER_ID');
        Cookie::delete('VK_EXPIRES_OUT');

        return !$this->logged_in();
    }
    /**
     * Метод для обращения к API
     * Example
     * $vk = Vk::instance();
     * $result = $vk->api('getProfiles',array('uids'=> 'XXXXXX','fields'=>'first_name,last_name,nickname')); // XXXXXX - ID пользователя в контакте
     * @param type $method String
     * @param type $parametrs Array
     * @return type Object stdClass
     */
    public function api($method = FALSE, $parametrs = array())
    {
        $array = array(
            '{METHOD_NAME}' => $method,
            '{PARAMETERS}' => $this->attr($parametrs),
            '{ACCESS_TOKEN}' => $this->access_token
        );
        $url = strtr($this->config['VK_URI_METHOD'],$array);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($json);
        if(isset($result->response))
        {
            return $result->response;
        }else{

        }

    }

    protected function attr($array = array())
    {
        $params = '';
        if(!empty($array))
        {
            foreach($array as $key=>$val)
            {
                $params .= $key.'='.$val.'&';
            }
        }
        return substr($params,0,-1);
    }



}