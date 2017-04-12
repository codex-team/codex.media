<?php

class Controller_User_Index extends Controller_Base_preDispatch
{
    const LIST_PAGES    = 'pages';
    const LIST_COMMENTS = 'comments';
    const ELEMS_IN_LIST = 7;

    public function action_profile()
    {
        $user_id = $this->request->param('id');
        $list = $this->request->param('list') ?: self::LIST_PAGES;
        $pageNumber = $this->request->param('page_number') ?: 1;

        $viewUser = new Model_User($user_id);

        if (!$viewUser->id) {
            throw HTTP_Exception::factory(404);
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

        $this->view['emailConfirmed'] = Arr::get($_GET, 'confirmed', 0);

        $this->template->title   = $viewUser->name;
        $this->template->content = View::factory('/templates/users/profile', $this->view);

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

}
