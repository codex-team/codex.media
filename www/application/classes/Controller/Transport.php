<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transport extends Controller_Base_preDispatch
{
    private $transportResponse = [
        'success' => 0
    ];

    private $type = null;

    /**
     * Transport target id (user, page, etc)
     *
     * @var null|Number
     */
    private $target = null;

    private $files = null;

    /**
     * Transport gateway
     */
    public function action_upload()
    {
        $this->files = Arr::get($_FILES, 'files');
        $this->type = $this->request->param('type');
        $this->target = Arr::get($_POST, 'target');

        $file = new Model_File();

        $success = $this->check();

        if (!$success) {
            goto finish;
        }

        $uploadedFile = $file->upload($this->type, $this->files, $this->user->id, $this->target);

        if ($uploadedFile) {
            $this->transportResponse['success'] = 1;
            $this->transportResponse['file'] = [
                'url' => $uploadedFile->filepath,
                'name' => $uploadedFile->file_hash_hex,
                'extension' => $uploadedFile->extension,
                'size' => $uploadedFile->size
            ];

            /**
             * EditorJS seems to be unable to validate null values
             * Solution: make it optional and write data only if not null
             */
            if (!is_null($uploadedFile->target)) {
                $this->transportResponse['file']['target'] = $uploadedFile->target;
            }
        } else {
            $this->transportResponse['message'] = 'Error while uploading';
        }

        finish:
        $response = @json_encode($this->transportResponse);

        $this->response->status($success ? 200 : 400);
        $this->auto_render = false;
        $this->response->body($response);
    }

    /**
     * Make necessary verifications
     *
     * @return Boolean
     */
    private function check()
    {
        $config = Kohana::$config->load('upload');

        if (!$this->user->id) {
            $this->transportResponse['message'] = 'Access denied';

            return false;
        }

        if (!$this->type) {
            $this->transportResponse['message'] = 'Transport type missed';

            return false;
        }

        if (empty($config[$this->type])) {
            $this->transportResponse['message'] = 'Wrong type passed';

            return false;
        }

        if (!Upload::size($this->files, UPLOAD_MAX_SIZE . "M")) {
            $this->transportResponse['message'] = 'File size exceeded limit: '.UPLOAD_MAX_SIZE.' MB';

            return false;
        }

        if (!$this->files) {
            $this->transportResponse['message'] = 'File was not transferred';

            return false;
        }

        if (!Upload::not_empty($this->files)) {
            $this->transportResponse['message'] = 'File is empty';

            return false;
        }

        if (!Upload::valid($this->files)) {
            $this->transportResponse['message'] = 'Uploaded file is damaged';

            return false;
        }

        return true;
    }
}
