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
<<<<<<< HEAD
//                $this->view['success'] = $viewUser->edit(array('status' => self::USER_STATUS_TEACHER));
=======
>>>>>>> 0a1ca7dbae2175d84b7eec001ab890443a9827ad
                break;
            case 'ban'     :
                $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_BANNED);
                break;
            case 'degrade' :
            case 'unban'   :
<<<<<<< HEAD
                $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_STUDENT);
                break;
        }
        
        if (!$viewUser->id) $this->redirect('/');
        
=======
                $this->view['success'] = $viewUser->setUserStatus(self::USER_STATUS_BANNED);
                break;
        }

>>>>>>> 0a1ca7dbae2175d84b7eec001ab890443a9827ad
        $this->view['userPages'] = $viewUser->getUserPages($uid);
        $this->view['viewUser']  = $viewUser;
        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/user/profile', $this->view);


    }
    public function action_settings()
    {
        $csrfToken = Arr::get($_POST, 'csrf');
        if(!Security::check($csrfToken)){
        
            $viewUser = $this->user;
            if ($viewUser->id != 0){
                $this->view['viewUser'] = $viewUser;
                $this->view['success'] = false;
                $this->view['userPages'] = $viewUser->getUserPages($viewUser->id);
                $this->template->content = View::factory('/templates/user/settings', $this->view);
                
            } else { $this->redirect('/'); }
        } else { 
            
            if (Arr::get($_POST, 'submit_email')){
                $newEmail = Arr::get($_POST, 'new_email');
                
                if (!empty($newEmail)){
                    $this->user->edit(array('email' => $newEmail));
                    $this->view['success'] = true;
                }
            }
            if (Arr::get($_POST, 'submit_password')){
                $newPassword = Arr::get($_POST, 'new_password');
                $repeatPassword = Arr::get($_POST, 'repeat_password');
                
                //TODO красивый вывод об ошибке               
                $this->user->updatePassword($newPassword, $repeatPassword);
            }         
            if (Arr::get($_POST, 'submit_ava')){
                $file = Arr::get($_FILES, 'new_ava');
                
                if (Upload::valid($file) && Upload::not_empty($file) && Upload::size($file, '8M')){
                    $correctSaveImage = $this->user->saveAvatar($file, 'upload/profile/');
                }
            }
            $this->redirect('user/settings');
        }
    }
}





