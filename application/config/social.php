<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

    'vk' => array(
        'VK_APP_ID'             => '4336733',
        'VK_APP_SECRET'         => '4mH8I8CRsf4xFfHXnfXc',
        'REDIRECT_URI'          => 'http://'.Arr::get($_SERVER, 'SERVER_NAME').'/auth/vk',
        'DISPLAY'               => 'page', // page OR popup OR touch OR wap
        'SCOPE'                 => array(
            //'notify', // Пользователь разрешил отправлять ему уведомления.
            //'friends', // Доступ к друзьям.
            //'photos', // Доступ к фотографиям.
            //'audio', // Доступ к аудиозаписям.
            //'video', // Доступ к видеозаписям.
            //'docs', // Доступ к документам.
            //'notes', // Доступ заметкам пользователя.
            //'pages', // Доступ к wiki-страницам.
            //'wall', // Доступ к обычным и расширенным методам работы со стеной.
            //'groups', // Доступ к группам пользователя.
            //'ads', // Доступ к расширенным методам работы с рекламным API.
            //'offline' // Доступ к API в любое время со стороннего сервера.
        ),
        'VK_URI_AUTH'           => 'http://api.vk.com/oauth/authorize?client_id={CLIENT_ID}&scope={SCOPE}&display={DISPLAY}&redirect_uri={REDIRECT_URI}',
        'VK_URI_ACCESS_TOKEN'   => 'https://api.vkontakte.ru/oauth/access_token?client_id={CLIENT_ID}&client_secret={APP_SECRET}&code={CODE}&redirect_uri={REDIRECT_URI}',
        'VK_URI_METHOD'         => 'https://api.vkontakte.ru/method/{METHOD_NAME}?{PARAMETERS}&access_token={ACCESS_TOKEN}',
    )
);