<?php defined('SYSPATH') or die('No direct script access.');

return [
    Model_File::EDITOR_IMAGE => [
        'path' => 'upload/pages/images/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => [
            'o' => [false, 1500, 1500],
            'b' => [true, 200],
            'm' => [true, 100],
            's' => [true, 50],
        ],
    ],

    Model_File::BRANDING => [
        'path' => 'upload/branding/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => [
            'o' => [false, 2000, 1500],
            'b' => [true, 1000, 700],
            'm' => [true, 400, 250],
            'preload' => [false, 40, 25]
        ]

    ],

    Model_File::EDITOR_FILE => [
        'path' => 'upload/pages/files/'
    ],

    Model_File::USER_PHOTO => [
        'path' => 'upload/users/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => [
            'o' => [false, 1000, 1000],
            'b' => [true, 200],
            'm' => [true, 100],
            's' => [true, 50],
        ],
    ],

    Model_File::EDITOR_PERSONALITY => [
        'path' => 'upload/pages/persons/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => [
            'o' => [false, 1000, 1000],
            'b' => [true, 200],
            'm' => [true, 100],
            's' => [true, 50],
        ],
    ],

    Model_File::PAGE_COVER => [
        'path' => 'upload/pages/covers/',
        /**
         * Image sizes config
         * key - filename prefix_
         * first argument  — need crop square or should resize with saving ratio
         * second argument — max width
         * third argument  — max height
         */
        'sizes' => [
            'o' => [false, 1500, 1500],
            'b' => [true, 200],
        ],
    ],

    Model_File::SITE_LOGO => [
        'path' => 'upload/logo/',

        'sizes' => [
            'o' => [false, 1000, 1000],
            'l' => [true, 400],
            'b' => [true, 200],
            'm' => [true, 100],
            's' => [true, 50],
        ],
    ],

];
