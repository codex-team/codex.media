<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Image manipulation support. Allows images to be resized, cropped, etc.
 *
 * @package    Kohana/Image
 * @category   Base
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_Image
{

    // Resizing constraints
    const NONE = 0x01;
    const WIDTH = 0x02;
    const HEIGHT = 0x03;
    const AUTO = 0x04;
    const INVERSE = 0x05;
    const PRECISE = 0x06;

    // Flipping directions
    const HORIZONTAL = 0x11;
    const VERTICAL = 0x12;

    /**
     * @var string default driver: GD, ImageMagick, etc
     */
    public static $default_driver = 'GD';

    // Status of the driver check
    protected static $_checked = false;

    /**
     * Loads an image and prepares it for manipulation.
     *
     *     $image = Image::factory('upload/test.jpg');
     *
     * @param string $file   image file path
     * @param string $driver driver type: GD, ImageMagick, etc
     *
     * @return Image
     *
     * @uses    Image::$default_driver
     */
    public static function factory($file, $driver = null)
    {
        if ($driver === null) {
            // Use the default driver
            $driver = Image::$default_driver;
        }

        // Set the class name
        $class = 'Image_' . $driver;

        return new $class($file);
    }

    /**
     * @var string image file path
     */
    public $file;

    /**
     * @var int image width
     */
    public $width;

    /**
     * @var int image height
     */
    public $height;

    /**
     * @var int one of the IMAGETYPE_* constants
     */
    public $type;

    /**
     * @var string mime type of the image
     */
    public $mime;

    /**
     * Loads information about the image. Will throw an exception if the image
     * does not exist or is not an image.
     *
     * @param string $file image file path
     *
     * @throws Kohana_Exception
     */
    public function __construct($file)
    {
        try {
            // Get the real path to the file
            $file = realpath($file);

            // Get the image information
            $info = getimagesize($file);
        } catch (Exception $e) {
            // Ignore all errors while reading the image
        }

        if (empty($file) or empty($info)) {
            throw new Kohana_Exception('Not an image or invalid image: :file',
                [':file' => Debug::path($file)]);
        }

        // Store the image information
        $this->file = $file;
        $this->width = $info[0];
        $this->height = $info[1];
        $this->type = $info[2];
        $this->mime = image_type_to_mime_type($this->type);
    }

    /**
     * Render the current image.
     *
     *     echo $image;
     *
     * [!!] The output of this function is binary and must be rendered with the
     * appropriate Content-Type header or it will not be displayed correctly!
     *
     * @return string
     */
    public function __toString()
    {
        try {
            // Render the current image
            return $this->render();
        } catch (Exception $e) {
            if (is_object(Kohana::$log)) {
                // Get the text of the exception
                $error = Kohana_Exception::text($e);

                // Add this exception to the log
                Kohana::$log->add(Log::ERROR, $error);
            }

            // Showing any kind of error will be "inside" image data
            return '';
        }
    }

    /**
     * Resize the image to the given size. Either the width or the height can
     * be omitted and the image will be resized proportionally.
     *
     *     // Resize to 200 pixels on the shortest side
     *     $image->resize(200, 200);
     *
     *     // Resize to 200x200 pixels, keeping aspect ratio
     *     $image->resize(200, 200, Image::INVERSE);
     *
     *     // Resize to 500 pixel width, keeping aspect ratio
     *     $image->resize(500, NULL);
     *
     *     // Resize to 500 pixel height, keeping aspect ratio
     *     $image->resize(NULL, 500);
     *
     *     // Resize to 200x500 pixels, ignoring aspect ratio
     *     $image->resize(200, 500, Image::NONE);
     *
     * @param int $width  new width
     * @param int $height new height
     * @param int $master master dimension
     *
     * @return $this
     *
     * @uses    Image::_do_resize
     */
    public function resize($width = null, $height = null, $master = null)
    {
        if ($master === null) {
            // Choose the master dimension automatically
            $master = Image::AUTO;
        }
        // Image::WIDTH and Image::HEIGHT deprecated. You can use it in old projects,
        // but in new you must pass empty value for non-master dimension
        elseif ($master == Image::WIDTH and ! empty($width)) {
            $master = Image::AUTO;

            // Set empty height for backward compatibility
            $height = null;
        } elseif ($master == Image::HEIGHT and ! empty($height)) {
            $master = Image::AUTO;

            // Set empty width for backward compatibility
            $width = null;
        }

        if (empty($width)) {
            if ($master === Image::NONE) {
                // Use the current width
                $width = $this->width;
            } else {
                // If width not set, master will be height
                $master = Image::HEIGHT;
            }
        }

        if (empty($height)) {
            if ($master === Image::NONE) {
                // Use the current height
                $height = $this->height;
            } else {
                // If height not set, master will be width
                $master = Image::WIDTH;
            }
        }

        switch ($master) {
            case Image::AUTO:
                // Choose direction with the greatest reduction ratio
                $master = ($this->width / $width) > ($this->height / $height) ? Image::WIDTH : Image::HEIGHT;
            break;
            case Image::INVERSE:
                // Choose direction with the minimum reduction ratio
                $master = ($this->width / $width) > ($this->height / $height) ? Image::HEIGHT : Image::WIDTH;
            break;
        }

        switch ($master) {
            case Image::WIDTH:
                // Recalculate the height based on the width proportions
                $height = $this->height * $width / $this->width;
            break;
            case Image::HEIGHT:
                // Recalculate the width based on the height proportions
                $width = $this->width * $height / $this->height;
            break;
            case Image::PRECISE:
                // Resize to precise size
                $ratio = $this->width / $this->height;

                if ($width / $height > $ratio) {
                    $height = $this->height * $width / $this->width;
                } else {
                    $width = $this->width * $height / $this->height;
                }
            break;
        }

        // Convert the width and height to integers, minimum value is 1px
        $width = max(round($width), 1);
        $height = max(round($height), 1);

        $this->_do_resize($width, $height);

        return $this;
    }

    /**
     * Crop an image to the given size. Either the width or the height can be
     * omitted and the current width or height will be used.
     *
     * If no offset is specified, the center of the axis will be used.
     * If an offset of TRUE is specified, the bottom of the axis will be used.
     *
     *     // Crop the image to 200x200 pixels, from the center
     *     $image->crop(200, 200);
     *
     * @param int   $width    new width
     * @param int   $height   new height
     * @param mixed $offset_x offset from the left
     * @param mixed $offset_y offset from the top
     *
     * @return $this
     *
     * @uses    Image::_do_crop
     */
    public function crop($width, $height, $offset_x = null, $offset_y = null)
    {
        if ($width > $this->width) {
            // Use the current width
            $width = $this->width;
        }

        if ($height > $this->height) {
            // Use the current height
            $height = $this->height;
        }

        if ($offset_x === null) {
            // Center the X offset
            $offset_x = round(($this->width - $width) / 2);
        } elseif ($offset_x === true) {
            // Bottom the X offset
            $offset_x = $this->width - $width;
        } elseif ($offset_x < 0) {
            // Set the X offset from the right
            $offset_x = $this->width - $width + $offset_x;
        }

        if ($offset_y === null) {
            // Center the Y offset
            $offset_y = round(($this->height - $height) / 2);
        } elseif ($offset_y === true) {
            // Bottom the Y offset
            $offset_y = $this->height - $height;
        } elseif ($offset_y < 0) {
            // Set the Y offset from the bottom
            $offset_y = $this->height - $height + $offset_y;
        }

        // Determine the maximum possible width and height
        $max_width = $this->width - $offset_x;
        $max_height = $this->height - $offset_y;

        if ($width > $max_width) {
            // Use the maximum available width
            $width = $max_width;
        }

        if ($height > $max_height) {
            // Use the maximum available height
            $height = $max_height;
        }

        $this->_do_crop($width, $height, $offset_x, $offset_y);

        return $this;
    }

    /**
     * Rotate the image by a given amount.
     *
     *     // Rotate 45 degrees clockwise
     *     $image->rotate(45);
     *
     *     // Rotate 90% counter-clockwise
     *     $image->rotate(-90);
     *
     * @param int $degrees degrees to rotate: -360-360
     *
     * @return $this
     *
     * @uses    Image::_do_rotate
     */
    public function rotate($degrees)
    {
        // Make the degrees an integer
        $degrees = (int) $degrees;

        if ($degrees > 180) {
            do {
                // Keep subtracting full circles until the degrees have normalized
                $degrees -= 360;
            } while ($degrees > 180);
        }

        if ($degrees < -180) {
            do {
                // Keep adding full circles until the degrees have normalized
                $degrees += 360;
            } while ($degrees < -180);
        }

        $this->_do_rotate($degrees);

        return $this;
    }

    /**
     * Flip the image along the horizontal or vertical axis.
     *
     *     // Flip the image from top to bottom
     *     $image->flip(Image::HORIZONTAL);
     *
     *     // Flip the image from left to right
     *     $image->flip(Image::VERTICAL);
     *
     * @param int $direction direction: Image::HORIZONTAL, Image::VERTICAL
     *
     * @return $this
     *
     * @uses    Image::_do_flip
     */
    public function flip($direction)
    {
        if ($direction !== Image::HORIZONTAL) {
            // Flip vertically
            $direction = Image::VERTICAL;
        }

        $this->_do_flip($direction);

        return $this;
    }

    /**
     * Sharpen the image by a given amount.
     *
     *     // Sharpen the image by 20%
     *     $image->sharpen(20);
     *
     * @param int $amount amount to sharpen: 1-100
     *
     * @return $this
     *
     * @uses    Image::_do_sharpen
     */
    public function sharpen($amount)
    {
        // The amount must be in the range of 1 to 100
        $amount = min(max($amount, 1), 100);

        $this->_do_sharpen($amount);

        return $this;
    }

    /**
     * Add a reflection to an image. The most opaque part of the reflection
     * will be equal to the opacity setting and fade out to full transparent.
     * Alpha transparency is preserved.
     *
     *     // Create a 50 pixel reflection that fades from 0-100% opacity
     *     $image->reflection(50);
     *
     *     // Create a 50 pixel reflection that fades from 100-0% opacity
     *     $image->reflection(50, 100, TRUE);
     *
     *     // Create a 50 pixel reflection that fades from 0-60% opacity
     *     $image->reflection(50, 60, TRUE);
     *
     * [!!] By default, the reflection will be go from transparent at the top
     * to opaque at the bottom.
     *
     * @param int  $height  reflection height
     * @param int  $opacity reflection opacity: 0-100
     * @param bool $fade_in TRUE to fade in, FALSE to fade out
     *
     * @return $this
     *
     * @uses    Image::_do_reflection
     */
    public function reflection($height = null, $opacity = 100, $fade_in = false)
    {
        if ($height === null or $height > $this->height) {
            // Use the current height
            $height = $this->height;
        }

        // The opacity must be in the range of 0 to 100
        $opacity = min(max($opacity, 0), 100);

        $this->_do_reflection($height, $opacity, $fade_in);

        return $this;
    }

    /**
     * Add a watermark to an image with a specified opacity. Alpha transparency
     * will be preserved.
     *
     * If no offset is specified, the center of the axis will be used.
     * If an offset of TRUE is specified, the bottom of the axis will be used.
     *
     *     // Add a watermark to the bottom right of the image
     *     $mark = Image::factory('upload/watermark.png');
     *     $image->watermark($mark, TRUE, TRUE);
     *
     * @param Image $watermark watermark Image instance
     * @param int   $offset_x  offset from the left
     * @param int   $offset_y  offset from the top
     * @param int   $opacity   opacity of watermark: 1-100
     *
     * @return $this
     *
     * @uses    Image::_do_watermark
     */
    public function watermark(Image $watermark, $offset_x = null, $offset_y = null, $opacity = 100)
    {
        if ($offset_x === null) {
            // Center the X offset
            $offset_x = round(($this->width - $watermark->width) / 2);
        } elseif ($offset_x === true) {
            // Bottom the X offset
            $offset_x = $this->width - $watermark->width;
        } elseif ($offset_x < 0) {
            // Set the X offset from the right
            $offset_x = $this->width - $watermark->width + $offset_x;
        }

        if ($offset_y === null) {
            // Center the Y offset
            $offset_y = round(($this->height - $watermark->height) / 2);
        } elseif ($offset_y === true) {
            // Bottom the Y offset
            $offset_y = $this->height - $watermark->height;
        } elseif ($offset_y < 0) {
            // Set the Y offset from the bottom
            $offset_y = $this->height - $watermark->height + $offset_y;
        }

        // The opacity must be in the range of 1 to 100
        $opacity = min(max($opacity, 1), 100);

        $this->_do_watermark($watermark, $offset_x, $offset_y, $opacity);

        return $this;
    }

    /**
     * Set the background color of an image. This is only useful for images
     * with alpha transparency.
     *
     *     // Make the image background black
     *     $image->background('#000');
     *
     *     // Make the image background black with 50% opacity
     *     $image->background('#000', 50);
     *
     * @param string $color   hexadecimal color value
     * @param int    $opacity background opacity: 0-100
     *
     * @return $this
     *
     * @uses    Image::_do_background
     */
    public function background($color, $opacity = 100)
    {
        if ($color[0] === '#') {
            // Remove the pound
            $color = substr($color, 1);
        }

        if (strlen($color) === 3) {
            // Convert shorthand into longhand hex notation
            $color = preg_replace('/./', '$0$0', $color);
        }

        // Convert the hex into RGB values
        list($r, $g, $b) = array_map('hexdec', str_split($color, 2));

        // The opacity must be in the range of 0 to 100
        $opacity = min(max($opacity, 0), 100);

        $this->_do_background($r, $g, $b, $opacity);

        return $this;
    }

    /**
     * Save the image. If the filename is omitted, the original image will
     * be overwritten.
     *
     *     // Save the image as a PNG
     *     $image->save('saved/cool.png');
     *
     *     // Overwrite the original image
     *     $image->save();
     *
     * [!!] If the file exists, but is not writable, an exception will be thrown.
     *
     * [!!] If the file does not exist, and the directory is not writable, an
     * exception will be thrown.
     *
     * @param string $file    new image path
     * @param int    $quality quality of image: 1-100
     *
     * @throws Kohana_Exception
     *
     * @return bool
     *
     * @uses    Image::_save
     */
    public function save($file = null, $quality = 100)
    {
        if ($file === null) {
            // Overwrite the file
            $file = $this->file;
        }

        if (is_file($file)) {
            if (! is_writable($file)) {
                throw new Kohana_Exception('File must be writable: :file',
                    [':file' => Debug::path($file)]);
            }
        } else {
            // Get the directory of the file
            $directory = realpath(pathinfo($file, PATHINFO_DIRNAME));

            if (! is_dir($directory) or ! is_writable($directory)) {
                throw new Kohana_Exception('Directory must be writable: :directory',
                    [':directory' => Debug::path($directory)]);
            }
        }

        // The quality must be in the range of 1 to 100
        $quality = min(max($quality, 1), 100);

        return $this->_do_save($file, $quality);
    }

    /**
     * Render the image and return the binary string.
     *
     *     // Render the image at 50% quality
     *     $data = $image->render(NULL, 50);
     *
     *     // Render the image as a PNG
     *     $data = $image->render('png');
     *
     * @param string $type    image type to return: png, jpg, gif, etc
     * @param int    $quality quality of image: 1-100
     *
     * @return string
     *
     * @uses    Image::_do_render
     */
    public function render($type = null, $quality = 100)
    {
        if ($type === null) {
            // Use the current image type
            $type = image_type_to_extension($this->type, false);
        }

        return $this->_do_render($type, $quality);
    }

    /**
     * Execute a resize.
     *
     * @param int $width  new width
     * @param int $height new height
     */
    abstract protected function _do_resize($width, $height);

    /**
     * Execute a crop.
     *
     * @param int $width    new width
     * @param int $height   new height
     * @param int $offset_x offset from the left
     * @param int $offset_y offset from the top
     */
    abstract protected function _do_crop($width, $height, $offset_x, $offset_y);

    /**
     * Execute a rotation.
     *
     * @param int $degrees degrees to rotate
     */
    abstract protected function _do_rotate($degrees);

    /**
     * Execute a flip.
     *
     * @param int $direction direction to flip
     */
    abstract protected function _do_flip($direction);

    /**
     * Execute a sharpen.
     *
     * @param int $amount amount to sharpen
     */
    abstract protected function _do_sharpen($amount);

    /**
     * Execute a reflection.
     *
     * @param int  $height  reflection height
     * @param int  $opacity reflection opacity
     * @param bool $fade_in TRUE to fade out, FALSE to fade in
     */
    abstract protected function _do_reflection($height, $opacity, $fade_in);

    /**
     * Execute a watermarking.
     *
     * @param Image $image    watermarking Image
     * @param int   $offset_x offset from the left
     * @param int   $offset_y offset from the top
     * @param int   $opacity  opacity of watermark
     */
    abstract protected function _do_watermark(Image $image, $offset_x, $offset_y, $opacity);

    /**
     * Execute a background.
     *
     * @param int $r       red
     * @param int $g       green
     * @param int $b       blue
     * @param int $opacity opacity
     */
    abstract protected function _do_background($r, $g, $b, $opacity);

    /**
     * Execute a save.
     *
     * @param string $file    new image filename
     * @param int    $quality quality
     *
     * @return bool
     */
    abstract protected function _do_save($file, $quality);

    /**
     * Execute a render.
     *
     * @param string $type    image type: png, jpg, gif, etc
     * @param int    $quality quality
     *
     * @return string
     */
    abstract protected function _do_render($type, $quality);
} // End Image
