<?php defined('SYSPATH') or die('No direct script access.');

class Model_File extends Model
{
    public $id          = 0;
    public $page        = 0;
    public $title       = '';
    public $is_removed  = 0;

    public $extension   = '';
    public $filename    = '';
    public $author      = 0;
    public $size        = 0;
    public $date        = null;
    public $status      = 0;
    public $type        = 0;
    public $filepath    = '';

    public $file_hash     = '';
    public $file_hash_hex = '';

    const EDITOR_IMAGE = 1;
    const EDITOR_FILE  = 2;

    /**
     * @var конфиг с размерами вырезаемых изображений
     * первый параметр - вырезать квадрат (true) или просто ресайзить с сохранением пропрорций (false)
     */
    public $IMAGE_SIZES_CONFIG = array(
        'o'  => array(false, 1500, 1500),
        'b'  => array(true , 200),
        'm'  => array(true , 100),
        's'  => array(true , 50),
    );



    public function __construct($id = null, $file_hash_hex = null, $row = array())
    {
        if (!$id && !$file_hash_hex && !$row) return;

        return self::get($id, $file_hash_hex, $row);
    }


    /**
     * Returns uploaded file path by type and filename
     * @uses  config/upload.php
     * @return stirng filepath from base dir
     */
    private function getFilePath()
    {
        $config = Kohana::$config->load('upload');

        return $config[$this->type]['path'] . $this->filename;
    }

    /**
     * Uploads file to the server
     * @param  int  $type file type constant
     * @param  array $file file object
     * @return string   uploaded file name
     */
    public function upload($type, $file)
    {
        $this->type = $type;

        $config = Kohana::$config->load('upload');
        $path   = $config[$this->type]['path'];

        return $this->saveFile($file, $path);
    }


    // public static function size($filename) {

    //     return filesize($filename);

    // }

    // public static function name ($filename) {

    //     $filename = basename($filename);

    //     $filename = self::rus2translit($filename);

    //     return pathinfo($filename, PATHINFO_FILENAME);

    // }

    // public static function extension ($filename) {

    //     $filename = basename($filename);

    //     return strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // }




    public function get($id = null, $file_hash_hex = null, $file_row = array())
    {
        if ($id || $file_hash_hex) {

            $file = Dao_Files::select();

            if ($id)             $file->where('id', '=', $id);
            if ($file_hash_hex)  $file->where('file_hash', '=', hex2bin($file_hash_hex));

            $file_row = $file->limit(1)->execute();
        }

        if(!$file_row) return false;

        foreach ($file_row as $field => $value) {

            if (property_exists($this, $field)) {

                $this->$field = $value;
            }
        }

        $this->file_hash_hex = bin2hex($this->file_hash);
        $this->filepath      = self::getFilePath();

        return $this;
    }

    public function insert($fields = array())
    {
        $file = Dao_Files::insert();

        if ($fields) {

            foreach ($fields as $key => $value) {

                $file->set($key, $value);
            }

            $this->filename = $fields['filename'];

        } else {

            /** если на вход идет модель */
            $file->set('filename',  $this->filename)
                 ->set('title',     $this->title)
                 ->set('author',    $this->author)
                 ->set('size',      $this->size)
                 ->set('extension', $this->extension)
                 ->set('type',      $this->type);
        }

        $file->set('file_hash', hex2bin(substr($this->filename, 0, strrpos($this->filename, '.'))));

        $file_id = $file->execute();

        return self::get($file_id);
    }

    public function update($fields = array())
    {
        $file = Dao_Files::update();

        if ($fields && isset($fields['id'])) {

            /** если на вход идет массив */
            $file->where('id', '=', $fields['id']);

            foreach ($fields as $name => $value)
                $file->set($name, trim(htmlspecialchars($value)));

        } else {

            /** если на вход идет модель */
            $file->where('id', '=',  $this->id)
                 ->set('page',       $this->page)
                 ->set('title',      $this->title)
                 ->set('is_removed', $this->is_removed)
                 ->set('status',     $this->status);
        }

        $file_id = $file->execute();

        return self::get($file_id);
    }

    /**
    *   Функция для скачивания файла
    *   Источник: https://habrahabr.ru/post/151795/
    */
    public function returnFileToUser()
    {
        if (file_exists($this->filepath)) {

            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) ob_end_clean();

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

    /**
    * Files uploading section
    */
    public function saveImage($file , $path)
    {
        /**
         *   Проверки на  Upload::valid($file) OR Upload::not_empty($file) OR Upload::size($file, '8M') делаются в контроллере.
         */
        if (!Upload::type($file, array('jpg', 'jpeg', 'png', 'gif'))) return FALSE;

        if (!is_dir($path)) mkdir($path);

        if ($file = Upload::save($file, NULL, $path)) {

            $filename = bin2hex(openssl_random_pseudo_bytes(16)) . '.jpg';

            $image = Image::factory($file);

            foreach ($this->IMAGE_SIZES_CONFIG as $prefix => $sizes) {

                $isSquare = !!$sizes[0];
                $width    = $sizes[1];
                $height   = !$isSquare ? $sizes[2] : $width;

                $image->background('#fff');

                // Вырезание квадрата
                if ($isSquare) {

                    if ($image->width >= $image->height) {

                        $image->resize( NULL , $height, true );

                    } else {

                        $image->resize( $width , NULL, true );
                    }

                    $image->crop( $width, $height );

                    /**
                     *   Для работы с этим методом нужно перекомпилировать php c bundled GD
                     *   http://www.maxiwebs.co.uk/gd-bundled/compilation.php
                     *   http://www.howtoforge.com/recompiling-php5-with-bundled-support-for-gd-on-ubuntu
                     */

                    // $image->sharpen(1.5);
                } else {

                    if ($image->width > $width || $image->height > $height) {

                        $image->resize( $width , $height , true );
                    }
                }

                $image->save($path . $prefix . '_' . $filename);
            }

            // Delete the temporary file
            unlink($file);

            return $filename;
        }

        return FALSE;
    }

    public function saveFile($file , $path)
    {
        /**
         *   Проверки на  Upload::valid($file) OR Upload::not_empty($file) OR Upload::size($file, '8M') делаются в контроллере.
         */
        if (!is_dir($path)) mkdir($path);

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . $ext;

        $file = Upload::save($file, $filename, $path);

        if ($file) return $filename;

        return FALSE;
    }

}
