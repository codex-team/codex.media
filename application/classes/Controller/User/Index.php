<?php

/**
 * Created by PhpStorm.
 * User: Egor
 * Date: 27/03/2017
 * Time: 18:06
 */
class Controller_User_Index extends Controller_Base_preDispatch
{

    public function action_profile() {

        $user_id = $this->request->param('id');
        $list = $this->request->param('list') ?: 'pages';

        $viewUser = new Model_User($user_id);

        if (!$viewUser->id) {
            throw HTTP_Exception::factory(404);
        }

        $viewUser->isMe = $viewUser->id == $this->user->id;
        $this->view['viewUser']  = $viewUser;

        switch ($list) {
            case 'comments':
                $this->view['userComments'] = Model_Comment::getCommentsByUserId($user_id);
                break;

            default:
                $this->view['userPages'] = $viewUser->getUserPages();
                break;
        }

        $this->view['list']      = $list;
        $this->view['listFactory'] = View::factory('/templates/users/' . $list, $this->view);

        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/users/profile', $this->view);

    }

    public function action_settings()
    {

        if (!$this->user->id) {
            throw new HTTP_Exception_403();
        }

        $this->template->content = View::factory('/templates/users/settings', $this->view);

    }
}