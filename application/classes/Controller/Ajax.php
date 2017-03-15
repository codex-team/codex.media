<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller_Base_preDispatch
{
    public function action_edit_file()
    {
        $action   = $this->request->param('type');
        $file_id  = (int)Arr::get($_POST, 'fid');
        $response = array("result" => "error");

        if ($file_id) {

            if ($action == 'title') {

                $title = Arr::get($_POST, 'title', '');

                if ($this->methods->updateFile($file_id , array('title'  => $title))) {

                    $response['result']  = 'ok';

                } else {

                    $response['message'] = 'no changes';
                }

                $response['new_title'] = $title;

            } elseif ($action == 'remove') {

                $response['result'] = $this->methods->updateFile($file_id , array('status' => 1)) ? 'ok' : 'error';

            } elseif ($action == 'restore') {

                $response['result'] = $this->methods->updateFile($file_id , array('status' => 0)) ? 'ok' : 'error';

            } else {

                $response['message'] = 'wrong action';
            }

        } else {

            $response['message'] = 'fid missed';
        }

        $this->auto_render = false;
        $this->response->body(@json_encode($response) );
    }

    public function action_send_confirmation_email() {

        $model_auth = new Model_Auth();

        $isSucces = $model_auth->sendConfirmationEmail($this->user);

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
