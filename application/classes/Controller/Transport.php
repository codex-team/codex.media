<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transport extends Controller_Base_preDispatch {

    private $transportResponse = array(
        'success' => 0
    );

    private $type  = null;
    private $files = null;

    /**
    * Transport file types
    */
    const PAGE_FILE  = 1;
    const PAGE_IMAGE = 2;


    /**
    * File transport module
    */
    public function action_file_uploader()
    {
        $this->type  = Arr::get($_POST , 'type' , false);
        $this->files = Arr::get($_FILES, 'files');

        if ( !$this->type ){

            $this->transportResponse['message'] = 'Transport type missed';
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

        if (!$this->user->isTeacher())
        {
            $this->transportResponse['message'] = 'Access denied';
            goto finish;
        }

        $this->transportResponse['type'] = $this->type;

        switch ($this->type)
        {
            case self::PAGE_FILE:
                $filename = $this->savePageFile();
                break;

            case self::PAGE_IMAGE:
                $filename = $this->savePageFile();
                break;

            default:
                break;
        }

        if ($filename) {

            $this->transportResponse['success'] = 1;

            $title = $this->methods->getUriByTitle($this->files['name']);

            $saved_id = $this->methods->newFile(array(
                'filename'  => $filename,
                'title'     => $title,
                'author'    => $this->user->id,
                'size'      => Arr::get($this->files, 'size', 0) / 1000,
                'extension' => strtolower(pathinfo($filename, PATHINFO_EXTENSION)),
                'type'      => $this->type,
            ));

            $this->transportResponse['title']    = $title;
            $this->transportResponse['id']       = $saved_id;
            $this->transportResponse['filename'] = $filename;
        }

        finish:

        $script = '<script>window.parent.codex.transport.response(' . @json_encode($this->transportResponse) . ')</script>';

        $this->auto_render = false;
        $this->response->body($script);
    }

    private function savePageFile()
    {
        switch ($this->type)
        {
            case self::PAGE_IMAGE:
                $filename = $this->methods->saveImage( $this->files , 'upload/page_images/' );
                break;

            case self::PAGE_FILE:
                $filename = $this->methods->saveFile( $this->files , 'upload/page_files/' );
                break;

            default:
                $this->transportResponse['message'] = 'Wrong transport type';
        }

        if ( !$filename or !isset($filename) ){
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
