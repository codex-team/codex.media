<?php

/**
 * Created by PhpStorm.
 * User: Egor
 * Date: 27/03/2017
 * Time: 18:23
 */
class Controller_User_Modify extends Controller_Base_preDispatch
{

    public function action_update () {



    }

    public function action_request_password_change() {

        $this->auto_render = false;

        $response = array(
            'success' => 0
        );

        $request    = json_decode(file_get_contents('php://input'));
        $password   = $request->currentPassword;
        $csrf       = $request->csrf;

        if (!Security::check($csrf)) {
            throw new HTTP_Exception_403();
        }

        if (empty($password)) {

            $response['message'] = 'Введите пароль';
            $this->response->body(json_encode($response));
            return;

        }

        if (!$this->user->checkPassword($password)) {

            $response['message'] = 'Неверный пароль';
            $this->response->body(json_encode($response));
            return;

        }


        $model_auth = new Model_Auth($this->user);

        $model_auth->sendChangePasswordEmail();

        $response = array(
            'success' => 1,
            'message' => 'Мы отправили на вашу почту письмо с подтверждением. Перейдите по ссылке в письме, чтобы установить новый пароль'
        );

        $this->response->body(json_encode($response));

    }

}