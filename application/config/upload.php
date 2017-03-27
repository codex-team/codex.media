<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    Model_File::EDITOR_IMAGE => array(
        'path' => 'upload/pages/images/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => array(
            'o'  => array(false, 1500, 1500),
            'b'  => array(true , 200),
            'm'  => array(true , 100),
            's'  => array(true , 50),
        ),
    ),

    Model_File::BRANDING => array(
        'path' => 'upload/branding/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => array(
            'o'  => array(false, 2000, 1500),
            'b'  => array(true , 1000, 700),
            'm'  => array(true , 400, 250),
            'preload' => array(false, 40, 25)
        )

    ),

    Model_File::EDITOR_FILE => array(
        'path' => 'upload/pages/files/'
    ),

    Model_File::USER_PHOTO => array(
        'path' => 'upload/users/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => array(
            'o'  => array(false, 1000, 1000),
            'b'  => array(true , 200),
            'm'  => array(true , 100),
            's'  => array(true , 50),
        ),
    ),

);
