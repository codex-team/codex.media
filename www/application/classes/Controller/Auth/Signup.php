<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Signup extends Controller_Auth_Base
{
    /** Where we should redirect user after success authorisation */
    const URL_TO_REDIRECT_AFTER_SUCCES_AUTH = '/';

    public function action_signup()
    {
        $this->title = 'Регистрация';
        $this->description = 'Страница для регистрации пользователей';

        $this->signup();

        $this->template->content = View::factory('/templates/auth/signup', $this->view);
    }

    /**
     * Users registration method
     *
     * @author Savchenko Petr (vk.com/specc)
     */
    public function signup()
    {
        $signupForm = [
            'email' => Arr::get($_POST, 'signup_email'),
            'name' => preg_replace("/[ ]+/", " ", trim(Arr::get($_POST, 'signup_name'))),
            'password' => Arr::get($_POST, 'signup_password'),
            'password_repeat' => Arr::get($_POST, 'signup_password_repeat'),
        ];

        /** Check for correct-filling form  */
        if (self::checkSignupFields($signupForm)) {

            /** Saves new user */
            $userId = parent::insertUser([
                'email' => $signupForm['email'],
                'password' => parent::createPasswordHash($signupForm['password']),
                'name' => $signupForm['name'],
                'isConfirmed' => 0
            ]);

            if ($userId) {
                parent::initAuthSession($userId);

                $model_auth = new Model_Auth([
                    "id" => $userId,
                    "name" => $signupForm['name'],
                    "email" => $signupForm['email']
                ]);

                $model_auth->sendEmail(Model_Auth::TYPE_EMAIL_CONFIRM);

                /** Redirect user after succeeded auth */
                $this->redirect(self::URL_TO_REDIRECT_AFTER_SUCCES_AUTH);
            }
        } else {
            echo "неуспех";
        }
    }

    /**
     * Checks for correct-filling form
     * Fills $this->view['signup_error_fields'] with errors texts
     *
     * @author Savchenko Petr (vk.com/specc)
     *
     * @param mixed $fields
     *
     * @return bool - checking result
     */
    protected function checkSignupFields($fields)
    {
        /** Check for CSRF token*/
        if (!Security::check(Arr::get($_POST, 'csrf', ''))) {
            $this->view['signup_error_text'] = 'CSRF токен не прошел проверку';

            return false;
        }

        /** Check for correct name */
        if (!$fields['name']) {
            $this->view['signup_error_fields']['name'] = 'Не указано имя пользователя';

            return false;
        }

        /** Check for correct email */
        if (!Valid::email($fields['email'])) {
            $this->view['signup_error_fields']['email'] = 'Некорректный email';

            return false;
        }

        /** Check for password existing */
        if (!$fields['password']) {
            $this->view['signup_error_fields']['password'] = 'Не указан пароль';

            return false;
        }

        /** Check for password-repeation existing */
        if (!$fields['password_repeat']) {
            $this->view['signup_error_fields']['password_repeat'] = 'Не заполнено подтверждение пароля';

            return false;
        }

        /** Check for correct passsword repeation */
        if ($fields['password'] != $fields['password_repeat']) {
            $this->view['signup_error_fields']['password_repeat'] = 'Подтверждение пароля не пройдено. Проверьте правильность ввода';

            return false;
        }

        /** Generates new CSRF token */
        Security::token(true);

        /** Check for email exisiting in DB  */
        if (!$this->user->hasUniqueEmail($fields['email'])) {
            $this->view['signup_error_text'] = 'Адрес <b>' . $fields['email'] . '</b> уже зарегистрирован <a href="/auth">Войти на сайт</a>';

            return false;
        }

        return true;
    }

    /**
     * Action for confirmation link
     */
    public function action_confirm()
    {
        $hash = $this->request->param('hash');

        $model_auth = new Model_Auth();

        $id = $model_auth->getUserIdByHash($hash, Model_Auth::TYPE_EMAIL_CONFIRM);

        if (!$id) {
            $error_text = 'Ваш аккаунт уже подтвержден';
            $this->template->content = View::factory('templates/error', ['error_text' => $error_text]);

            return;
        }

        $model_auth->deleteHash($hash, Model_Auth::TYPE_EMAIL_CONFIRM);

        $user = new Model_User($id);

        if (!$user->id) {
            $error_text = 'Переданы некорректные данные';
            $this->template->content = View::factory('templates/error', ['error_text' => $error_text]);

            return;
        }

        $user->updateUser($user->id, ['isConfirmed' => 1]);

        $this->redirect('/user/' . $id . '?confirmed=1');
    }
}
