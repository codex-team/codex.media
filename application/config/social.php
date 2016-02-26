<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
    'vk' => array(
        'type'          => 1,
        'client_id'     => '5171042',
        'client_secret' => '4V4V7Ql3OcFVHaIpHS6y',
        'redirect_uri'  => 'http'.((Arr::get($_SERVER, 'HTTPS')) ? 's' : '').'://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/vk',
        'scopes'        => 'email,offline',
    ),
    'facebook' => array(
        'type'         => 2,
        'client_id'    => '706927366073496',
        'client_secret'=> 'c46963a4b38ea4c1118a46abf41a145e',
        'response_type'=> 'code',
        'scope'        => 'email',
        'redirect_uri' =>  'http'. ((Arr::get($_SERVER, 'HTTPS')) ? 's' : '') .'://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/fb',
    ),
    'twitter' => array(
        'type'           => 3,
        'consumer_key'   => 'YX0ETqtsCJFT7SmfKKmrgSzXo',
        'consumer_secret'=>'TqZE8mg1WBzBg5q4jZhHoQ9KZshJVthxEVKgNA25jG9vobPwJP',
        'redirect_uri' =>  'http'. ((Arr::get($_SERVER, 'HTTPS')) ? 's' : '') .'://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/tw',
    ),
);