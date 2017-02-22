<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comments extends Controller_Base_preDispatch
{
    public function action_add()
    {
        $error = '';

        /**
         * Checking for authorized user
         */
        if ($this->user->status < Model_User::USER_STATUS_REGISTERED) {
            $error = 'Ошибка доступа';
            goto finish;
        }
        /***/

        /**
         * Checking for existing page
         */
        $page = new Model_Page($this->request->param('id'));
        if (!$page->id) {
            $error = 'Неверный номер страницы';
            goto finish;
        }
        /***/

        /**
         * Checking for existing text
         */
        $text = trim(nl2br(Arr::get($_POST, 'comment_text')));
        if (!$text) {
            $error = 'Отсутствует текст комментария';
            goto finish;
        }
        /***/

        $comment = new Model_Comment();
        $comment->page_id              = $this->request->param('id');
        $comment->text                 = $text;
        $comment->parent_comment['id'] = Arr::get($_POST, 'parent_id', '0');
        $comment->author['id']         = $this->user->id;
        $comment->root_id              = Arr::get($_POST, 'root_id', '0');
        $comment->insert();


        finish:

        if (Model_Methods::isAjax()) {

            $response = array();

            if ($error) {
                $response['success'] = 0;
                $response['error'] = $error;
            } else {
                $response['success'] = 1;
                $response['comment'] = isset($comment) ? $comment : null;
            }

            $this->auto_render = false;
            $this->response->headers('Content-Type', 'application/json; charset=utf-8');
            $this->response->body( json_encode($response) );

            return true;

        }

        if ($error) {
            $this->redirect( '/p/' . $page->id . '/' . $page->uri . '?error=' . $error );
        } else {
            $this->redirect( '/p/' . $page->id . '/' . $page->uri );
        }
    }

    public function action_delete()
    {
        $comment_id = $this->request->param('comment_id');

        $comment = Model_Comment::get($comment_id);

        if ($comment->author->id == $this->user->id || $this->user->isAdmin) {
            $comment->delete(true);
        }

        $page = new Model_Page($comment->page_id);

        $this->redirect('/p/' . $page->id . '/' . $page->uri);
    }
}
