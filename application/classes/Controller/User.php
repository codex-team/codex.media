<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Base_preDispatch
{
    const LIST_PAGES    = 'pages';
    const LIST_COMMENTS = 'comments';
    const ELEMS_IN_LIST = 7;

    public function action_profile()
    {
        $user_id = $this->request->param('id');
        $list = $this->request->param('list') ?: self::LIST_PAGES;
        $pageNumber = $this->request->param('page_number') ?: 1;

        $new_status = Arr::get($_GET, 'newStatus');

        $viewUser = new Model_User($user_id);

        if (!$viewUser->id) {
            throw HTTP_Exception::factory(404);
        }

        if ($this->user->isAdmin && $new_status) {

            $this->view['isUpdateSaved'] = $viewUser->setUserStatus(self::translate_user_status($new_status));
        }

        $viewUser->isMe = $viewUser->id == $this->user->id;
        $this->view['viewUser']  = $viewUser;

        $offset = ($pageNumber - 1) * self::ELEMS_IN_LIST;
        switch ($list) {

            case self::LIST_COMMENTS:
                $userFeed = Model_Comment::getCommentsByUserId($viewUser->id, $offset, self::ELEMS_IN_LIST + 1);
                break;

            case self::LIST_PAGES:
                $userFeed = $viewUser->getUserPages($offset, self::ELEMS_IN_LIST + 1);
                break;

            default:
                $userFeed = array();
                break;
        }

        /** If next page exist we need to unset last elem */
        $nextPage = Model_Methods::isNextPageExist($userFeed, self::ELEMS_IN_LIST);

        if ($nextPage) unset($userFeed[self::ELEMS_IN_LIST]);
        /***/

        /** If ajax request */
        if (Model_Methods::isAjax()) {

            $this->ajax_pagination($list, $userFeed, $nextPage);
            return;
        }
        /***/

        $this->view['user_feed']   = $userFeed;
        $this->view['next_page']   = $nextPage;
        $this->view['page_number'] = $pageNumber;

        $this->view['list']        = $list;
        $this->view['listFactory'] = View::factory('/templates/users/' . $list, $this->view);

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

    /**
     * @todo Fully rewrite
     */
    public function action_settings()
    {

        if (!$this->user->id) {
            throw new HTTP_Exception_403();
        }

        $succesResult = false;
        $error = array();
        $csrfToken = Arr::get($_POST, 'csrf');

        if (Security::check($csrfToken)) {

            $newEmail        = trim(Arr::get($_POST, 'email'));
            $currentPassword = trim(Arr::get($_POST, 'current_password'));
            $newPassword     = trim(Arr::get($_POST, 'new_password'));
            $repeatPassword  = trim(Arr::get($_POST, 'repeat_password'));
            $newPhone        = trim(Arr::get($_POST, 'phone'));

            $bio              = trim(Arr::get($_POST, 'bio'));
            $name             = trim(Arr::get($_POST, 'name'));

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

            $fields = array(
                'email'    => $newEmail,
                'phone'    => $newPhone,
                'name'     => $name,
                'bio'      => $bio
            );

            if (!$error) {

                $fields['password'] = Controller_Auth_Base::createPasswordHash($newPassword);
            }

            //если поле пустое, то не заносим его в базу и модель, за исключением некоторых

            $allowEmpty = array('bio', 'phone');

            foreach ($fields as $key => $value) {

                if (!$value && !in_array($key, $allowEmpty)) unset($fields[$key]);

            }

            if ($this->user->updateUser($this->user->id, $fields)) {

                $succesResult = (!$error) ? true : false;

            }
        }

        $this->view['error']     = $error;
        $this->view['success']   = $succesResult;

        $this->template->content = View::factory('/templates/users/settings', $this->view);
    }

    private function ajax_pagination($type, $models, $nextPage = false)
    {
        $response = array();
        $response['success'] = 1;

        switch ($type) {
            case self::LIST_COMMENTS:
                $response['list'] = View::factory('templates/users/comments', array('user_feed' => $models))->render();
                break;

            case self::LIST_PAGES:
                $response['list'] = View::factory('templates/users/pages', array('user_feed' => $models))->render();
                break;

            default:
                $response['list'] = '';
                break;
        }

        $response['next_page']  = $nextPage;

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($response) );
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

            if ($saving) {
                $response['success'] = 1;
                $response['bio']     = $bio;
                $response['csrf']    = Security::token(true);
            }

        }

        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json; charset=utf-8');
        $this->response->body( json_encode($response) );
    }
}
