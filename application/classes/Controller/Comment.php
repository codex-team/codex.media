<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comment extends Controller_Base_preDispatch
{

	public function action_add()
    {

        $comment = new Model_Comments();
        
        $page = new Model_Page($this->request->param('id'));
        
        if ($page->id != 0) {
            $comment->page_id   = $this->request->param('id');
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
        }

        $this->redirect('/p/'.$page->id.'/'.$page->uri);
    }
    
    public function action_delete()
    {
        $comment_id = $this->request->param('comment_id');

        $comment = Model_Comments::get($comment_id);

        $article_id = $comment->delete_comment($this->user);
        
        $page = new Model_Page($comment->page_id);

        $this->redirect('/p/'.$page->id.'/'.$page->uri);
    }

}