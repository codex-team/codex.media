<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{

    public function action_profile()
    {
        $user_id = $this->request->param('id');

        $new_status = Arr::get($_GET, 'newStatus');

        $viewUser = new Model_User($user_id);

        if ($this->user->isAdmin && $new_status)
        {
            $this->view['setUserStatus'] = $viewUser->setUserStatus(self::translate_user_status($new_status));
        }

        $this->view['userPages'] = $viewUser->getUserPages();
        $this->view['viewUser']  = $viewUser;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);

    }

    public function translate_user_status($act)
    {
        switch ($act) {
            case 'teacher'    :
                $status = Model_User::USER_STATUS_TEACHER;
                break;
            case 'banned'     :
                $status = Model_User::USER_STATUS_BANNED;
                break;
            case 'registered'   :
                $status = Model_User::USER_STATUS_REGISTERED;
                break;
            default        :
                return FALSE;
        }
        return $status;
    }

    public function action_settings()
    {
        $succesResult = false;
        $error = '';
        $csrfToken = Arr::get($_POST, 'csrf');
        
        if (Security::check($csrfToken)){
            
            $newEmail        = Arr::get($_POST, 'new_email');
            $currentPassword = Arr::get($_POST, 'current_password');
            $newPassword     = Arr::get($_POST, 'new_password');
            $repeatPassword  = Arr::get($_POST, 'repeat_password');
            $newPhone        = Arr::get($_POST, 'phone_number');
            $newAva          = Arr::get($_FILES, 'new_ava');


            if ($newPassword != $repeatPassword){
                $newPassword = '';
                $error = 'Пароли не совпадают. ';
            }

            if ($currentPassword){
                $currentPassword = Controller_Auth_Base::createPasswordHash($currentPassword);
                $error = ($currentPassword != $this->user->password) ? $error . 'Неправильный текущий пароль.': '';
                $newPassword = '';
            }

            if (Upload::valid($newAva) && Upload::not_empty($newAva) && Upload::size($newAva, '8M')){
                $this->user->saveAvatar($newAva, 'upload/profile/');
            }

            $fields = array(
                'email'    => $newEmail,
                'password' => $newPassword,
                'phone'    => $newPhone);

            //если пустое поле, то не заносим его в базу и модель, за исключением телефона    
            foreach ($fields as $key => $value){
                if (!$value && $key != 'phone') unset($fields[$key]);
            }

            if ($fields){
                if ( $this->user->updateUser($this->user->id, $fields) ){
                    $succesResult = (!$error) ? true : false;
                }
            }            
        }
        
        //создаем объект модели, чтобы обновить кэш и сразу вывести изменения
        $viewUser = new Model_User($this->user->id);
        
        if ($viewUser->id != 0){
            $this->view['viewUser']  = $viewUser;
            $this->view['error']     = $error;
            $this->view['success']   = $succesResult;
            $this->view['userPages'] = $viewUser->getUserPages($viewUser->id);
            $this->template->content = View::factory('/templates/user/settings', $this->view);
        }
    }
}
