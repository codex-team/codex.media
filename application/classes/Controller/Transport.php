<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transport extends Controller_Base_preDispatch
{
    private $transportResponse = array(
        'success' => 0
    );

    private $type  = null;
    private $files = null;

    /**
     * Codex Editor Attaches tool server-side
     */
    public function action_upload()
    {
        $this->files = Arr::get($_FILES, 'files');
        $this->type  = Model_File::EDITOR_FILE;

        $file = new Model_File();

        if ( !$this->check() ){
            goto finish;
        }

        $filename = $file->upload($this->type, $this->files);

        if ( $filename ) {

            $this->transportResponse['success'] = 1;
            $this->transportResponse['data'] = array(
                'url'       => $filename,
                'name'      => 'File title',
                'extension' => 'jpg',
                'size'      => 2000
            );
        } else {
            $this->transportResponse['message'] = 'Error while uploading';
        }

        finish:
        $response = @json_encode($this->transportResponse);

        $this->auto_render = false;
        $this->response->body($response);

    }

    /**
     * Make necessary verifications
     * @return Boolean
     */
    private function check()
    {
        if (!$this->user->id) {

            $this->transportResponse['message'] = 'Access denied';
            return false;
        }

        if (!$this->type) {

            $this->transportResponse['message'] = 'Transport type missed';
            return false;
        }

        if (!Upload::size($this->files, '2M')) {

            $this->transportResponse['message'] = 'File size exceeded limit';
            return false;
        }

        if (!$this->files || !Upload::not_empty($this->files) || !Upload::valid($this->files)){

            $this->transportResponse['message'] = 'File is missing or damaged';
            return false;
        }

        return true;

    }

    /**
    * File transport module
    */
    public function action_file_uploader()
    {
        $this->type  = Arr::get($_POST , 'type' , false);
        $this->files = Arr::get($_FILES, 'files');

        if ( !$this->check() ){
            goto finish;
        }

        $this->transportResponse['type'] = $this->type;

        $filename = $this->save();

        if ($filename) {

            $this->transportResponse['success'] = 1;

            $filename_without_ext = substr($this->files['name'], 0, strrpos($this->files['name'], '.' ));
            $title = $this->methods->getUriByTitle($filename_without_ext);

            $saved = new Model_File;
            $saved->filename  = $filename;
            $saved->title     = $title;
            $saved->author    = $this->user->id;
            $saved->size      = Arr::get($this->files, 'size', 0) / 1000;
            $saved->extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $saved->type      = $this->type;
            $saved->insert();

            $this->transportResponse['title']    = $saved->title;
            $this->transportResponse['id']       = $saved->id;
        }

        finish:
        $response = @json_encode($this->transportResponse);

        $this->auto_render = false;
        $this->response->body($response);
    }

    private function save()
    {
        $filename = null;
        $upload_path = Model_File::getUploadPathByType($this->type);

        switch ($this->type) {

            // case Model_File::PAGE_IMAGE:
            //     $filename = $this->methods->saveImage( $this->files , $upload_path );
            //     break;

            // case Model_File::PAGE_FILE:
            //     $filename = $this->methods->saveFile( $this->files , $upload_path );
            //     break;

            default:
                $this->transportResponse['message'] = 'Wrong transport type';
                return false;
        }

        if (!$filename) {

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
