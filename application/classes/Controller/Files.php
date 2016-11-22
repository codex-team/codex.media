<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Files extends Controller_Base_preDispatch
{
    public function action_download()
    {
        $file_hash_hex = $this->request->param('file_hash_hex');

        $file = new Model_File(null, $file_hash_hex);

        $file->returnFileToUser();
    }
}
