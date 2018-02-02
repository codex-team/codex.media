<?php defined('SYSPATH') or die('No direct script access.');

return [
    'finalize' => true,
    'preload' => false,
    'settings' => [
        /**
         * Use the application cache for HTML Purifier
         */
        'Cache.SerializerPath' => APPPATH . 'cache',
        'Filter.YouTube' => true,
    ],
];
