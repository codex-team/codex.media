<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{

    const USER_STATUS_ADMIN     = 2;
    const USER_STATUS_TEACHER   = 1;
    const USER_STATUS_STUDENT   = 0;
    const USER_STATUS_BANNED    = -1;


    public function action_profile()
    {
        $uid = $this->request->param('id');
        $act = Arr::get($_GET, 'act');

        $this->view['success'] = FALSE;

        $viewUser = new Model_User($uid);

        switch ($act) {
            case 'rise'    :
                $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_TEACHER);
                break;
            case 'ban'     :
                $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_BANNED);
                break;
            case 'degrade' :
            case 'unban'   :
                $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_BANNED);
                break;
        }

        $this->view['userPages'] = $viewUser->getUserPages($uid);
        $this->view['viewUser']  = $viewUser;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);


    }

    public function action_settings()
    {
        $succesResult = false;
        $csrfToken = Arr::get($_POST, 'csrf');
        
        if (Security::check($csrfToken)){
            
            $newEmail       = Arr::get($_POST, 'new_email');
            $newPassword    = Arr::get($_POST, 'new_password');
            $repeatPassword = Arr::get($_POST, 'repeat_password');
            $newPhone       = Arr::get($_POST, 'phone_number');
            $newAva         = Arr::get($_FILES, 'new_ava');
            
            $newPassword = ($newPassword == $repeatPassword) ? $newPassword : '';
            
            if (Upload::valid($newAva) && Upload::not_empty($newAva) && Upload::size($newAva, '8M')){
                $this->user->saveAvatar($newAva, 'upload/profile/');
            }
            
            $fields = array(
                'email'    => $newEmail,
                'password' => $newPassword,
                'phone'    => $newPhone);
                
            foreach ($fields as $key => $value){
                if (!$value && $key != 'phone') unset($fields[$key]);
            }
            
            if ($fields){
                if ($this->user->updateUser($this->user->id, $fields)){
                    $succesResult = true;
                }
            }            
        }
        
        //создаем модель, чтобы обновить кэш и сразу вывести изменения
        $viewUser = new Model_User($this->user->id);
        
        if ($viewUser->id != 0){
            $this->view['viewUser']  = $viewUser;
            $this->view['success']   = $succesResult;
            $this->view['userPages'] = $viewUser->getUserPages($viewUser->id);
            $this->template->content = View::factory('/templates/user/settings', $this->view);
        }
    }
}





