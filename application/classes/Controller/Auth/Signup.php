<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Signup extends Controller_Auth_Base
{
    /** Where we should redirect user after success authorisation */
    const URL_TO_REDIRECT_AFTER_SUCCES_AUTH    = '/';
    const CONFIRMATION_HASHES_KEY              = 'codex.org.confirmation.hashes';

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
                'name'         => $signupForm['name'],
                'isConfirmed'  => 0
            ));


            if ($userId) {

                parent::initAuthSession($userId);

                $this->sendConfirmationEmail(new Model_User($userId));

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

        /** Check for correct name */
        if (!Valid::not_empty($fields['name'])) {

            $this->view['signup_error_fields']['name'] = 'Не указано имя пользователя';
            return FALSE;
        }

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


    protected function sendConfirmationEmail($user) {

        $hash = hash('md5', self::AUTH_PASSWORD_SALT.$user->email);

        $this->redis->hset(self::CONFIRMATION_HASHES_KEY, $hash, $user->id);

        $message = View::factory('templates/emails/confirm', array('user' => $user, 'hash' => $hash));

        $email = new Email();
        $email->send($user->email, $GLOBALS['SITE_MAIL'], "Добро пожаловать на ".$_SERVER['HTTP_HOST']."!", $message, true);


    }

    public function action_confirm() {

        $hash = $this->request->param('hash');

        $id = $this->redis->hGet(self::CONFIRMATION_HASHES_KEY, $hash);

        if (!$id) {
            $error_text = 'Ваш аккаунт уже подтвержден';
            $this->template->content = View::factory('templates/error', array('error_text' => $error_text));
            return;
        }

        $user = new Model_User($id);

        if (!$user->id) {
            $error_text = 'Пользователя, почту которого вы хотите подтвердить, не существует :(';
            $this->template->content = View::factory('templates/error', array('error_text' => $error_text));
            return;
        }

        $user->updateUser($user->id, array('isConfirmed' => 1));

        $this->redis->hDel(self::CONFIRMATION_HASHES_KEY, $hash);

        $this->redirect('/user/'.$id);

    }

    public function action_show() {

        $this->view['user'] = new Model_User(4);
        $this->view['hash'] = 1;

        $this->template->content = View::factory('/templates/emails/confirm', $this->view);

    }

}