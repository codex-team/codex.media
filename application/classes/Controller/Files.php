<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Files extends Controller_Base_preDispatch
{
    public function action_download()
    {
        $filename = $this->request->param('filename');

        // $file = file_get_contents('upload/page_files/' . $filename . );

        // $this->response->headers('Content-disposition', 'attachment; filename=' . $filename . '.py');
        // $this->auto_render = false;
        // $this->response->body($file);

        $file = new Model_File(null, $filename);
        //$file->update(array('extension' => 't'));
        $this->response->body(json_encode($file));
        $this->auto_render = false;
    }
}