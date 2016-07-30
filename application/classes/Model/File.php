<?php defined('SYSPATH') or die('No direct script access.');

class Model_File extends Model
{

    public $id          = 0;
    public $page        = 0;
    public $extension   = '';
    public $filename    = '';
    public $title       = '';
    public $author      = 0;
    public $size        = 0;
    public $date        = null;
    public $is_removed  = 0;
    public $status      = 0;
    public $type        = 0;
    public $filepath    = '';

    const PAGE_FILE  = 1;
    const PAGE_IMAGE = 2;

    public function __construct($id = null, $name = null, $row = array())
    {
        if ( !$id && !$name && !$row ) return;

        return self::get($id, $name, $row);
    }

    public function get($id = null, $name = null, $file_row = array())
    {
        if ($id || $name)
        {
            $file = Dao_Files::select();

            if ($id)    $file->where('id', '=', $id);
            if ($name)  $file->where('filename', '=', $name);

            $file_row = $file->limit(1)->execute();

        }

        if( !$file_row ) {

            return false;
        }

        foreach ($file_row as $field => $value)
        {
            if (property_exists($this, $field))
            {
                $this->$field = $value;
            }
        }

        $this->filepath = self::getFilePath();

        return $this;
    }

    public function insert($fields = array())
    {
        $file = Dao_Files::insert();

        if ($fields)
        {
            /** если на вход идет массив */
            foreach ($fields as $key => $value) {
                $file->set($key, $value);
            }

        } else {
            /** если на вход идет модель */
            $file->set('filename',  $this->filename)
                 ->set('title',     $this->title)
                 ->set('author',    $this->author)
                 ->set('size',      $this->size)
                 ->set('extension', $this->extension)
                 ->set('type',      $this->type);
        }

        $file_id = $file->execute();

        return self::get($file_id);
    }

    public function update($fields = array())
    {
        $file = Dao_Files::update();

        if ($fields && isset($fields['id']))
        {
            /** если на вход идет массив */
            $file->where('id', '=', $fields['id']);

            foreach ($fields as $name => $value)
                $file->set($name, trim(htmlspecialchars($value)));

        } else {
            /** если на вход идет модель */
            $file->where('id', '=', $this->id)
                 ->set('page',      $this->page)
                 ->set('title',     $this->title);

        }

        $file_id = $file->execute();

        return self::get($file_id);
    }

    static public function getUploadPathByType($type)
    {
        switch ($type) {
            case self::PAGE_FILE:
                return 'upload/page_files/';
                break;

            case self::PAGE_IMAGE:
                return 'upload/page_images/';
                break;

            default:
                return 'upload/default/';
                break;
        }
    }

    public function getFilePath()
    {
        $path = self::getUploadPathByType($this->type);

        $path .= $this->filename . '.' . $this->extension;

        return $path;
    }

    static public function getPageFiles( $page_id, $type = false )
    {
        $page_files = Dao_Files::select()
            ->where('page','=', $page_id)
            ->where('status', '=', 0);

        if ($type) $page_files->where('type', '=', $type);

        $page_files_rows = $page_files->order_by('id','ASC')->execute();

        $page_files_array = array();

        if (!empty($page_files_rows))
        {
            foreach ($page_files_rows as $file_row) {
                $page_files_array[] = new Model_File(null, null, $file_row);
            }
        }

        return $page_files_array;
    }

    /**
    *   Функция для скачивания файла
    *   Источник: https://habrahabr.ru/post/151795/
    */
    public function returnFileToUser()
    {
        if (file_exists($this->filepath))
        {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level())
            {
              ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $this->title . '.' . $this->extension);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($this->filepath));
            // читаем файл и отправляем его пользователю
            readfile($this->filepath);
            exit;
        }
    }

}
