<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
    'vk' => array(
        'type'          => 1,
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => 'http'.((Arr::get($_SERVER, 'HTTPS')) ? 's' : '').'://'.Arr::get($_SERVER, 'HTTP_HOST').'/auth/vk',
        'scopes'        => '',
    ),
    'facebook' => array(
        'type'         => 2,
        'client_id'    => '',
        'client_secret'=> '',
        'response_type'=> '',
        'scope'        => '',
        'redirect_uri' =>  'http'. ((Arr::get($_SERVER, 'HTTPS')) ? 's' : '') .'://'.Arr::get($_SERVER, 'HTTP_HOST').'/auth/fb',
    ),
    'twitter' => array(
        'type'           => 3,
        'consumer_key'   => '',
        'consumer_secret'=>'',
        'redirect_uri' =>  'http'. ((Arr::get($_SERVER, 'HTTPS')) ? 's' : '') .'://'.Arr::get($_SERVER, 'HTTP_HOST').'/auth/tw',
    ),
);
