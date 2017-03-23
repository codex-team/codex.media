<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transport extends Controller_Base_preDispatch
{
    private $transportResponse = array(
        'success' => 0
    );

    private $type  = null;
    private $files = null;

    private $typesAvailable = array(
        Model_File::EDITOR_FILE,
        Model_File::EDITOR_IMAGE,
        Model_File::USER_PHOTO,
        Model_File::BRANDING
    );

    /**
     * Codex Editor Attaches tool server-side
     */
    public function action_upload()
    {
        $this->files = Arr::get($_FILES, 'files');
        $this->type  = $this->request->param('type');

        $file = new Model_File();

        if ( !$this->check() ){
            goto finish;
        }

        $uploadedFile = $file->upload($this->type, $this->files, $this->user->id);

        if ( $uploadedFile ) {
            $this->transportResponse['success'] = 1;
            $this->transportResponse['data'] = array(
                'url'       => $uploadedFile->filepath,
                'title'     => $uploadedFile->title,
                'name'      => $uploadedFile->file_hash_hex,
                'extension' => $uploadedFile->extension,
                'size'      => $uploadedFile->size
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
//        if (!$this->user->id) {
//
//            $this->transportResponse['message'] = 'Access denied';
//            return false;
//        }

        if (!$this->type) {

            $this->transportResponse['message'] = 'Transport type missed';
            return false;
        }

        if ( !in_array($this->type, $this->typesAvailable) ){
            $this->transportResponse['message'] = 'Wrong type passed';
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
}
