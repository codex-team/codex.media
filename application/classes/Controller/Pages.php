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

        switch ($uri){
            case 'add'  :
            case 'edit' : self::add_page(); break;
            default     : self::get_page($id, $uri);
        }

    }

    /**
     * @author Taly
     */
    public function add_page()
    {
        if (!$this->user->id){
            $this->redirect('/');
        }



        $this->view['page'] = FALSE;

        $this->view['page_type'] = Arr::get($_GET, 'type', FALSE);
        $this->view['page_parent'] = Arr::get($_GET, 'parent', FALSE);

        $this->view['category'] = 'index';

        $form_saved = FALSE;

        if ( Security::check(Arr::get($_POST, 'csrf')) ) {
            $form_saved = self::pages();
        }

        $this->view['form_saved'] = $form_saved;

        if ($this->view['page_type']) {
            $this->template->content = View::factory('templates/page_form', $this->view);
        } else {
            $this->redirect('/');
        }
    }

    /**
     * @author Taly
     */
    public function get_page($id, $uri)
    {
        $page = $this->methods->getPage($id, $uri);

        if ($page) {

            $this->view['page'] = $page;
            $this->view['files'] = $this->methods->getPageFiles($page['id']);

            $this->view['page']['childrens'] = $this->methods->getChildrenPagesByParent($page['id']);

            $this->template->content = View::factory('templates/page', $this->view);

        } else {
            $this->redirect('/');
        }

    }

    /**
     * @author Taly
     */
    public function pageForm()
    {

        $type = (int)Arr::get($_POST, 'type');
        $id   = (int)Arr::get($_POST, 'id');

        if ( $type && Security::check(Arr::get($_POST, 'csrf')) )
        {
            $data = array(
                'type'         => $type,
                'author'       => $this->user->id ,
                'id_parent'    => (int)Arr::get($_POST, 'id_parent' , 0),
                'title'        => Arr::get($_POST, 'title'),
                'content'      => Arr::get($_POST, 'content'),
                'uri'          => Arr::get($_POST, 'uri', NULL),
                'html_content' => Arr::get($_POST, 'html_content', NULL),
                'is_menu_item' => Arr::get($_POST, 'is_menu_item', 0),
            );

            if ( $data['title'] )
            {

                $this->view['category'] = 'pages';

                if ($id) {
                    $page = $this->methods->updatePage( $id , $data );

                } else {
                    $page = $this->methods->newPage( $data );
                }

                #$url = '/page/' . $page['id'];
                $url = '/';                                 // TODO: get new page id
                $this->redirect($url);

            } else {

                $this->view['error'] = 'Укажите название страницы';
                return FALSE;

            }
        }
    }

    /**
     * @author Taly
     */
    public function pages( $page_type = NULL)
    {
//        $pageId = $this->view['pageId'] = $this->request->param('id');
//        $pages = $this->methods->getPages($page_type);
//
//        if ($pageId) {
//
//            $this->view['pages'] = $pages;
//            $this->view['page'] = $this->methods->getPage($pageId);
//            $this->view['files'] = $this->methods->getPageFiles($pageId);
//            if (isset($this->view['page']['title'])) $this->view['title'] = $this->view['page']['title'];
//
//        } else {
//
//            foreach ($pages as $id => $page) {
//                $pages[$id]['parent'] = $page['id_parent'] ? $this->methods->getPage($page['id_parent']) : array();
//            }
//
//            $this->view['pages'] = $pages;
//
//        }
//
//
//        $this->view['page_type'] = $page_type;
        return self::pageForm();
    }
}