<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Auth extends Controller_Auth_Base
{
    /** Where we should redirect user after success authorisation */
    const URL_TO_REDIRECT_AFTER_SUCCES_AUTH = '/';

    /**
     * Auth page.
     * Both 2 form: login and signup handlers here.
     *
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
        $action = Arr::get($_POST, 'action');
        $method = $this->request->param('method');
        $authSucceeded = false;

        if ($action) {
            switch ($action) {

            case 'login': $authSucceeded = $this->login(); break;
        }
        }

        if ($method) {
            switch ($method) {

            case 'vk': $authSucceeded = $this->login_vk(); break;
            case 'fb': $authSucceeded = $this->login_fb(); break;
            case 'tw': $authSucceeded = $this->login_tw(); break;
        }
        }

        /** Redirect user after succeeded auth */
        if ($authSucceeded) {
            $urlToRedirect = $this->session->get('redirect', self::URL_TO_REDIRECT_AFTER_SUCCES_AUTH);
            $this->session->delete('redirect');
            $this->redirect($urlToRedirect);
        }

        $this->view['passwordReseted'] = Arr::get($_GET, 'reseted', 0);

        $this->title = 'Авторизация';
        $this->description = 'Страница для авторизации и регистрации пользователей';
        $this->template->content = View::factory('/templates/auth/auth', $this->view);
    }

    /**
     * Login method
     *
     * @author Savchenko Petr (vk.com/specc)
     */
    public function login()
    {
        $loginForm = [
            'email' => Arr::get($_POST, 'login_email'),
            'password' => Arr::get($_POST, 'login_password'),
        ];

        if (self::checkUserLogin($loginForm)) {
            $passwordHash = parent::createPasswordHash($loginForm['password']);
            $userFound = Dao_Users::select()
                                ->where('email', '=', $loginForm['email'])
                                ->where('password', '=', $passwordHash)
                                ->limit(1)
                                ->execute();

            if ($userFound) {
                /** Success login */
                parent::initAuthSession($userFound['id']);

                return true;
            } else {
                $this->view['login_error_text'] = 'Неверный email или пароль';

                return false;
            }
        }
    }

    /**
     *  Login with vk.com. Return auth status: true or false
     *
     *  @author Demyashev Alexander
     *
     *  @return bool $status
     */
    public function login_vk()
    {
        $vk = Model::factory('Social_Vk');
        $code = Arr::get($_GET, 'code', '');
        $state = Arr::get($_GET, 'state', 'login');

        $log = Log::instance();

        if ($state == 'remove') {
            return $this->social_remove('vk');
        }

        if (!$code) {
            $redirect = $vk->getCode($state);
        } else {
            $response = $vk->auth($code);
            $userdata = $vk->getUserInfo($response->user_id);

            $user_to_db = [
                'name' => "{$userdata->last_name} {$userdata->first_name}",
                'vk' => $userdata->id,
                'vk_name' => "{$userdata->last_name} {$userdata->first_name}",
                'vk_uri' => $userdata->domain,
                'photo' => $userdata->photo_50,
                'photo_medium' => $userdata->photo_100,
                'photo_big' => $userdata->photo_max
            ];

            if (property_exists($response, 'email')) {
                $user_to_db['email'] = $response->email;
            }

            /**
             *  What to do with response data?
             *
             *  @var string $state
             */
            if ($state) {
                switch ($state) {

                case 'login':
                    $status = $this->social_insert('vk', $user_to_db);
                    break;

                case 'attach':
                    $status = $this->social_attach('vk', $userdata->id, $user_to_db);
                    break;
            }
            }

            return $status;
        }
    }

    /**
     *  Login with facebook.com
     *
     *  @author Demyashev Alexander
     */
    public function login_fb()
    {
        $fb = Model::factory('Social_Fb');
        $code = Arr::get($_GET, 'code', '');
        $state = Arr::get($_GET, 'state', 'login');

        if (Arr::get($_GET, 'error')) {
            $this->view['login_error_text'] = Arr::get($_GET, 'error_description', '');

            return false;
        }

        if ($state == 'remove') {
            return $this->social_remove('facebook');
        }

        if (!$code) {
            $fb->auth($state);
        } else {
            $response = $fb->getToken($code);
            $userdata = $fb->getUser($response->access_token);

            $user_to_db = [
                'name' => $userdata->name,
                'facebook' => $userdata->id,
                'facebook_name' => $userdata->name,
                'facebook_username' => null,
                'photo' => $userdata->picture['100'],
                'photo_medium' => $userdata->picture['200'],
                'photo_big' => $userdata->picture['500']
            ];

            if (property_exists($response, 'email')) {
                $user_to_db['email'] = $response->email;
            }

            /**
             *  What to do with response data?
             *
             *  @var string $state
             */
            if ($state) {
                switch ($state) {

                case 'login':
                    $status = $this->social_insert('facebook', $user_to_db);
                    break;

                case 'attach':
                    $status = $this->social_attach('facebook', $userdata->id, $user_to_db);
                    break;
            }
            }

            return $status;
        }
    }

    /**
     *  Login with twitter.com
     *
     *  @author Demyashev Alexander
     */
    public function login_tw()
    {
        $session = Session::instance();

        $state = Arr::get($_GET, 'state', 'login');
        $oauth_verifier = Arr::get($_GET, 'oauth_verifier', '');
        $oauth_token = $session->get('oauth_token', '');
        $oauth_token_secret = $session->get('oauth_token_secret', '');

        if ($state == 'remove') {
            return $this->social_remove('twitter');
        }

        //If there was a redirect from twitter and it sent us some auth data
        $twitter_initiated = !empty($oauth_verifier)
                          && !empty($oauth_token)
                          && !empty($oauth_token_secret);

        if ($twitter_initiated) {
            $userdata = $this->login_tw_get_userdata($oauth_verifier, $oauth_token, $oauth_token_secret, $session);

            // 'include_email' Use of this parameter requires whitelisting.
            $user_to_db = [
                'name' => $userdata->name,
                'email' => null,
                'twitter' => $userdata->id_str,
                'twitter_name' => $userdata->name,
                'twitter_username' => $userdata->screen_name,
                'photo' => str_replace('normal.jpeg', '200x200.jpeg', $userdata->profile_image_url_https),
                'photo_medium' => str_replace('normal.jpeg', '400x400.jpeg', $userdata->profile_image_url_https),
                'photo_big' => str_replace('normal.jpeg', '400x400.jpeg', $userdata->profile_image_url_https)
            ];

            /**
             *  What to do with response data?
             *
             *  @var string $state
             */
            $state = $session->get('state', 'login');
            if ($state) {
                switch ($state) {

                case 'login':
                    $status = $this->social_insert('twitter', $user_to_db);
                    break;

                case 'attach':
                    $status = $this->social_attach('twitter', $userdata->id_str, $user_to_db);
                    break;
            }
            }

            return $status;
        } else {
            // if we do not have data from Twitter
            return $this->login_tw_get_request_token($session, $state);
        }
    }

    /**
     * Get userdata from twitter profile with oauth_verifier token
     *
     * @author Alexander Demyashev <alexander.demyashev@gmail.com>
     *
     * @param string $oauth_verifier
     * @param string $oauth_token
     * @param string $oauth_token_secret
     * @param object $session
     *
     * @return array $userdata
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
     *
     * @author Alexander Demyashev <alexander.demyashev@gmail.com>
     *
     * @param object $session
     * @param mixed  $state
     *
     * @return bool FALSE or REDIRECT (30x http code)
     */
    private function login_tw_get_request_token($session, $state)
    {
        $settings = Kohana::$config->load('social.twitter');

        $twitter_oauth = new Model_Social_Tw(
            $settings['consumer_key'],
            $settings['consumer_secret']
        );

        $request_token = $twitter_oauth->getRequestToken($settings['redirect_uri']);

        /**
         * Catch Error and send it to Hawk
         */
        if (empty($request_token['oauth_token']) ||
            empty($request_token['oauth_token_secret'])) {
            try {
                throw new Exception('Bad response from Twitter while authorization');
            } catch (\Exception $e) {
                \Hawk\Catcher::get()->sendException($e);
            }

            return false;
        }

        $session->set('oauth_token', $request_token['oauth_token']);
        $session->set('oauth_token_secret', $request_token['oauth_token_secret']);
        $session->set('state', $state);

        if ($twitter_oauth->http_code == 200) {
            $url = $twitter_oauth->getAuthorizeURL($request_token['oauth_token']);
            $this->redirect($url);
        }

        return false;
    }

    /**
     *  Create profile on site with only social info
     *
     *  @param  string    $social    # vk, twitter, facebook
     *  @param  array     $userdata
     *
     *  @see    config/social.php for second param for initAuthSession() [0,1,2]
     *
     *  @author Demyashev Alexander
     */
    private function social_insert($social, $userdata)
    {
        $social_cfg = Kohana::$config->load('social')->$social;

        $userFound = Dao_Users::select('id')
            ->where($social, '=', $userdata[$social])
            ->limit(1)
            ->execute();

        if ($userFound) {
            unset($userdata['email'], $userdata['name'],
                  $userdata['photo'], $userdata['photo_medium'], $userdata['photo_big']);

            $updateResult = Model::factory('User')->updateUser($userFound['id'], $userdata);

            parent::initAuthSession($userFound['id'], $social_cfg['type']);

            return true;
        } else {
            $userId = parent::insertUser($userdata);

            parent::initAuthSession($userId, $social_cfg['type']);

            return true;
        }
    }

    /**
     *  Attach social profile to site user's profile
     *
     *  @param  array     $userdata
     * @param mixed $social
     * @param mixed $social_id
     *
     *  @author Demyashev Alexander
     */
    private function social_attach($social, $social_id, $userdata)
    {
        if ($userId = parent::checkAuth()) {
            $userFound = Dao_Users::select('id')
                ->where($social, '=', $social_id)
                ->limit(1)
                ->execute();

            if (!$userFound) {
                unset($userdata['email'], $userdata['name'],
                      $userdata['photo'], $userdata['photo_medium'], $userdata['photo_big']);

                Model::factory('User')->updateUser($userId, $userdata);

                return true;
            } else {
                $this->view['login_error_text'] = 'Профиль, который вы хотите прикрепить, уже прикреплен';

                return false;
            }
        } else {
            $this->view['login_error_text'] = 'Не удалось прикрепить профиль соцсети';

            return false;
        }
    }

    /**
     *  Remove social profile from site user's profile
     *
     *  @author Demyashev Alexander
     *
     * @param mixed $social
     */
    private function social_remove($social)
    {
        switch ($social) {

            case 'vk':       $fieldsToClean = ['vk' => null,'vk_name' => null,'vk_uri' => null]; break;
            case 'facebook': $fieldsToClean = ['facebook' => null,'facebook_username' => null,'facebook_name' => null]; break;
            case 'twitter':  $fieldsToClean = ['twitter' => null,'twitter_name' => null,'twitter_username' => null]; break;
        }

        if ($userId = parent::checkAuth()) {
            if (true == parent::rightToUnbindSocial($userId)) {
                Model::factory('User')->updateUser($userId, $fieldsToClean);

                return true;
            } else {
                $this->view['login_error_text'] = 'Не удалось открепить профиль соцсети, т.к. это ваша последняя возможность авторизации на сайте';

                return false;
            }
        } else {
            $this->view['login_error_text'] = 'Не удалось открепить профиль соцсети';

            return false;
        }
    }

    /**
     * Checks for login form filled correctly
     *
     * @author Savchenko Petr (vk.com/specc)
     *
     * @param mixed $fields
     *
     * @return bool
     */
    protected function checkUserLogin($fields)
    {
        /** Check for CSRF token*/
        if (!Security::check(Arr::get($_POST, 'csrf', ''))) {
            return false;
        }

        if (!Valid::email($fields['email'])) {
            $this->view['login_error_fields']['email'] = 'Некорректный email';

            return false;
        }

        if (!$fields['password']) {
            $this->view['login_error_fields']['password'] = 'Введите пароль';

            return false;
        }

        /** Generates new CSRF token */
        Security::token(true);

        return true;
    }

    /**
     * Action for /logout route
     */
    public function action_logout()
    {
        if ($this->user->id) {
            parent::deleteSession();
            parent::clearAuthCookie();
        }

        $this->redirect('/auth');
    }

    /**
     * Action for /reset route
     */
    public function action_reset()
    {
        $this->title = 'Восстановление пароля';
        $this->description = 'Страница для восстановления пароля';

        $this->view['header'] = 'Восстановление пароля';
        $this->view['email'] = '';

        $email = Arr::get($_POST, 'reset_email', '');

        if ($this->checkEmail($email)) {
            $this->view['header'] = 'Мы отправили письмо с инструкциями на вашу почту';
            $this->view['email'] = $email;
        }

        $this->template->content = View::factory('/templates/auth/reset_password', $this->view);
    }

    /**
     * Checks if email valid and sets error text to $this->view
     *
     * @param $email
     *
     * @return bool
     */
    private function checkEmail($email)
    {

        /** Check for CSRF token*/
        if (!Security::check(Arr::get($_POST, 'csrf', ''))) {
            return false;
        }

        /** Check for correct email */
        if (!Valid::email($email)) {
            $this->view['reset_password_error_fields']['email'] = 'Некорректный email';

            return false;
        }

        $user = new Model_User($email);

        if (!$user->id) {
            $this->view['reset_password_error_fields']['email'] = 'Пользователь с таким email не найден';

            return false;
        }

        $model_auth = new Model_Auth([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);

        $model_auth->sendEmail(Model_Auth::TYPE_EMAIL_RESET);

        return true;
    }

    /**
     * Action for reset link
     */
    public function action_reset_password()
    {
        $hash = $this->request->param('hash');
        $method = $this->request->param('method');

        $model_auth = new Model_Auth();

        $id = $model_auth->getUserIdByHash($hash, $method);

        if (!$id) {
            $error_text = 'Ссылка не действительна';
            $this->template->content = View::factory('templates/error', ['error_text' => $error_text]);

            return;
        }

        $user = new Model_User($id);

        if (!$user->id) {
            $error_text = 'Переданы некорректные данные';
            $this->template->content = View::factory('templates/error', ['error_text' => $error_text]);

            return;
        }

        $fields = [

            'password' => Arr::get($_POST, 'reset_password', ''),
            'password_repeat' => Arr::get($_POST, 'reset_password_repeat', '')

        ];

        if ($this->checkNewPassword($fields)) {
            $user->updateUser($id, ['password' => parent::createPasswordHash($fields['password'])]);
            $model_auth->deleteHash($hash, $method);

            switch ($method) {
                case Model_Auth::TYPE_EMAIL_RESET: $this->redirect('/auth?reseted=1'); break;
                case Model_Auth::TYPE_EMAIL_CHANGE: $this->redirect('/user/settings?success=1'); break;
            }
        }

        $this->view['method'] = $method;

        $this->template->content = View::factory('templates/auth/new_password', $this->view);
    }

    /**
     * Validation for new password
     *
     * @param $fields
     *
     * @return bool
     */
    private function checkNewPassword($fields)
    {

        /** Check for CSRF token*/
        if (!Security::check(Arr::get($_POST, 'csrf', ''))) {
            return false;
        }

        if (!$fields['password']) {
            $this->view['reset_password_error_fields']['password'] = 'Введите пароль';

            return false;
        }

        if (!$fields['password_repeat']) {
            $this->view['reset_password_error_fields']['password_repeat'] = 'Повторите пароль';

            return false;
        }

        if ($fields['password_repeat'] != $fields['password']) {
            $this->view['reset_password_error_fields']['password_repeat'] = 'Пароли не совпадают';

            return false;
        }

        /** Generates new CSRF token */
        Security::token(true);

        return true;
    }
}
