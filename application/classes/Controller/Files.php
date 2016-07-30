<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Files extends Controller_Base_preDispatch
{
    public function action_download()
    {
        $file_hash = $this->request->param('file_hash');

        /**  packing hex to bin for searchig */
        $file_hash = pack('H*', $file_hash);

        $file = new Model_File(null, $file_hash);

        $file->returnFileToUser();

    }
}