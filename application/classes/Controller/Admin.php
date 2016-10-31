<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller_Base_preDispatch {

    public static $categories = array('pages', 'page', 'index', 'news', 'users', 'parser', 'base');

    public function action_index() {

        if (!$this->user->id){

            $this->redirect('/');

        }

        $this->title = $this->view['title'] = 'Панель управления сайтом';

        $page = $this->view['category'] = $this->request->param('page');

        $form_saved = FALSE;

        switch ($page) {

            case 'pages'  : $form_saved = self::pages(Model_Page::TYPE_SITE_PAGE); break;
            case 'news'   : $form_saved = self::pages(Model_Page::TYPE_SITE_NEWS); break;
            case 'users'  : self::users(); break;
            case 'base'   : self::siteSettings(); break;
            case 'parser' : self::parser(); break;
            case 'index'  : default : self::adminIndexPage();

        }

        $this->view['form_saved'] = $form_saved;
        $this->template->content = View::factory('templates/admin/layout', $this->view);

    }

    public function parser() {

        $this->view['category'] = 'parser';

    }

    public function adminIndexPage() {

        $this->view['category'] = 'index';

    }

    public function users() {

        $this->view['users'] = $this->methods->getUsers();

    }

    /**
     *
     * @author taly
     */
    public function siteSettings() {

        if (Security::check(Arr::get($_POST, 'csrf'))) {

            $data = array(
                'title'         => Arr::get($_POST, 'title'),
                'city'          => Arr::get($_POST, 'city'),
                'full_name'     => Arr::get($_POST, 'full_name'),
                'description'   => Arr::get($_POST, 'description'),
                'address'       => Arr::get($_POST, 'address'),
                'coordinates'   => Arr::get($_POST, 'coordinates'),
                'phone'         => Arr::get($_POST, 'phone'),
                'fax'           => Arr::get($_POST, 'fax'),
                'email'         => Arr::get($_POST, 'email'),
                'logo'          => Arr::get($_POST, 'logo'),
            );

            if ($this->methods->saveSiteInfo($data)) {

                $this->view['site_info'] = $this->methods->getSiteInfo();

            }

        }

        $this->view['category'] = 'base';
    }

    public function pageForm() {

        $type = (int)Arr::get($_POST, 'type');
        $id   = (int)Arr::get($_POST, 'id');

        if ($type && Security::check(Arr::get($_POST, 'csrf'))) {

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

            if ($data['title']) {

                $this->view['category'] = 'pages';

                if ($id) {

                    return $this->methods->updatePage( $id , $data );

                } else {

                    return $this->methods->newPage( $data );

                }

            } else {

                $this->view['error'] = 'Укажите название страницы';
                return FALSE;

            }

        }
        
    }

    public function pages($page_type = NULL) {

        $pageId = $this->view['pageId'] = $this->request->param('id');
        $pages  = $this->methods->getPages( $page_type );

        if ($pageId) {

            $this->view['pages'] = $pages;
            $this->view['page']  = $this->methods->getPage( $pageId );
            $this->view['files'] = $this->methods->getPageFiles( $pageId );

            if (isset($this->view['page']['title'])) $this->view['title'] = $this->view['page']['title'];

        } else {

            foreach ($pages as $id => $page) {

                $pages[$id]['parent'] = $page['id_parent'] ? $this->methods->getPage( $page['id_parent'] ) : array();

            }

            $this->view['pages'] = $pages;

        }

        $this->view['page_type'] = $page_type;

        return self::pageForm();
    }

    public function action_file_uploader() {

        $response = array("result" => "error");

        $page_id   = (int)Arr::get($_POST , 'page_id' , 0);
        $csrf      = Arr::get($_POST , 'csrf' , false);
        $title     = Arr::get($_POST , 'title' , 0);
        $file      = Arr::get($_FILES, 'file');


        if ($page_id) {

            if ($file && Upload::not_empty($file) && Upload::valid($file)) {

                if (Upload::size($file, '30M')) {

                        if (Upload::type($file, array('jpg', 'jpeg', 'png', 'gif'))) {

                            $filename = $this->methods->saveImage( $file , 'upload/page_images/' );

                        } else {

                            $filename = $this->methods->saveFile( $file , 'upload/page_files/' );

                        }

                        if ($filename) {

                            $data = array(
                                'page'      => $page_id,
                                'filename'  => $filename,
                                'title'     => $title ? $title : $this->rus_lat($file['name']),
                                'author'    => $this->user->id,
                                'size'      => $file['size'] / 1000,
                                'extension' => strtolower(pathinfo($filename, PATHINFO_EXTENSION)),
                            );

                            if ($data['id'] = $this->methods->addFileToPage($data)) {

                                $response['result']   = 'success';
                                $new_file_row = View::factory('templates/admin/file_row', array( 'file' => $data ) )->render();
                                $response['callback'] = 'callback.uploadpageFile.success(' . json_encode($new_file_row) . ')';

                            }

                        } else {

                            $response['message'] = 'Error while saving';

                        }

                } else {

                    $response['message'] = 'File size exceeded limit';

                }

            } else {

                $response['message'] = 'File is missing or damaged';

            }

        } else {

            $response['message'] = ' Page id missed';

        }

        $script = '<script>window.parent.transport.response(' . @json_encode($response) . ')</script>';

        $this->auto_render = false;
        $this->response->body($script);

    }

}
