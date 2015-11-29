<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{
    public function action_login()
    {
        // Проверям, вдруг пользователь уже зашел 
        if(Auth::instance()->logged_in()) {
            // И если это так, то отправляем его сразу на страницу пользователей 
             return $this->redirect('/');
        }
 
        // Если же пользователь не зашел, но данные на страницу пришли, то: 
        if ($_POST)
        {

            $email    = Arr::get($_POST, 'email', '');
            $password = Arr::get($_POST, 'password', '');
            $remember = Arr::get($_POST, 'remember', '');

            // Создаем переменную, отвечающую за связь с моделью данных User 
            $user = ORM::factory('User');

            // в $status помещаем результат функции login 
            $status = Auth::instance()->login($email, $password, $remember);

            // Если логин успешен, то 
            if ($status) {
                // Отправляем пользователя на главную
                $this->redirect('/');
            }
            else {
                $this->view['errors'][] = 'Пользователь или пароль не верны. Проверьте правильность данных, а также факт подтверждения активации аккаунта через письмо на почту.';
            }
        }

        // Грузим view логина 
        $this->template->title   = 'Авторизация';
        $this->template->content = View::factory('templates/auth/login', $this->view);
    }

    public function action_register() {
        // Проверям, вдруг пользователь уже зашел 
        if(Auth::instance()->logged_in()) {
            // И если это так, то отправляем его сразу на страницу пользователей 
             return $this->redirect('/');
        }

        // Если есть данные, присланные методом POST    
        if ($_POST) {
            
            // trim spases
            $_POST = Arr::map('trim', $_POST);

            $post = array();
            $post['email']            = Arr::get($_POST, 'email', '');
            $post['password']         = Arr::get($_POST, 'password', '');
            $post['password_confirm'] = Arr::get($_POST, 'password_confirm', '');
            $post['username']         = current(explode('@', $post['email']));
            $post['token']            = md5(time() . $post['username']  . $post['email']);

            $validation = Validation::factory($post);

            $validation->rules('email', array(
                array('not_empty'),
                array('email'),
            ));

            $validation->rules('password', array(
                array('not_empty'),
                array('alpha_numeric'),
                array('min_length', array(':value', 6)),
            ));

            $validation->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));

            $validation->labels(array(
                'username' => 'Имя пользователя',
                'password' => 'Пароль',
                'password_confirm' => 'Подтверждение пароля',
            ));

            if( !$validation->check() ) {
                $this->view['errors'] = $validation->errors('validation');
            }
            else {

                // email is unique?
                $email = ORM::factory('User')
                    ->where('email', '=', $post['email'])
                    ->limit(1)
                    ->find();
                $email_isset = $email->loaded();

                if ($email_isset) {
                    $this->view['errors'][] = 'Email уже зарегистрирован';
                }
                else {
                    // Создаем переменную, отвечающую за связь с моделью данных User 
                    $model = ORM::factory('User');

                    // Вносим в эту переменную значения, переданные из POST 
                    $model->values(array(
                        'username'        => $post['username'],
                        'email'           => $post['email'],
                        'password'        => $post['password'],
                        'password_confirm'=> $post['password_confirm'],
                        'token'           => $post['token'],
                    ));

                    try
                    {
                        // Пытаемся сохранить пользователя (то есть, добавить в базу) 
                        $model->save();
                        // Назначаем ему роли 
                        //$model->add('roles', ORM::factory('role')->where('name', '=', 'login')->find());

                        // Создаем ссылку для подтверждения E-mail
                        $url  = 'http://'.$_SERVER['HTTP_HOST'].'/user/approved?token='.$post['token'];
                        
                        // Отправляем письмо пользователю с ссылкой для подтверждения E-mail
                        mail($post['email'], 'Регистрация на сайте SiteName', 'Вы были зерегестрированы на сайте SiteName, для подтверждения E-mail пройдите по ссылке '.$url);

                        // И отправляем его на страницу пользователя 
                        $this->redirect('/user/login?action=approve');
                    }
                    catch (ORM_Validation_Exception $e)
                    {
                        // Это если возникли какие-то ошибки 
                        $this->view['errors'][] = $e;
                    }
                }
            
            }   
        }
        // Загрузка формы логина 
        $this->template->title   = 'Регистрация';
        $this->template->content = View::factory('templates/auth/login', $this->view); 

    }

    public function action_approved()
    {
        $token = $this->request->query('token');
        if($token){
            // ищем пользователя с нужным токеном
            $user = ORM::factory('User')->where('token', '=', $token)->find();
            if($user->get('id')){
            
                // добавляем пользователю роль login, чтобы он мог авторизоваться
                $user->add('roles', ORM::factory('Role',array('name'=>'login')));
                
                // Чистим поле с токеном
                $user->update_user(array('token'=>null), array('token'));
                
                // Можно сразу и авторизовать и перенаправить ЛК
                Auth::instance()->force_login($user->get('email'));
                $this->redirect('/');
                
                // Или переадресовать на форму входа для ввода логина и пароль
                //$this->redirect("/users/login");
            }
        }
     
        // Делаем редирект на страницу авторизации
        $this->redirect("user/login");
    }

    public function action_logout()
    {
        Auth::instance()->logout();
        $this->redirect('/');
    }

    public function action_profile()
    {
        $uid = $this->request->param('id');

        $this->template->content = View::factory('templates/user/profile', $this->view);
    }

}