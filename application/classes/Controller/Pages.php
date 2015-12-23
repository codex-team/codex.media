<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{
    const TYPE_PAGE = 1;
    const TYPE_NEWS = 2;
    const TYPE_BLOG = 3;

    public function action_page()
    {
        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        $page = $this->methods->getPage( $id , $uri );

        if ( $page ) {

            $this->view['page']  = $page;
            $this->view['files'] = $this->methods->getPageFiles( $page['id'] );

            $this->view['page']['childrens'] = $this->methods->getChildrenPagesByParent( $page['id'] );

            $this->view['comments'] = Model_Comments::getByPageId($page['id']);
            
            $this->template->content = View::factory('templates/page',  $this->view);
        
        } else{
            $this->redirect('/');
        }


    }

}