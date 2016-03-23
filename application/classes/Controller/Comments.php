<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comments extends Controller_Base_preDispatch
{

    public function action_add()
    {

        $comment = new Model_Comment();
        
        $page = new Model_Page($this->request->param('id'));
        
        $text = trim(Arr::get($_POST, 'text'));
        
        if ($page->id != 0 && $this->user->id !=0 && $text != "") {
            
            $comment->page_id              = $this->request->param('id');
            $comment->text                 = $text;
            $comment->parent_comment['id'] = Arr::get($_POST, 'parent_id', '0');
            $comment->author['id']         = $this->user->id;
            $comment->root_id              = 0;

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