<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Files extends Controller_Base_preDispatch
{
    public function action_download()
    {
        $filename = $this->request->param('filename');

        $file = new Model_File(null, $filename);

        $file->returnFileToUser();

    }
}