<?php defined('SYSPATH') or die('No direct script access.');

class Model_File extends Model
{
    public $id = 0;
    public $title = '';
    public $is_removed = 0;

    public $extension = '';
    public $mime = '';
    public $filename = '';
    public $author = 0;
    public $size = 0;
    public $date = null;
    public $status = 0;
    public $type = 0;
    public $target = 0;


    /**
     * File destination
     *
     * @var string
     */
    public $filepath = '';

    public $file_hash = '';
    public $file_hash_hex = '';

    const EDITOR_IMAGE = 1;
    const EDITOR_FILE = 2;
    const USER_PHOTO = 3;
    const BRANDING = 4;
    const PAGE_COVER = 5;
    const EDITOR_PERSONALITY = 6;
    const SITE_LOGO = 7;

    /**
     * This types are images
     *
     * @var array
     */
    public $imageTypes = [
        self::EDITOR_IMAGE,
        self::USER_PHOTO,
        self::BRANDING,
        self::PAGE_COVER,
        self::EDITOR_PERSONALITY,
        self::SITE_LOGO
    ];

    public function __construct($id = null, $file_hash_hex = null, $row = [])
    {
        if (!$id && !$file_hash_hex && !$row) {
            return;
        }

        return self::get($id, $file_hash_hex, $row);
    }

    /**
     * Returns uploaded file path by type and filename
     *
     * @uses  config/upload.php
     *
     * @return stirng filepath from base dir
     */
    private function getFilePath()
    {
        $config = Kohana::$config->load('upload');

        return $config[$this->type]['path'] . $this->filename;
    }

    /**
     * Uploads file to the server
     *
     * @param int      $type    file type constant
     * @param array    $file    file object
     * @param int      $user_id author
     * @param null|int $target  target
     *
     * @return string uploaded file name
     */
    public function upload($type, $file, $user_id, $target = null)
    {
        $this->type = $type;
        $this->target = $target;

        $config = Kohana::$config->load('upload')[$this->type];
        $path = $config['path'];
        $saved = false;

        $isImage = in_array($type, $this->imageTypes);

        if (!$isImage) {
            $savedFilename = $this->saveFile($file, $path);
        } else {
            $savedFilename = $this->saveImage($file, $path, $config['sizes']);
        }

        /** Check for uploading error */
        if (!$savedFilename) {
            return false;
        }

        switch ($type) {

            case self::EDITOR_FILE:
                $this->filename = $savedFilename;
                break;

            case self::EDITOR_IMAGE:
                $this->filename = 'o_' . $savedFilename;
                break;

            case self::USER_PHOTO:
                $this->filename = 'b_' . $savedFilename;
                $user = new Model_User($user_id);
                $user->updatePhoto($savedFilename, $path);
                break;

            case self::BRANDING:
                $user = new Model_User($user_id);
                if (!$user->isAdmin) {
                    return false;
                }

                // save branding as site_info
                $settings = new Model_Settings();
                $branding = $settings->newBranding($savedFilename);
                $this->filename = 'o_' . $branding;
                break;

            case self::PAGE_COVER:
                $page = new Model_Page($target);
                $page->cover = $savedFilename;
                $page->update();
                $this->filename = 'o_' . $savedFilename;
                break;

            case self::EDITOR_PERSONALITY:
                $this->filename = 'b_' . $savedFilename;
                break;

            case self::SITE_LOGO:
                $user = new Model_User($user_id);
                if (!$user->isAdmin) {
                    return false;
                }

                $settings = new Model_Settings();
                $settings->newLogo($savedFilename);
                $this->filename = 'm_' . $savedFilename;

                break;
        }

        $this->title = $this->getOriginalName($file['name']);
        $this->filepath = $path . $this->filename;
        $this->size = $this->getSize();
        $this->mime = $this->getMime();
        $this->extension = $this->getExtension();
        $this->author = $user_id;

        return $this->insert();
    }

    /**
     * Returns size of file
     *
     * @return int
     */
    public function getSize()
    {
        return @filesize($this->filepath);
    }

    /**
     * Returns file mime type by filepath
     *
     * @return string mime-type
     */
    public function getMime()
    {
        return File::mime($this->filepath);
    }

    /**
     * Returns file extension by mime-type
     *
     * @return string extension
     */
    public function getExtension()
    {
        return File::ext_by_mime($this->mime);
    }

    /**
     * Returns file extension by mime-type
     *
     * @param mixed $filepath
     *
     * @return string extension
     */
    public function getOriginalName($filepath)
    {
        $info = pathinfo($filepath);

        return $info['filename'];
    }

    public function get($id = null, $file_hash_hex = null, $file_row = [])
    {
        if ($id || $file_hash_hex) {
            $file = Dao_Files::select();

            if ($id) {
                $file->where('id', '=', $id);
            }
            if ($file_hash_hex) {
                $file->where('file_hash', '=', hex2bin($file_hash_hex));
            }

            $file_row = $file->limit(1)->execute();
        }

        if (!$file_row) {
            return false;
        }

        foreach ($file_row as $field => $value) {
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }

        $this->file_hash_hex = bin2hex($this->file_hash);
        $this->filepath = self::getFilePath();

        return $this;
    }

    public function insert($fields = [])
    {
        $file = Dao_Files::insert();

        if ($fields) {
            foreach ($fields as $key => $value) {
                $file->set($key, $value);
            }

            $this->filename = $fields['filename'];
        } else {

            /** если на вход идет модель */
            $file->set('filename', $this->filename)
                 ->set('title', $this->title)
                 ->set('author', $this->author)
                 ->set('size', $this->size)
                 ->set('extension', $this->extension)
                 ->set('mime', $this->mime)
                 ->set('type', $this->type)
                 ->set('target', $this->target)
                 ->set('file_hash', hex2bin($this->file_hash_hex));
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
            if (ob_get_level()) {
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

    /**
     * Files uploading section
     *
     * @param mixed $file
     * @param mixed $path
     * @param mixed $sizesConfig
     */
    public function saveImage($file, $path, $sizesConfig)
    {
        /**
         *   Проверки на  Upload::valid($file) OR Upload::not_empty($file) OR Upload::size($file, '8M') делаются в контроллере.
         */
        if (!Upload::type($file, ['jpg', 'jpeg', 'png', 'gif'])) {
            return false;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $file = Upload::save($file, null, $path);

        if (!$file) {
            return false;
        }

        $this->file_hash_hex = bin2hex(openssl_random_pseudo_bytes(16));
        $filename = $this->file_hash_hex . '.jpg';


        foreach ($sizesConfig as $prefix => $sizes) {

            /**
             * Все операции делаем с исходным файлом.
             * Для этого заново его загружаем в переменную
             */
            $image = Image::factory($file);

            $isSquare = !!$sizes[0];
            $width = Arr::get($sizes, 1, null);
            $height = !$isSquare ? Arr::get($sizes, 2, null) : $width;

            $image->background('#fff');

            // Вырезание квадрата
            if ($isSquare) {
                if ($image->width >= $image->height) {
                    $image->resize(null, $height, Image::AUTO);
                } else {
                    $image->resize($width, null, Image::AUTO);
                }

                $image->crop($width, $height);
            } else {
                if ($image->width > $width || $image->height > $height) {
                    $image->resize($width, $height, Image::AUTO);
                }
            }

            $image->save($path . $prefix . '_' . $filename);
        }

        // Delete the temporary file
        unlink($file);

        return $filename;
    }

    /**
     * Saves file to the server
     *
     * @param array  $file file array from input
     * @param string $path path to store file
     *
     * @return string saved file name
     *
     * @todo  Add translited file title to file name
     * @todo  Check extension by mime type — see https://kohanaframework.org/3.3/guide-api/File#mime
     */
    public function saveFile($file, $path)
    {
        /**
         *   Проверки на  Upload::valid($file) OR Upload::not_empty($file) OR Upload::size($file, '8M') делаются в контроллере.
         */
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $this->file_hash_hex = bin2hex(openssl_random_pseudo_bytes(16));
        $filename = $this->file_hash_hex . '.' . $ext;

        $file = Upload::save($file, $filename, $path);

        if ($file) {
            return $filename;
        }

        return false;
    }
}
