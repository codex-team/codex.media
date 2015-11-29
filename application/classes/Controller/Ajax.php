<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Ajax extends Controller_Base_preDispatch {
    public function action_file_uploader()
    {
        $response = array("result" => "error");
        $data     = array();
        $id        = (int)Arr::get($_POST , 'id' , 0);
        $action    = Arr::get($_POST , 'action' , false);
        $fieldName = Arr::get($_POST , 'name' , 0);
        $files     = Arr::get($_FILES, 'files');
        // Always return filename on success
        if ( $action && $id ){
            if ( $files && Upload::not_empty($files) && Upload::valid($files) ){
                if ( Upload::size($files, '30M') ){
                    switch ($action) {
                        case 'pageFiles':
                            $filename = 'test';
                            $response['callback'] = 'callback.uploadpageFile.success(1, "'.$filename.'")';
                            // $filename = $this->methods->saveImage( $files , 'upload/startups/' );
                            // if ( $filename ){
                            //     $startup = new Model_Startup($id);
                            //     $startup->editLogo($filename);
                            //     $response['callback'] = 'callback.uploadStartupLogo.success(1, "'.$filename.'")';
                            // }
                        break;
                        default: $response['message'] = 'Wrong action'; break;
                    }
                } else {
                    $response['message'] = 'File size exceeded limit';    
                }
            } else {
                $response['message'] = 'File is missing or damaged';
            }
        } else {
            $response['message'] = 'Action or id missed';
        }
        /*
        if ( $file = Arr::get($_FILES, 'files') ) {
            $sizes = $name == 'cover_img' ? array( 'l' => 500 ) : null ;
            if ($filename = Controller_Weekly::newOrEditPhoto($file, $sizes, 'weekly')) {
                $data[$name] = $filename;
            }
            $filename = null;
        }
        if ( $wid && $data ){
            $response['result']   = $this->methods->updateWeeklyById( $wid , $data );
            $response[$name] = '/upload/weekly/l_' . $data[$name];
        }
        */
        $script = '<script>window.parent.transport.response(' . @json_encode($response) . ')</script>';
        $this->auto_render = false;
        $this->response->body($script);
    }
    public function action_edit_file()
    {
        $action   = $this->request->param('type');
        $file_id  = (int)Arr::get($_POST, 'fid');
        $response = array("result" => "error");
        if ($file_id ) {    
            if ($action == 'title') {
                $title = Arr::get($_POST, 'title', '');
                if ( $this->methods->updateFile( $file_id , array( 'title'  => $title )) ){
                    $response['result']  = 'ok';
                } else {
                    $response['message'] = 'no changes';
                }
                $response['new_title'] = $title;
            } elseif ($action == 'remove' ){
                $response['result'] = $this->methods->updateFile( $file_id , array( 'status'  => 1 )) ? 'ok' : 'error';
            } elseif ($action == 'restore' ) {
                $response['result'] = $this->methods->updateFile( $file_id , array( 'status'  => 0 )) ? 'ok' : 'error';
            } else {
                $response['message'] = 'wrong action';    
            }
            
        } else {
            $response['message'] = 'fid missed';
        }
        
        $this->auto_render = false;
        $this->response->body(@json_encode($response) );
    }
}