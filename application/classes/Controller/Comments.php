<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comments extends Controller_Base_preDispatch
{

	public function action_add()
    {

        $comment = new Model_Comments();

        $comment->page_id   = Arr::get($_POST, 'page_id');
        $comment->text      = Arr::get($_POST, 'text');
        $comment->parent_id = Arr::get($_POST, 'parent_id', '0');
        $comment->author    = $this->user->id;

        /**
         * Определяет уровень комментария.
         * @author Vitaly Guryn
         */
        /*if ($comment->parent_id != 0){
            $parent_comment = Model_Comments::get($comment->parent_id);
            if ($parent_comment->parent_id != 0) {
                $comment->root_id = $parent_comment->root_id;
            } else {
                $comment->root_id = $parent_comment->id;
            }
        } else {
            $comment->root_id = 0;
        }*/
        $comment->root_id = 0;

        $comment->insert();

        $this->redirect('/page/'.$comment->page_id);
    }
    
    public function action_delete()
    {
        $comment_id = $this->request->param('comment_id');

        $comment = Model_Comment::get($comment_id);

        $article_id = $comment->delete_comment($this->user);

        $this->redirect('/article/' . $article_id);
    }

}