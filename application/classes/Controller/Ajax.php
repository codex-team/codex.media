<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller_Base_preDispatch
{
    public function action_send_confirmation_email()
    {
        $model_auth = new Model_Auth(array(
            "id"    => $this->user->id,
            "name"  => $this->user->name,
            "email" => $this->user->email
        ));

        $isSucces = $model_auth->sendEmail(Model_Auth::TYPE_EMAIL_CONFIRM);

        $message = $isSucces?'Письмо отправлено':'Во время отправки письма произошла ошибка';
        $result  = $isSucces?'ok':'error';

        $response = array(
            "result"    => $result,
            "message"   =>  $message
        );

        $this->auto_render = false;
        $this->response->body(@json_encode($response));
    }
}
