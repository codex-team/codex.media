<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Modify extends Controller_Base_preDispatch
{
    /**
     * @var array - AJAX response
     */
    private $ajaxResponse = array(
        'success' => 0
    );

    public function action_settings() {

        if (!$this->user->id) {
            throw new HTTP_Exception_403();
        }

        $this->view['success'] = Arr::get($_GET, 'success', 0);

        if (Security::check(Arr::get($_POST, 'csrf'))) {
           $this->view['success'] = $this->update();
        };

        $this->template->content = View::factory('/templates/users/settings', $this->view);

    }

    public function update () {

        $name = Arr::get($_POST, 'name', $this->user->name);
        $bio  = Arr::get($_POST, 'bio', $this->user->bio);

        $fields = array(
            'name' => $name,
            'bio'  => $bio,
        );

        if ($this->validateForm($fields)) {

            $this->user->updateUser($this->user->id, $fields);
            $this->redirect('user/settings?success=1');
            return true;

        }

        return false;

    }

    private function validateForm($fields) {

        $success = true;

        if (!trim($fields['name'])) {
            $this->view['errors']['name'] = 'Введите имя';
            $success = false;
        }

        return $success;

    }

    public function action_request_password_change() {

        $this->auto_render = false;

        $response = array(
            'success' => 0
        );

        $request  = json_decode(file_get_contents('php://input'));
        $password = $request->currentPassword;
        $csrf     = $request->csrf;

        if (!Security::check($csrf) || !$this->request->is_ajax()) {
            throw new HTTP_Exception_403();
        }

        if (empty($password) && $this->user->password) {

            $response['message'] = 'Введите пароль';
            $this->response->body(json_encode($response));
            return;

        }

        if ($this->user->password && !$this->user->checkPassword($password)) {

            $response['message'] = 'Неверный пароль';
            $this->response->body(json_encode($response));
            return;

        }


        $model_auth = new Model_Auth($this->user);

        $model_auth->sendChangePasswordEmail();

        $response = array(
            'success' => 1,
            'message' => 'Мы отправили на вашу почту письмо с подтверждением. Перейдите по ссылке в письме, чтобы установить новый пароль.'
        );

        $this->response->body(json_encode($response));

    }

    /**
     * Fast saving bio from profile
     * AJAX action
     */
    public function action_updateBio()
    {
        $response = array(
            'success' => 0
        );

        $bio  = Arr::get($_POST, 'bio');
        $csrf = Arr::get($_POST, 'csrf');

        $bio = trim($bio);

        if (Security::check($csrf) && $bio) {

            $saving = $this->user->updateUser($this->user->id, array(
                'bio' => $bio
            ));

            $response['success'] = 1;
            $response['bio']     = $bio;
            $response['csrf']    = Security::token(true);

        }

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($response) );
    }

    /**
     *
     */
    public function action_promote()
    {
        $field  = $this->request->param('field');
        $userId = $this->request->param('id');
        $value  = Arr::get($_POST, 'value');

        if (!$this->user->isAdmin) {

            $this->ajaxResponse['message'] = 'Access denied';
            goto finish;
        }

        switch ($field) {
            case 'status':
                $this->ajaxResponse['success'] = (boolean) $this->changeStatus($userId, $value);
                break;
            case 'role':
                $this->ajaxResponse['success'] = (boolean) $this->changeRole($userId, $value);
                break;
        }

        finish:
        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($this->ajaxResponse) );
    }

    /**
     * @param $userId
     * @param $status
     * @return boolean
     */
    private function changeStatus($userId, $status)
    {
        $viewUser = new Model_User($userId);

        switch ($status) {
            case Model_User::BANNED:
                $this->ajaxResponse['message']     = 'Пользователь заблокирован';
                $this->ajaxResponse['buttonText']  = 'Разблокировать';
                $this->ajaxResponse['buttonValue'] = Model_User::STANDARD;
                break;

            case Model_User::STANDARD:
                $this->ajaxResponse['message']     = 'Пользователь разблокирован';
                $this->ajaxResponse['buttonText']  = 'Заблокировать';
                $this->ajaxResponse['buttonValue'] = Model_User::BANNED;
                break;
        }

        return $viewUser->updateUser($viewUser->id, array(
            'status' => $status
        ));

    }

    /**
     * @param $userId
     * @param $role
     * @return boolean
     */
    private function changeRole($userId, $role)
    {
        $viewUser = new Model_User($userId);

        switch ($role) {
            case Model_User::ADMIN:
                $newRole = Model_User::ADMIN;
                $this->ajaxResponse['message'] = 'Пользователь имеет права администратора';
                $this->ajaxResponse['buttonText'] = 'Убрать права администратора';
                $this->ajaxResponse['buttonValue'] = Model_User::REGISTERED;
                break;

            case Model_User::TEACHER:
                $newRole = Model_User::TEACHER;
                $this->ajaxResponse['message'] = 'Установлен статус учителя';
                $this->ajaxResponse['buttonText'] = 'Не преподаватель';
                $this->ajaxResponse['buttonValue'] = Model_User::REGISTERED;
                break;

            case Model_User::REGISTERED:
            default:
                $newRole = Model_User::REGISTERED;
                $this->ajaxResponse['message'] = 'Установлен статус простого пользователя';
                $this->ajaxResponse['buttonText'] = 'Сделать преподавателем';
                $this->ajaxResponse['buttonValue'] = Model_User::TEACHER;
                break;

        }

        return $viewUser->updateUser($viewUser->id, array(
            'role' => $newRole
        ));

    }

    public function action_changeEmail () {

        if (!$this->request->is_ajax() || !$this->user) {
            throw new HTTP_Exception_403();
        }


        $response = array(
            'success' => 0
        );

        $email = Arr::get($_POST, 'email');
        $csrf  = Arr::get($_POST, 'csrf');

        $email = trim($email);

        if (!Valid::email($email)) {
            $response['message'] = 'Введите корректный email';
            goto finish;
        }

        if ($email == $this->user->email) {
            $response['message'] = 'Этот email уже привязан к странице';
            goto finish;
        }

        if (Model_User::exists('email', $email)) {
            $response['message'] = 'Похоже, такой email уже занят';
            goto finish;
        }


        if (Security::check($csrf)) {

            $update = $this->user->updateUser($this->user->id, array(
                'email'       => $email,
                'isConfirmed' => 0
            ));

            if (!$update) {
                $response['message'] = 'Произошла ошибка при сохранении email';
                goto finish;
            }

            $model_auth = new Model_Auth($this->user);
            $model_auth->sendConfirmationEmail();

            $response['success'] = 1;

            $response['island'] = View::factory('templates/components/email_confirm_island')->render();

        }

        finish:
        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($response) );

    }
}
