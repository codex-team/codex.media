<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{
    public function action_profile()
    {
        $user_id = $this->request->param('id');

        $new_status = Arr::get($_GET, 'newStatus');

        $viewUser = new Model_User($user_id);

        if ($this->user->isAdmin && $new_status) {

            $this->view['setUserStatus'] = $viewUser->setUserStatus(self::translate_user_status($new_status));
        }

        $viewUser->isMe = $viewUser->id == $this->user->id;

        $this->view['userPages'] = $viewUser->getUserPages();
        $this->view['viewUser']  = $viewUser;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/users/profile', $this->view);
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

            case 'registered' :
                $status = Model_User::USER_STATUS_REGISTERED;
                break;

            default :
                return FALSE;
        }

        return $status;
    }

    public function action_settings()
    {
        $succesResult = false;
        $error = array();
        $csrfToken = Arr::get($_POST, 'csrf');

        if (Security::check($csrfToken)) {

            $newEmail        = trim(Arr::get($_POST, 'email'));
            $currentPassword = trim(Arr::get($_POST, 'current_password'));
            $newPassword     = trim(Arr::get($_POST, 'new_password'));
            $repeatPassword  = trim(Arr::get($_POST, 'repeat_password'));
            $newPhone        = trim(Arr::get($_POST, 'phone'));
            $newAva          = Arr::get($_FILES, 'new_ava');

            if ($currentPassword) {

                $hashedCurrentPassword = Controller_Auth_Base::createPasswordHash($currentPassword);

            } else {

                $hashedCurrentPassword = Controller_Auth_Base::createPasswordHash($newPassword);
            }

            if ($hashedCurrentPassword != $this->user->password && $currentPassword) {

                $error['currPassError'] = 'Неправильный текущий пароль.';
                $newPassword = '';
            }

            if ($newPassword != $repeatPassword) {

                $newPassword = '';
                $error['passError'] = 'Пароли не совпадают.';
            }

            if (Upload::valid($newAva) && Upload::not_empty($newAva) && Upload::size($newAva, '8M')) {

                $this->user->saveAvatar($newAva, 'upload/profile/');
            }

            $fields = array(
                'email'    => $newEmail,
                'phone'    => $newPhone);

            if (!$error) $fields['password'] = Controller_Auth_Base::createPasswordHash($newPassword);

            //если пустое поле, то не заносим его в базу и модель, за исключением телефона
            foreach ($fields as $key => $value) {

                if (!$value && $key != 'phone') unset($fields[$key]);
            }

            if ($this->user->updateUser($this->user->id, $fields)) {

                $succesResult = (!$error) ? true : false;
            }
        }

        $this->view['error']     = $error;
        $this->view['success']   = $succesResult;

        $this->template->content = View::factory('/templates/users/settings', $this->view);
    }
}
