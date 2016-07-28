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
                return 'upload/';
                break;
        }
    }

    public function __construct($id = null, $name = null)
    {
        if ( !$id and !$name ) return;

        return self::get($id, $name);
    }

    public function get($id = null, $name = null)
    {
        if ($id or $name)
        {
            $file = Dao_Files::select();

            if ($id)    $file->where('id', '=', $id);
            if ($name)  $file->where('filename', '=', $name);

            $file_row = $file->limit(1)->execute();

            foreach ($file_row as $field => $value){
                if (property_exists($this, $field)){
                    $this->$field = $value;
                }
            }

            $this->filepath = self::getFilePath();

            return $this;
        }

        return false;
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
            $file->set('filename', $this->filename)
                 ->set('title', $this->title)
                 ->set('author', $this->author)
                 ->set('size', $this->size)
                 ->set('extension', $this->extension)
                 ->set('type', $this->type);
        }

        $file_id = $file->execute();

        return self::get($file_id);
    }

    public function update($fields)
    {
        $file = Dao_Files::update()
                ->where('id', '=', $this->id);

        foreach ($fields as $name => $value)
            $file->set($name, trim(htmlspecialchars($value)));

        $file->execute();

        return self::get($this->id);
    }

    public function getFilePath()
    {
        $path = self::getUploadPathByType($this->type);

        $path .= $this->filename . '.' . $this->extension;

        return $path;
    }

}
