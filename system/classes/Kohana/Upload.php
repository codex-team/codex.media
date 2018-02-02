<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Upload helper class for working with uploaded files and [Validation].
 *
 *     $array = Validation::factory($_FILES);
 *
 * [!!] Remember to define your form with "enctype=multipart/form-data" or file
 * uploading will not work!
 *
 * The following configuration properties can be set:
 *
 * - [Upload::$remove_spaces]
 * - [Upload::$default_directory]
 *
 * @package    Kohana
 * @category   Helpers
 *
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Upload
{

    /**
     * @var bool remove spaces in uploaded files
     */
    public static $remove_spaces = true;

    /**
     * @var string default upload directory
     */
    public static $default_directory = 'upload';

    /**
     * Save an uploaded file to a new location. If no filename is provided,
     * the original filename will be used, with a unique prefix added.
     *
     * This method should be used after validating the $_FILES array:
     *
     *     if ($array->check())
     *     {
     *         // Upload is valid, save it
     *         Upload::save($array['file']);
     *     }
     *
     * @param array  $file      uploaded file data
     * @param string $filename  new filename
     * @param string $directory new directory
     * @param int    $chmod     chmod mask
     *
     * @return string on success, full path to new file
     * @return FALSE  on failure
     */
    public static function save(array $file, $filename = null, $directory = null, $chmod = 0644)
    {
        if (! isset($file['tmp_name']) or ! is_uploaded_file($file['tmp_name'])) {
            // Ignore corrupted uploads
            return false;
        }

        if ($filename === null) {
            // Use the default filename, with a timestamp pre-pended
            $filename = uniqid() . $file['name'];
        }

        if (Upload::$remove_spaces === true) {
            // Remove spaces from the filename
            $filename = preg_replace('/\s+/u', '_', $filename);
        }

        if ($directory === null) {
            // Use the pre-configured upload directory
            $directory = Upload::$default_directory;
        }

        if (! is_dir($directory) or ! is_writable(realpath($directory))) {
            throw new Kohana_Exception('Directory :dir must be writable',
                [':dir' => Debug::path($directory)]);
        }

        // Make the filename into a complete path
        $filename = realpath($directory) . DIRECTORY_SEPARATOR . $filename;

        if (move_uploaded_file($file['tmp_name'], $filename)) {
            if ($chmod !== false) {
                // Set permissions on filename
                chmod($filename, $chmod);
            }

            // Return new file path
            return $filename;
        }

        return false;
    }

    /**
     * Tests if upload data is valid, even if no file was uploaded. If you
     * _do_ require a file to be uploaded, add the [Upload::not_empty] rule
     * before this rule.
     *
     *     $array->rule('file', 'Upload::valid')
     *
     * @param array $file $_FILES item
     *
     * @return bool
     */
    public static function valid($file)
    {
        return (isset($file['error'])
            and isset($file['name'])
            and isset($file['type'])
            and isset($file['tmp_name'])
            and isset($file['size']));
    }

    /**
     * Tests if a successful upload has been made.
     *
     *     $array->rule('file', 'Upload::not_empty');
     *
     * @param array $file $_FILES item
     *
     * @return bool
     */
    public static function not_empty(array $file)
    {
        return (isset($file['error'])
            and isset($file['tmp_name'])
            and $file['error'] === UPLOAD_ERR_OK
            and is_uploaded_file($file['tmp_name']));
    }

    /**
     * Test if an uploaded file is an allowed file type, by extension.
     *
     *     $array->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')));
     *
     * @param array $file    $_FILES item
     * @param array $allowed allowed file extensions
     *
     * @return bool
     */
    public static function type(array $file, array $allowed)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return true;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        return in_array($ext, $allowed);
    }

    /**
     * Validation rule to test if an uploaded file is allowed by file size.
     * File sizes are defined as: SB, where S is the size (1, 8.5, 300, etc.)
     * and B is the byte unit (K, MiB, GB, etc.). All valid byte units are
     * defined in Num::$byte_units
     *
     *     $array->rule('file', 'Upload::size', array(':value', '1M'))
     *     $array->rule('file', 'Upload::size', array(':value', '2.5KiB'))
     *
     * @param array  $file $_FILES item
     * @param string $size maximum file size allowed
     *
     * @return bool
     */
    public static function size(array $file, $size)
    {
        if ($file['error'] === UPLOAD_ERR_INI_SIZE) {
            // Upload is larger than PHP allowed size (upload_max_filesize)
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            // The upload failed, no size to check
            return true;
        }

        // Convert the provided size to bytes for comparison
        $size = Num::bytes($size);

        // Test that the file is under or equal to the max size
        return ($file['size'] <= $size);
    }

    /**
     * Validation rule to test if an upload is an image and, optionally, is the correct size.
     *
     *     // The "image" file must be an image
     *     $array->rule('image', 'Upload::image')
     *
     *     // The "photo" file has a maximum size of 640x480 pixels
     *     $array->rule('photo', 'Upload::image', array(':value', 640, 480));
     *
     *     // The "image" file must be exactly 100x100 pixels
     *     $array->rule('image', 'Upload::image', array(':value', 100, 100, TRUE));
     *
     *
     * @param array $file       $_FILES item
     * @param int   $max_width  maximum width of image
     * @param int   $max_height maximum height of image
     * @param bool  $exact      match width and height exactly?
     *
     * @return bool
     */
    public static function image(array $file, $max_width = null, $max_height = null, $exact = false)
    {
        if (Upload::not_empty($file)) {
            try {
                // Get the width and height from the uploaded image
                list($width, $height) = getimagesize($file['tmp_name']);
            } catch (ErrorException $e) {
                // Ignore read errors
            }

            if (empty($width) or empty($height)) {
                // Cannot get image size, cannot validate
                return false;
            }

            if (! $max_width) {
                // No limit, use the image width
                $max_width = $width;
            }

            if (! $max_height) {
                // No limit, use the image height
                $max_height = $height;
            }

            if ($exact) {
                // Check if dimensions match exactly
                return ($width === $max_width and $height === $max_height);
            } else {
                // Check if size is within maximum dimensions
                return ($width <= $max_width and $height <= $max_height);
            }
        }

        return false;
    }
} // End upload
