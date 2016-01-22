<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comments extends Controller_Base_preDispatch
{

    public function action_add()
    {

        $comment = new Model_Comment();
        
        $page = new Model_Page($this->request->param('id'));
        
        if ($page->id != 0 && $this->user->id !=0) {
            $comment->page_id   = $this->request->param('id');
            $comment->text      = Arr::get($_POST, 'text');
            $comment->parent_id = Arr::get($_POST, 'parent_id', '0');
            $comment->author    = $this->user->id;
            $comment->root_id   = 0;

            $comment->insert();
        }

        $this->redirect('/p/'.$page->id.'/'.$page->uri);
    }
    
    public function action_delete()
    {
        $comment_id = $this->request->param('comment_id');

        $comment = Model_Comment::get($comment_id);

        if ($comment->author == $this->user->id || $this->user->isAdmin)
        {
            $comment->delete();
        }
        
        $page = new Model_Page($comment->page_id);

        $this->redirect('/p/'.$page->id.'/'.$page->uri);
    }

}