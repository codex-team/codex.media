<?php defined('SYSPATH') or die('No direct access allowed.');

return [

    'driver' => 'File',
    'hash_method' => 'sha256',
    'hash_key' => null,
    'lifetime' => 1209600,
    'session_type' => Session::$default,
    'session_key' => 'auth_user',

    // Username/password combinations for the Auth File driver
    'users' => [
        // 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
    ],

];
