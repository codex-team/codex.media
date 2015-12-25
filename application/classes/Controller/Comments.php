<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Comments extends Controller_Base_preDispatch
{

	public function action_add()
    {

        $comment = new Model_Comments();
        
        $target = $comment->page_id;

        $comment->page_id 	= Arr::get($_GET, 'page_id');
        $comment->text 		= Arr::get($_GET, 'text');
        $comment->parent_id	= Arr::get($_GET, 'parent_id', '0');
        $comment->user_id	= $this->user->id;

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

}