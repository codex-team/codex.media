<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Base_preDispatch
{
    const TYPE_SITE_PAGE = 1;
    const TYPE_SITE_NEWS = 2;
    const TYPE_USER_PAGE = 3;

    public function action_page()
    {

        $id = $this->request->param('id');
        $uri = $this->request->param('uri');

        switch ($uri) {
            case 'add'      :
            case 'edit'     :
                self::add_page();
                break;
            case 'delete'   :
                self::delete_page();
                break;
            default         :
                self::show_page($id, $uri);
        }

    }

    public function add_page()
    {
        if (!$this->user->id && !$this->user->isAdmin(Controller_User::USER_STATUS_TEACHER)) {
            $this->redirect('/');
        }

        $page_id = Arr::get($_GET, 'id', FALSE);
        $this->view['page_type'] = Arr::get($_GET, 'type', FALSE);

        $page_parent = Arr::get($_GET, 'parent', FALSE);
        $this->view['page_parent'] = $page_parent;
        if ($page_parent != '0')
        {
            $page_parent_author = $this->methods->getPage($page_parent)['author'];

            if ($page_parent_author != $this->user->id && !$this->user->isAdmin()) {
                $this->redirect('/');
            }
        }

        $form_saved = FALSE;
        if (Security::check(Arr::get($_POST, 'csrf'))) {
            $form_saved = self::save_form();
        }
        $this->view['form_saved'] = $form_saved;

        if (!($this->view['page_type'] || $page_id)){
            $this->redirect('/');
        } elseif ($page_id) {
            $this->view['page'] = $this->methods->getPage($page_id);
        } else {
            $this->view['page'] = FALSE;
        }

        $this->template->content = View::factory('templates/page_form', $this->view);
    }

    public function delete_page()
    {
        $page_id = Arr::get($_GET, 'id', FALSE);
        $page = $this->methods->getPage($page_id);

        if ($this->user->isAdmin() || $this->user->id == $page['author']) {
            $this->methods->deletePage($page_id);
        }

        if ($page['type'] == Controller_Pages::TYPE_SITE_NEWS){
            $url = '/';
        } elseif ($page['id_parent'] != '0'){
            $url = '/page/' . $page['id_parent'];
        } else {
            $url = '/user/' . $page['author'];
        }

        $this->redirect($url);
    }

    public function show_page($id, $uri)
    {
        $page = $this->methods->getPage($id, $uri);

        if ($page) {

            $this->view['page'] = $page;
            $this->view['files'] = $this->methods->getPageFiles($page['id']);

            $this->view['page']['childrens'] = $this->methods->getChildrenPagesByParent($page['id']);

            $this->view['parent'] = FALSE;
            if ($page['id_parent']) {
                $this->view['parent'] = $this->methods->getPage($page['id_parent']);
            }

            $this->template->content = View::factory('templates/page', $this->view);

        } else {
            $this->redirect('/');
        }

    }

    public function save_form()
    {

        $type = (int)Arr::get($_POST, 'type');
        $id = (int)Arr::get($_POST, 'id');

        if ($type && Security::check(Arr::get($_POST, 'csrf'))) {
            $data = array(
                'type'          => $type,
                'author'        => $this->user->id,
                'id_parent'     => (int)Arr::get($_POST, 'id_parent', 0),
                'title'         => Arr::get($_POST, 'title'),
                'content'       => Arr::get($_POST, 'content'),
                'uri'           => Arr::get($_POST, 'uri', NULL),
                'html_content'  => Arr::get($_POST, 'html_content', NULL),
                'is_menu_item'  => Arr::get($_POST, 'is_menu_item', 0),
            );

            if ($data['title']) {

                $this->view['category'] = 'pages';

                if ($id) {
                    $page = $this->methods->updatePage($id, $data);
                    $url = '/page/' . $id;
                } else {
                    $page = $this->methods->newPage($data);
                    $url = '/page/' . $page[0];
                }


                $this->redirect($url);

            } else {

                $this->view['error'] = 'Укажите название страницы';
                return FALSE;

            }
        }
    }
}