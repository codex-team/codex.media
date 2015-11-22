<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller_Base_preDispatch
{

    public function action_login()
    {
        $this->view['login_error']      = false;
        $this->view['login_error_text'] = '';

        $this->template->title = 'КИКГ ИТМО - Авторизация';

        if ( Arr::get($_POST, 'csrf') ){

            $email    = Arr::get($_POST, 'email', null);
            $password = Arr::get($_POST, 'password', null);
            $CSRF     = Arr::get($_POST, 'csrf', '');
            
            if ( $email && $password && Security::check($CSRF) && Valid::email(Arr::get($_POST, 'email', '')) ){
                if ( $user = $this->user->getUserForLogin($email, $password) ){
                    $this->user->setAuthCookie($user['id']);
                    $this->redirect();
                } else {
                    $this->view['login_error'] = true;
                    $this->view['login_error_text'] = 'Неправильный логин или пароль';
                }
            } elseif ( !Valid::email(Arr::get($_POST, 'email', '')) ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Email написан не правильно';
            } elseif ( !$this->user->hasUniqueUsername($email) ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Такой email не зарегистрирован';
            } elseif ( !$email ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Поле email пусто';
            } elseif ( !$password ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Поле пароль пусто';
            }
        }

        $this->template->content = View::factory('templates/login', $this->view);
    }

    public function action_signup()
    {
        $this->view['login_error']      = false;
        $this->view['login_error_text'] = '';

        $this->template->title = 'КИКГ ИТМО - Регистрация';

        /* Продолжаем регистрацию после того, как авторизовался через контакт */
        if ( Arr::get($_POST, 'vk_id', '') && Arr::get($_POST, 'csrf') ) {
            $email            = Arr::get($_POST, 'email', '');
            $password         = Arr::get($_POST, 'password', null);
            $password_confirm = Arr::get($_POST, 'password_confirm', null);
            $CSRF             = Arr::get($_POST, 'csrf', '');
            $vk_id            = Arr::get($_POST, 'vk_id', '');
            
            if ( $email && $password && ( $password == $password_confirm ) && Valid::email(Arr::get($_POST, 'email', '')) && Security::check($CSRF) ){
                if ($new_user_id = $this->user->updateInfoVk($vk_id, $email, $password)) {
                    $this->user->updateRole( $new_user_id , 1);
                    $this->redirect();
                }
                else {
                    $this->view['login_error'] = true;
                    $this->view['login_error_text'] = 'Инфа не обновилась';
                    $this->template->content = View::factory('templates/signup', $this->view);
                } 
            }
            else {
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'не все поля заполнены';
                $this->template->content = View::factory('templates/signup', $this->view);
            }

        }
        
        # normal registration
        elseif ( Arr::get($_POST, 'csrf') ){

            $email            = Arr::get($_POST, 'email', '');
            $password         = Arr::get($_POST, 'password', null);
            $password_confirm = Arr::get($_POST, 'password_confirm', null);
            $CSRF             = Arr::get($_POST, 'csrf', '');

            
            if ( $email && $password && ( $password == $password_confirm ) && Valid::email(Arr::get($_POST, 'email', '')) && Security::check($CSRF) ){
                if ($this->user->hasUniqueUsername($email)) {
                    if ($new_user_id = $this->user->insertNewUser($email, $password)) {
                        $this->user->setAuthCookie($new_user_id);
                        $this->user->updateRole( $new_user_id , 1);

                        $this->redirect();
                    } else {
                        $this->view['login_error'] = true;
                        $this->view['login_error_text'] = 'Пользователь не добавлен';
                    }
                } else {
                    $this->view['login_error'] = true;
                    $this->view['login_error_text'] = 'Такой пользователь уже существет';
                }
            } elseif ( !Valid::email(Arr::get($_POST, 'email', '')) ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Email написан неправильно';
            } elseif ( !$email ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Поле email пусто';
            } elseif ( !$password ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Поле пароля пусто';
            } elseif ( !$password_confirm ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Поле подтверждения пароля пусто';
            } elseif ( $password != $password_confirm ){
                $this->view['login_error'] = true;
                $this->view['login_error_text'] = 'Пароли не совпадают';
            } 
        }

        $this->template->content = View::factory('templates/signup', $this->view);
    }

    public function action_logout()
    {
        Cookie::delete('uid');
        Cookie::delete('hr');
        $this->redirect('/login');
    }


    public function action_vk()
    {
        $this->view['login_error']      = false;
        $this->view['login_error_text'] = '';

        $this->view['vk_signup']        = false;
        $this->view['vk_signup_text']   = '';

        $this->template->title = 'КИКГ ИТМО - Авторизация через сайт vk.com';

        $data = array(
            'client_id'     => '3494261', 
            'client_secret' => 'OsXjHkDgWHyS3a7mJy4s',
            'code'          => NULL,
            'redirect_uri'  => 'http://' . Arr::get($_SERVER, 'SERVER_NAME' , '') . '/vk',
        );

        if ( Arr::get($_GET, 'code') ) {
            $code = (string) Arr::get($_GET, 'code', '');
            $data['code'] = $code;

            $url = "https://oauth.vk.com/access_token?"
                ."client_id=".         $data['client_id']
                ."&client_secret=".    $data['client_secret']
                ."&code=".             $data['code']
                ."&redirect_uri=".     $data['redirect_uri']
                ."&";


            $user = json_decode(file_get_contents($url));
        
            // [access_token] => 0ce89b9df46 [expires_in] => 86400 [user_id] => 40474063 ) 

            $url = "https://api.vk.com/method/users.get?"
                . "uid=" .               $user->user_id
                . "&access_token=" .     $user->access_token
                . "&fields=photo_50";

            $data = json_decode(file_get_contents($url));
            $data = current($data->response);


            // [uid] => 40474063 [first_name] => ... [last_name] => ... [photo_50] => url

            // Если зареганы, но нет vk-аккаунта, добавляем vk аккаунт к аккаунту user
            if ( $this->user->id && !$this->user->vk_id ) {
                if ( $user = $this->user->addVk($this->user->id, $data->uid, $data->first_name, $data->last_name, $data->photo_50) ){
                    $this->redirect();
                } 
            }

            // Если в базе нет вообще, добавляем vk и продолжаем регистрацию
            if ($user = $this->user->hasUniqueUsernameVK($data->uid)) {

                if ($new_user_id = $this->user->insertNewUserVK( $data->uid, $data->first_name, $data->last_name )) {
                    $this->user->setAuthCookie($new_user_id);

                    $url = $data->photo_50;
                    $path = './public/img/user/'.$new_user_id.'_50x50.jpg';
                    $img_url = '/public/img/user/'.$new_user_id.'_';

                    $save_ava = file_put_contents($path, file_get_contents($url));

                    $update = $this->user->updateImgVk( $data->uid, $img_url );

                    $this->redirect('http://' . Arr::get($_SERVER, 'SERVER_NAME' , '') . '/signup');
                }
            
            } else {

                if ( $user = $this->user->getUserForLoginVK($data->uid) ){
                    $this->user->setAuthCookie($user['id']);

                    $url = $data->photo_50;
                    $path = './public/img/user/'.$user['id'].'_50x50.jpg';
                    $img_url = '/public/img/user/'.$user['id'].'_';

                    $save_ava = file_put_contents($path, file_get_contents($url));
                    $update = $this->user->updateImgVk( $data->uid, $img_url );
            
                    $this->redirect();
                } 
            
            }
            
        }

        if ( Arr::get($_GET, 'error') ){
            $error = (string) Arr::get($_GET, 'error', '');
            $error_reason = (string) Arr::get($_GET, 'error_reason', '');
            $error_description = (string) Arr::get($_GET, 'error_description', '');

            die($error);
            // Code here...
        }
    }

}