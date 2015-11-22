<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Login extends Controller_Base_preDispatch {

    const LOGIN_MAX_TRYING     = 5;
    const LOGIN_PASSWORD_SALT1 = 'dfhgga23';
    const LOGIN_PASSWORD_SALT2 = 'dfhshgf2';

    public function before()
    {
        parent::before();
        $this->social = Kohana::$config->load('social');
    }

    public function action_signup()
    {

        self::oldCheckInvite();

        if ($this->user->id) {
            $this->redirect('/user/'.$this->user->id);
        }

        if ($this->checkUserSignup()) {

            $email     = Arr::get($_POST, 'email');
            $real_name = Arr::get($_POST, 'real_name');
            $password  = Arr::get($_POST, 'password');

            $user_password = sha1(self::LOGIN_PASSWORD_SALT1 . $password . self::LOGIN_PASSWORD_SALT2);

            $userId = Dao_User::insert()
                ->set('email', $email)
                ->set('password2', $user_password)
                ->set('real_name', $real_name)
                ->execute();

            if ($userId) {
                $this->user->setAuthCookie($userId);
                $this->redirect('/');
                return;
            }

        }

        $this->template->title = 'Регистрация';
        $this->view['social_params'] = array();

        $this->template->content = View::factory('/templates/auth/signup', $this->view);
    }

    public function action_login()
    {
        $this->template->title = 'Вход';
        
        if ($this->user->id) $this->redirect('/');



        $this->view['trying'] = self::LOGIN_MAX_TRYING;



        $session  = Session::instance();
        $redirect = Arr::get($_GET, 'redirect');
        
        if ($redirect) $session->set('redirect', $redirect);

        

        $this->view['login_trying'] = (int)$this->memcache->get('error_login:'.Request::$client_ip, 0);

        if ( self::checkUserLogin() ) {

            $email         = $_POST['email'];
            $password      = $_POST['password'];
            
            $hash_password = sha1(self::LOGIN_PASSWORD_SALT1 . $password . self::LOGIN_PASSWORD_SALT2);            
            
            $find_user     = Dao_User::select()->where('email', $email)->where('password', $hash_password)->execute();

            if ($find_user) {
                $this->user->setAuthCookie($find_user['id']);
                $this->redirect('/');
                return;

            } else {

                $this->view['error_text'] = 'Email или пароль не верный';

                $this->view['login_trying']++;

                if ($this->view['login_trying']) {
                    $this->memcache->set('error_login:'.Request::$client_ip, $this->view['login_trying'], Date::MINUTE*3);
                } else {
                    $this->memcache->set('error_login:'.Request::$client_ip, 1, Date::MINUTE*3);
                }

                if ($this->view['login_trying'] > self::LOGIN_MAX_TRYING) {
                    $this->memcache->set('error_login_time:'.Request::$client_ip, time() + Date::MINUTE*3);
                    $this->redirect('login');
                    return;
                }

            }

        }

        $this->view['error_time'] = $this->memcache->get('error_login_time:'.Request::$client_ip, time());
        $this->template->content = View::factory('/templates/auth/login', $this->view);
    }

    public function action_callback()
    {

        $session = Session::instance();

        $linking = Arr::get($_GET, 'linking'); // если пытаемся привязать к аккаунту
        $provider = $this->request->param('provider');

        if ($provider) {

            $account = self::checkSocialCallback($provider);

            if ($account) {

                $account_id   = $account['id'];
                $screen_name  = $account['screen_name'];
                $real_name    = $account['real_name'];
                $photo        = $account['photo'];
                $photo_medium = $account['photo_medium'];
                $photo_big    = $account['photo_big'];
                $stats_id     = $account['stats_id'];

                if ($linking) {
                    if (self::callbackLinking($provider, $account_id, $screen_name)) {
                        $this->redirect('user');return;
                    }
                } else {

                    // Если нашли пользователя с привязанной соц. сетью
                    $find_user = Dao_User::select()->where($provider, $account_id)->execute();
                    if ($find_user) {

                        $find_user_id = $find_user['id'];
                        $this->user->setAuthCookie($find_user_id);
                        $this->user->getUserInfo($find_user_id, true);

                        $redirect = $session->get('redirect');
                        if ($redirect) {
                            $session->delete('redirect');
                        } else {
                            $redirect = '/';
                        }
                        $this->redirect($redirect);

                    } else { // если соц сеть не привязанна к пользователю то регистрируем нового пользователя

                        $userId = Dao_User::insert()
                            ->set('real_name', $real_name)
                            ->set('photo', $photo)
                            ->set('photo_medium', $photo_medium)
                            ->set('photo_big', $photo_big)
                            ->set($provider, $account_id)
                            ->set($provider . '_name', $screen_name)
                            ->execute();

                        if ($userId) {
                            $this->user->setAuthCookie($userId);
                            $this->redirect('/');
                            return;
                        }

                    }

                }
            }

        }

        $this->redirect('login');

    }

    public function action_social()
    {
        $provider = $this->request->param('provider');

        switch($provider) {
            case 'vk':       self::socialVkLogin();break;
            case 'twitter':  self::socialTwitterLogin();break;
            case 'facebook': self::socialFacebookLogin();break;
            default:         break;
        }

        $this->redirect('login');
    }

    public function action_linking()
    {
        $provider = $this->request->param('provider');

        if ($token = Arr::get($_GET, 'token')) {
            if ($token == sha1($this->user->id .'link'. $this->user->real_name . $provider . $this->user->email)) {
                switch($provider) {
                    case 'vk':       self::socialVkLogin(true);break;
                    case 'twitter':  self::socialTwitterLogin(true);break;
                    case 'facebook': self::socialFacebookLogin(true);break;
                    default:         break;
                }
            }
        }

        $this->redirect('user');

    }

    public function action_unlinking()
    {
        $provider = $this->request->param('provider');

        //$this->user->getUserInfo($this->user->id, true); // для теста

        if ($token = Arr::get($_GET, 'token')) {
            if ($token == sha1($this->user->id .'unlink'. $this->user->real_name .$provider. $this->user->email)) {

                $social_count = (int)(bool)$this->user->twitter + (int)(bool)$this->user->vk + (int)(bool)$this->user->facebook;

                if (!$this->user->checkEmailOnEmpty() || $social_count > 1) {
                    Dao_User::update()->set($provider, '')->set($provider . '_name', '')->where('id',$this->user->id)->execute();
                    $this->user->getUserInfo($this->user->id, true);
                } else {
                    $this->redirect("user?error=3&provider=$provider");return;
                }

            }
        }

        $this->redirect('user');
    }

    public function action_logout()
    {
        Cookie::delete('uid');
        Cookie::delete('hr');

        $this->redirect('login');
    }

    public function action_recover()
    {
        if ($this->user->id) {
            $this->redirect('/');
            return;
        }

        $session = Session::instance();

        if (Arr::get($_GET, 'my')) {

            $find_user_id = abs((int)$session->get('find_user_id'));
            if ($find_user_id) {

                $session->delete('find_user_id');
                self::recoverEmail($find_user_id);
                $this->view['find_user_id'] = $find_user_id;

            }

        }

        $csrf = Arr::get($_POST, 'csrf');
        if (Security::check($csrf)) {

            $email = Arr::get($_POST, 'email');
            if (Valid::email($email)) {

                $find_user = Dao_User::select()->where('email', $email)->execute();
                if ($find_user) {
                    $this->view['find_user'] = $find_user;
                    $session->set('find_user_id', $find_user['id']);
                } else {
                    $data['error_text'] = 'Пользователя с таким адресом не нашлось';
                }

            }
        }

        $this->template->content = View::factory('/templates/auth/recover', $this->view);

    }

    public function action_recoverPassword()
    {
        $hash = $this->request->param('hash');
        if (!$hash) {
            $this->redirect('/');
            return;
        }

        $find_user_redis = $this->redis->get('user:recover:' . $hash);

        if ($find_user_redis) {

            $find_user = unserialize($find_user_redis);
            if ($find_user) {

                $find_user_id = isset($find_user['id']) ? $find_user['id'] : 0;
                if ($find_user_id) {

                    $csrf = Arr::get($_POST, 'csrf');
                    if (Security::check($csrf)) {

                        $password = Arr::get($_POST, 'password');
                        $password_repeat = Arr::get($_POST, 'password2');

                        if ($password && $password_repeat) {
                            if ($password == $password_repeat) {

                                $user_password = sha1(self::LOGIN_PASSWORD_SALT1 . $password . self::LOGIN_PASSWORD_SALT2);

                                $update = Dao_User::update()
                                    ->set('password', '')
                                    ->set('password2', $user_password)
                                    ->where('id', $find_user_id)
                                    ->execute();

                                $this->user->getUserInfo($find_user_id, true);

                                if ($update) {
                                    $this->redis->del('user:recover:' . $hash);
                                    $this->user->setAuthCookie($find_user_id);
                                    $this->redirect('/');
                                }
                            }
                        }
                    }
                }
            }

        }

        $this->template->content = View::factory('/templates/auth/recover_pass', $this->view);
    }

    /*
     * #todo: Внутренние функции
     */

    public function hasUniqueLinking($provider, $id)
    {
        return !((bool)Dao_User::select()->where($provider, $id)->execute());
    }

    protected function checkUserSignup()
    {
        $csrf = Arr::get($_POST, 'csrf');
        if (!$csrf) return false;

        if (!Security::check($csrf)) return false;

        $email = Arr::get($_POST, 'email', '');
        if (!Valid::email($email)) {
            $this->view['error_text'] = 'Неверно заполнено поле "Email"';
            return false;
        }

        $real_name = Arr::get($_POST, 'real_name', '');
        $real_name = strip_tags(preg_replace("/[ ]+/", " ", trim($real_name)));
        if (!self::validText($real_name)) {
            $this->view['error_text'] = 'Недопустимые символы в имени или фамилии';
            return false;
        }

        $password  = Arr::get($_POST, 'password', '');
        if (!$password) {
            $this->view['error_text'] = 'Неверно заполнено поле "Пароль"';
            return false;
        }

        $password_repeat = Arr::get($_POST, 'password2', '');
        if (!$password_repeat) {
            $this->view['error_text'] = 'Неверно заполнено поле "Повторный пароль"';
            return false;
        }

        if ($password != $password_repeat) {
            $this->view['error_text'] = 'Неверно заполнено поле "Повторный пароль"';
            return false;
        }

        Security::token(true);

        if (!$this->user->hasUniqueEmail($email)) {
            $this->view['error_text'] = 'Адрес <b>' . $email . '</b> уже зарегистрирован <a href="/login">Войти на сайт</a>';
            return false;
        }

        $_POST['email'] = $email;
        $_POST['real_name'] = $real_name;
        $_POST['password'] = $password;

        return true;
    }

    protected function checkUserLogin()
    {
        if ($this->view['login_trying'] > self::LOGIN_MAX_TRYING) {
            return false;
        }

        $csrf = Arr::get($_POST, 'csrf');
        if (!$csrf) return false;
        if (!Security::check($csrf)) return false;

        $email = trim(Arr::get($_POST, 'email'));
        if (!Valid::email($email)) {
            $this->view['error_text'] = 'Неверно заполнено поле "Email"';
            return false;
        }

        $password = Arr::get($_POST, 'password');
        if (!$password) {
            $this->view['error_text'] = 'Неверно заполнено поле "Пароль"';
            return false;
        }

        Security::token(true);

        $_POST['email'] = $email;
        $_POST['password'] = $password;

        return true;
    }

    /*
     *  @todo: Приватные методы для внутренних вызовов внутри этого класса
     */

    private function checkSocialCallback($provider, $linking = false)
    {
        $session = Session::instance();

        if ($provider == 'twitter') {

            $config = $this->social->$provider;

            if (isset($_REQUEST['oauth_token']) && $session->get('oauth_token') !== $_REQUEST['oauth_token']) {
                $session->set('oauth_status', 'oldtoken');
            }

            $connection = new Controller_Auth_Service_Twitter(
                $config['CONSUMER_KEY'],
                $config['CONSUMER_SECRET'],
                $session->get('oauth_token'),
                $session->get('oauth_token_secret')
            );

            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

            if ($connection->http_code == 200) {
                $account = $connection->get('account/verify_credentials');
                if ($account) return array(
                    'id'           => $account->id,
                    'screen_name'  => $account->screen_name,
                    'photo'        => $account->profile_image_url,
                    'photo_medium' => str_replace('image_normal', 'image', $account->profile_image_url),
                    'photo_big'    => str_replace('image_normal', 'image', $account->profile_image_url),
                    'stats_id'     => 1
                );
            }

        } elseif ($provider == 'vk') {

            if (!Arr::get($_REQUEST, 'code')) return false;

            $url = $linking ? 'http://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/vk?linking=1' : '';

            $connection = new Controller_Auth_Service_Vk();
            $connection->login($url);
            $social_user = $connection->get_user();
            $social_user_params = $connection->api('users.get',
                array(
                    'uids' => $social_user['VK_USER_ID'],
                    'fields' => 'uid,first_name,last_name,nickname,photo,photo_medium,photo_big,photo_rec,screen_name'
                )
            );

            $account = current($social_user_params);

            if ($account) return array(
                'id'           => $account->uid,
                'real_name'    => $account->first_name . PHP_EOL . $account->last_name,
                'screen_name'  => $account->screen_name,
                'photo'        => $account->photo,
                'photo_medium' => $account->photo_medium,
                'photo_big'    => $account->photo_big,
                'stats_id'     => 2
            );

        } elseif ($provider == 'facebook') {

            if (!Arr::get($_GET, 'code')) return false;

            $url = $linking ? 'http://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/facebook?linking=1' : '';

            $connection = new Controller_Auth_Service_Facebook();
            $account = $connection->getUser($url);

            if ($account) return array(
                'id'           => $account->id,
                'screen_name'  => $account->username,
                'photo'        => 'http://graph.facebook.com/'.$account->id.'/picture?width=200&height=200',
                'photo_medium' => 'http://graph.facebook.com/'.$account->id.'/picture?width=400&height=400',
                'photo_big'    => 'http://graph.facebook.com/'.$account->id.'/picture?width=600&height=600',
                'stats_id'     => 3
            );

        }
        return false;
    }

    private function socialVkLogin($linking = false)
    {
        $linking = $linking ? 'http://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/vk?linking=1' : '';

        $vk = new Controller_Auth_Service_Vk();
        $url = $vk->get_link_login($linking);
        $this->redirect($url);
    }

    private function socialTwitterLogin($linking = false)
    {
        $linking = $linking ? '?linking=1' : '';

        $config = $this->social->twitter;
        $connection = new Controller_Auth_Service_Twitter($config['CONSUMER_KEY'], $config['CONSUMER_SECRET']);
        $request_token = $connection->getRequestToken($config['OAUTH_CALLBACK'] . $linking);

        $session = Session::instance();
        $session->set('oauth_token', $request_token['oauth_token']);
        $session->set('oauth_token_secret', $request_token['oauth_token_secret']);

        if ($connection->http_code == 200) {
            $url = $connection->getAuthorizeURL($request_token);
            $this->redirect($url);
        }
    }

    private function socialFacebookLogin($linking = false)
    {
        $linking = $linking ? 'http://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/facebook?linking=1' : '';

        $facebook = new Controller_Auth_Service_Facebook();
        $url = $facebook->getLink($linking);
        $this->redirect($url);
    }

    private function recoverEmail($userId)
    {
        $user = Dao_User::select()->where('id', $userId)->execute();

        if (!$user) return;

        $email = $user['email'];

        $hash = sha1($userId . $email);

        $recover_params = array(
            'id'    => $userId,
            'mail'  => $email
        );

        $this->redis->set('user:recover:' . $hash, serialize($recover_params));

        $this->view['name'] = $user['real_name'];
        $this->view['email'] = $email;
        $this->view['link'] = 'http://' . Arr::get($_SERVER, 'SERVER_NAME', 'spark.ru') . '/r/' . $hash;

        $subject = 'Востановление пароля от аккаунта на ' . $GLOBALS['SITE_NAME'] ;
        $message = View::factory('/email/recover', $this->view)->render();

        $module_email = Messages::instance('Email');
        $module_email->add_to_queue($subject, $message, $email)->commit()->send();
    }

    private function callbackLinking($provider, $id, $username)
    {
        if (self::hasUniqueLinking($provider, $id)) {
            $update = Dao_User::update()->set($provider, $id)->where('id', $this->user->id)->execute();
            if ($update) {

                if (!$username) $username = $id;

                $update = Dao_User::update()->set($provider . '_name', $username)->where('id', $this->user->id)->execute();
                if ($update) {
                    $this->user->getUserInfo($this->user->id, true);
                    return true;
                }
            }
        } else {
            $this->redirect("user?error=2&provider=$provider");
        }
        return false;
    }

    /*
     * @deprecated Приемка для старого неправильного метода приглашений
     */
    protected function oldCheckInvite()
    {
        if ( $invite_hash = $this->request->param('team') ){
            $keys = $this->redis->keys('TEAM_INVITE:SID:*:EMAIL:'.$invite_hash);
            foreach ($keys as $key) {
                $inviteData = unserialize( $this->redis->get($key) );

                $invite = new Model_Invite();
                $invite->meetUserWithLink( $inviteData['startup'] , $invite_hash );
            }
            $this->redirect('signup');
            return;
        };
    }

}
