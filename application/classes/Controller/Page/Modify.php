<?php

class Controller_Page_Modify extends Controller_Base_preDispatch
{

    public function action_save() {

        /** check permissions for cteate or edit subpage */
        $page_parent = (int) Arr::get($_POST, 'parent', Arr::get($_GET, 'parent', 0));
        $parent = new Model_Page($page_parent);
        $is_valid_parent = $parent->id != 0 ? $this->user->id == $parent->author->id : true;

        /** check permissions for edit */
        $page_id = (int) Arr::get($_POST, 'id', Arr::get($_GET, 'id', 0));
        $page = new Model_Page($page_id);
        $is_valid_author = $page_id ? $this->user->id == $page->author->id : true;

        if (!$this->user->id || !$is_valid_parent || !$is_valid_author) {

            self::error_page('Недостаточно прав для создания или редактирования страницы сайта');
            return FALSE;
        }


    }

}