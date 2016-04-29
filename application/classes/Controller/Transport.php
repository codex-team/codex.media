<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transport extends Controller_Base_preDispatch {

    private $transportResponse = array(
        'success' => 0
    );

    private $action = null;
    private $files    = null;

    /**
    * File transport module
    */
    public function action_file_uploader()
    {
        $this->action = Arr::get($_POST , 'action' , false);
        $this->files    = Arr::get($_FILES, 'files');

        if ( !$this->action ){

            $this->transportResponse['message'] = 'Transport action missed'; 
            goto finish;

        }
        
        if ( !$this->files || !Upload::not_empty($this->files) || !Upload::valid($this->files) ){
            
            $this->transportResponse['message'] = 'File is missing or damaged';
            goto finish;
        
        }

        if ( !Upload::size($this->files, '30M') ){

            $this->transportResponse['message'] = 'File size exceeded limit';
            goto finish;

        }
                
        switch ($this->action) {
            case 'pageFile':
               
                $filename = $this->savePageFile();
                if ($filename) {
                    $this->transportResponse['success'] = 1;
                }
                
                $this->transportResponse['filename'] = $filename;

            break;

            default: $this->transportResponse['message'] = 'Wrong action'; break;
        }


        finish: 

        $script = '<script>window.parent.codex.transport.response(' . @json_encode($this->transportResponse) . ')</script>';

        $this->auto_render = false;
        $this->response->body($script);



    }

    private function savePageFile()
    {
        if (Upload::type($this->files, array('jpg', 'jpeg', 'png', 'gif'))){
            $filename = $this->methods->saveImage( $this->files , 'upload/page_images/' );
        } else {
            $filename = $this->methods->saveFile( $this->files , 'upload/page_files/' );
        }

        if ( !$filename ){
            $this->transportResponse['message'] = 'Error while saving';
            return false;   
        }

        return $filename;

        // $data = array(
        //     'page'      => $page_id,
        //     'filename'  => $filename,
        //     'title'     => $title ? $title : $this->rus_lat($file['name']),
        //     'author'    => $this->user->id,
        //     'size'      => $file['size'] / 1000, 
        //     'extension' => strtolower(pathinfo($filename, PATHINFO_EXTENSION)),
        // );
                // $this->response['callback'] = 'callback.uploadpageFile.success(' . json_encode($data) . ')';

    }



}
