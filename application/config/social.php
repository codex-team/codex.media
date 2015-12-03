<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
    'vk' => array(
        'client_id'     => '5171042',
        'client_secret' => '4V4V7Ql3OcFVHaIpHS6y',
        'redirect_uri'  => 'http://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/vk',
        'scopes'        => 'email,offline',
    )
);