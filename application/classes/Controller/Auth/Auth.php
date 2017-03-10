<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Auth extends Controller_Auth_Base
{
    /** Where we should redirect user after success authorisation */
    const URL_TO_REDIRECT_AFTER_SUCCES_AUTH = '/';

    /**
    * Auth page.
    * Both 2 form: login and signup handlers here.
    * @author Savchenko Petr (vk.com/specc)
    */
    public function action_auth()
    {
        /** If user is already logged in, redirects to the '/' */
        //if ($this->user->id){ $this->redirect('/user/'.$this->user->id); return; }

        /** Remember $_GET 'redirect' param if need */
        if ($redirect = Arr::get($_GET, 'redirect')) {

            $this->session->set('redirect', htmlspecialchars_decode($redirect));
        }

        /** To handle login/signup form submitting */
        $action        = Arr::get($_POST, 'action');
        $method        = $this->request->param('method');
        $authSucceeded = FALSE;

        if ($action) switch ($action) {

            case 'login' : $authSucceeded = $this->login(); break;
        }

        if ($method) switch ($method) {

            case 'vk' : $authSucceeded = $this->login_vk(); break;
            case 'fb' : $authSucceeded = $this->login_fb(); break;
            case 'tw' : $authSucceeded = $this->login_tw(); break;
        }

        /** Redirect user after succeeded auth */
        if ($authSucceeded) {

            $urlToRedirect = $this->session->get('redirect', self::URL_TO_REDIRECT_AFTER_SUCCES_AUTH);
            $this->session->delete('redirect');
            $this->redirect( $urlToRedirect );
        }

        $this->title = 'Авторизация';
        $this->description = 'Страница для авторизации и регистрации пользователей';
        $this->template->content = View::factory('/templates/auth/auth', $this->view);
    }

    public function action_signup()
    {
        $this->title = 'Регистрация';
        $this->description = 'Страница для регистрации пользователей';

        $this->signup();

        $this->template->content = View::factory('/templates/auth/signup', $this->view);
    }

    /**
    * Users registration method
    * @author Savchenko Petr (vk.com/specc)
    */
    public function signup()
    {
        $signupForm = array(
            'email'           => Arr::get($_POST, 'signup_email'),
            'name'            => preg_replace("/[ ]+/", " ", trim(Arr::get($_POST, 'signup_name'))),
            'password'        => Arr::get($_POST, 'signup_password'),
            'password_repeat' => Arr::get($_POST, 'signup_password_repeat'),
        );

        /** Check for correct-filling form  */
        if (self::checkSignupFields($signupForm)) {

            /** Saves new user */
            $userId = parent::insertUser(array(
                'email'        => $signupForm['email'],
                'password'     => parent::createPasswordHash($signupForm['password']),
                'name'         => $signupForm['name']
            ));

            if ($userId) {

                parent::initAuthSession($userId);

                /** Redirect user after succeeded auth */
                $this->redirect( self::URL_TO_REDIRECT_AFTER_SUCCES_AUTH );
            }
        }
    }


    /**
    * Checks for correct-filling form
    * Fills $this->view['signup_error_fields'] with errors texts
    * @return bool - checking result
    * @author Savchenko Petr (vk.com/specc)
    */
    protected function checkSignupFields($fields)
    {
        /** Check for CSRF token*/
        if (!Security::check(Arr::get($_POST, 'csrf', ''))) return FALSE;

        /** Check for correct email */
        if (!Valid::email($fields['email'])) {

            $this->view['signup_error_fields']['email'] = 'Некорректный email';
            return FALSE;
        }

        /** Check for password existing */
        if (!$fields['password']) {

            $this->view['signup_error_fields']['password'] = 'Не указан пароль';
            return FALSE;
        }

        /** Check for password-repeation existing */
        if (!$fields['password_repeat']) {

            $this->view['signup_error_fields']['password_repeat'] = 'Не заполнено подтверждение пароля';
            return FALSE;
        }

        /** Check for correct passsword repeation */
        if ($fields['password'] != $fields['password_repeat']) {

            $this->view['signup_error_fields']['password_repeat'] = 'Подтверждение пароля не пройдено. Проверьте правильность ввода';
            return FALSE;
        }

        /** Generates new CSRF token */
        Security::token(TRUE);

        /** Check for email exisiting in DB  */
        if (!$this->user->hasUniqueEmail($fields['email'])) {

            $this->view['signup_error_text'] = 'Адрес <b>' . $fields['email'] . '</b> уже зарегистрирован <a href="/auth">Войти на сайт</a>';
            return FALSE;
        }
        return TRUE;
    }


    /**
    * Login method
    * @author Savchenko Petr (vk.com/specc)
    */
    public function login()
    {
        $loginForm = array(
            'email'     => Arr::get($_POST, 'login_email'),
            'password'  => Arr::get($_POST, 'login_password'),
        );

        if (self::checkUserLogin($loginForm)) {

            $passwordHash = parent::createPasswordHash($loginForm['password']);
            $userFound    = Dao_Users::select()
                                ->where('email', '=', $loginForm['email'])
                                ->where('password', '=', $passwordHash)
                                ->limit(1)
                                ->execute();

            if ($userFound) {
                /** Success login */
                parent::initAuthSession($userFound['id']);
                return TRUE;

            } else {

                $this->view['login_error_text'] = 'Неверный email или пароль';
                return FALSE;
            }
        }
    }

    /**
     *  Login with vk.com. Return auth status: true or false
     *  @author Demyashev Alexander
     *  @return bool $status
     */
    public function login_vk()
    {
        $vk     = Model::factory('Social_Vk');
        $code   = Arr::get($_GET, 'code', '');
        $action = Arr::get($_GET, 'action', '');
        $state  = Arr::get($_GET, 'state', 'login');

        if ($state == 'remove') return $this->social_remove('vk');

        if (!$code) {

            $redirect = $vk->getCode($state);

        } else {

            $response = $vk->auth($code);
            $userdata = $vk->getUserInfo($response->user_id);

            $user_to_db = array(
                'name'          => "{$userdata->last_name} {$userdata->first_name}",
                'email'         => $response->email,
                'vk'            => $userdata->uid,
                'vk_name'       => "{$userdata->last_name} {$userdata->first_name}",
                'vk_uri'        => $userdata->domain,
                'photo'         => $userdata->photo_50,
                'photo_medium'  => $userdata->photo_100,
                'photo_big'     => $userdata->photo_max
            );

            /**
             *  What to do with response data?
             *  @var string $state
             */
            if ($state) switch ($state) {

                case 'login':
                    $status = $this->social_insert('vk', $user_to_db);
                    break;

                case 'attach':
                    unset($user_to_db['email']);
                    $status = $this->social_attach($user_to_db);
                    break;
            }
            return $status;
        }
    }

    /**
     *  Login with facebook.com
     *  @author Demyashev Alexander
     */
    public function login_fb()
    {
        $fb     = Model::factory('Social_Fb');
        $code   = Arr::get($_GET, 'code', '');
        $state  = Arr::get($_GET, 'state', 'login');

        if (Arr::get($_GET, 'error')) {

            $this->view['login_error_text'] = Arr::get($_GET, 'error_description', '');
            return FALSE;
        }

        if ($state == 'remove') return $this->social_remove('facebook');

        if (!$code) {

            $fb->auth($state);

        } else {

            $response = $fb->getToken($code);
            $userdata = $fb->getUser($response->access_token);

            $user_to_db = array(
                'name'              => $userdata->name,
                'email'             => $userdata->email,
                'facebook'          => $userdata->id,
                'facebook_name'     => $userdata->name,
                'facebook_username' => NULL,
                'photo'             => $userdata->picture['100'],
                'photo_medium'      => $userdata->picture['200'],
                'photo_big'         => $userdata->picture['500']
            );

            /**
             *  What to do with response data?
             *  @var string $state
             */
            if ($state) switch ($state) {

                case 'login':
                    $status = $this->social_insert('facebook', $user_to_db);
                    break;

                case 'attach':
                    unset($user_to_db['email']);
                    $status = $this->social_attach($user_to_db);
                    break;
            }

            return $status;
        }
    }

    /**
     *  Login with twitter.com
     *  @author Demyashev Alexander
     */
    public function login_tw()
    {
        $session = Session::instance();

        $state              = Arr::get($_GET, 'state', 'login');
        $oauth_verifier     = Arr::get($_GET, 'oauth_verifier', '');
        $oauth_token        = $session->get('oauth_token', '');
        $oauth_token_secret = $session->get('oauth_token_secret', '');

        if ($state == 'remove') return $this->social_remove('twitter');

        //If there was a redirect from twitter and it sent us some auth data
        $twitter_initiated = !empty($oauth_verifier)
                          && !empty($oauth_token)
                          && !empty($oauth_token_secret);

        if ($twitter_initiated) {

            $userdata = $this->login_tw_get_userdata( $oauth_verifier, $oauth_token, $oauth_token_secret, $session);

            // 'include_email' Use of this parameter requires whitelisting.
            $user_to_db = array(
                'name'            => $userdata->name,
                'email'           => NULL,
                'twitter'         => $userdata->id_str,
                'twitter_name'    => $userdata->name,
                'twitter_username'=> $userdata->screen_name,
                'photo'           => str_replace('normal.jpeg', '200x200.jpeg', $userdata->profile_image_url_https),
                'photo_medium'    => str_replace('normal.jpeg', '400x400.jpeg', $userdata->profile_image_url_https),
                'photo_big'       => str_replace('normal.jpeg', '400x400.jpeg', $userdata->profile_image_url_https)
            );

            /**
             *  What to do with response data?
             *  @var string $state
             */
            if ($state) switch ($state) {

                case 'login':
                    $status = $this->social_insert('twitter', $user_to_db);
                    break;

                case 'attach':
                    unset($user_to_db['email']);
                    $status = $this->social_attach($user_to_db);
                    break;
            }

            return $status;

        } else {
            // if we do not have data from Twitter
            return $this->login_tw_get_request_token($session);
        }
    }

    /**
     * Get userdata from twitter profile with oauth_verifier token
     *
     * @author Alexander Demyashev <alexander.demyashev@gmail.com>
     *
     * @param  string   $oauth_verifier
     * @param  string   $oauth_token
     * @param  string   $oauth_token_secret
     * @param  object   $session
     * @return array    $userdata
     */
    private function login_tw_get_userdata(
        $oauth_verifier,
        $oauth_token,
        $oauth_token_secret,
        $session
    ) {
        $settings = Kohana::$config->load('social.twitter');

        $twitter_oauth = new Model_Social_Tw(
            $settings['consumer_key'],
            $settings['consumer_secret'],
            $oauth_token,
            $oauth_token_secret
        );

        $user_info = $twitter_oauth->getAccessToken($oauth_verifier);

        return $twitter_oauth->get('account/verify_credentials');
    }

    /**
     * Get oauth_verifier code from twitter in order to
     * @author Alexander Demyashev <alexander.demyashev@gmail.com>
     * @param  object   $session
     * @return bool     FALSE or REDIRECT (30x http code)
     */
    private function login_tw_get_request_token($session)
    {
        $settings = Kohana::$config->load('social.twitter');

        $twitter_oauth = new Model_Social_Tw(
            $settings['consumer_key'],
            $settings['consumer_secret']
        );

        $request_token = $twitter_oauth->getRequestToken($settings['redirect_uri']);

        $session->set('oauth_token', $request_token['oauth_token']);
        $session->set('oauth_token_secret', $request_token['oauth_token_secret']);

        if ($twitter_oauth->http_code == 200) {

            $url = $twitter_oauth->getAuthorizeURL($request_token['oauth_token']);
            $this->redirect($url);

        } else {

            $log = Log::instance();
            $log->add(Log::ERROR, 'Twitter authorisation failed');
            $log->write();

            return FALSE;
        }
    }

    /**
     *  Create profile on site with only social info
     *  @param  string    $social    # vk, twitter, facebook
     *  @param  array     $userdata
     *  @see    config/social.php for second param for initAuthSession() [0,1,2]
     *  @author Demyashev Alexander
     */
    private function social_insert($social, $userdata)
    {
        $social_cfg = Kohana::$config->load('social')->$social;

        $userFound = Dao_Users::select('id')
            ->where($social,  '=', $userdata[$social])
            ->limit(1)
            ->execute();

        if ($userFound) {

            Model::factory('User')->updateUser($userFound['id'], $userdata);

            parent::initAuthSession($userFound['id'], $social_cfg['type']);

            return TRUE;

        } else {

            $userId = parent::insertUser( $userdata );

            parent::initAuthSession($userId, $social_cfg['type']);
            return TRUE;
        }
    }

    /**
     *  Attach social profile to site user's profile
     *  @param  array     $userdata
     *  @author Demyashev Alexander
     */
    private function social_attach($userdata)
    {
        if ($userId = parent::checkAuth()) {

            Model::factory('User')->updateUser($userId, $userdata);
            return TRUE;

        } else {

            $this->view['login_error_text'] = 'Не удалось прикрепить профиль соцсети';
            return FALSE;
        }
    }

    /**
     *  Remove social profile from site user's profile
     *  @author Demyashev Alexander
     */
    private function social_remove($social)
    {
        switch ($social) {

            case 'vk':       $fieldsToClean = array('vk'=> NULL,'vk_name'=> NULL,'vk_uri'=> NULL); break;
            case 'facebook': $fieldsToClean = array('facebook'=> NULL,'facebook_username'=> NULL,'facebook_name'=> NULL); break;
            case 'twitter':  $fieldsToClean = array('twitter'=> NULL,'twitter_name'=> NULL,'twitter_username'=> NULL); break;
        }

        if ($userId = parent::checkAuth()) {

            if (TRUE == parent::rightToUnbindSocial($userId)) {

                Model::factory('User')->updateUser($userId, $fieldsToClean);
                return TRUE;

            } else {

                $this->view['login_error_text'] = 'Не удалось открепить профиль соцсети, т.к. это ваша последняя возможность авторизации на сайте';
                return FALSE;
            }

        } else {

            $this->view['login_error_text'] = 'Не удалось открепить профиль соцсети';
            return FALSE;
        }
    }

    /**
    * Checks for login form filled correctly
    * @return bool
    * @author Savchenko Petr (vk.com/specc)
    */
    protected function checkUserLogin($fields)
    {
        /** Check for CSRF token*/
        if (!Security::check(Arr::get($_POST, 'csrf', ''))) return FALSE;

        if (!Valid::email($fields['email'])) {

            $this->view['login_error_fields']['email'] = 'Некорректный email';
            return FALSE;
        }

        if (!$fields['password']) {

            $this->view['login_error_fields']['password'] = 'Введите пароль';
            return FALSE;
        }

        /** Generates new CSRF token */
        Security::token(TRUE);
        return TRUE;
    }


    /**
    * Action for /logout route
    */
    public function action_logout()
    {
        parent::deleteSession();
        parent::clearAuthCookie();

        $this->redirect('/auth');
    }
}
