<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

$DIGIT = '\d+';
$STRING = '[-a-zA-Z\d]+';

$FEED_KEYS = implode('|', [
    Model_Feed_Pages::ALL,
    Model_Feed_Pages::TEACHERS,
    Model_Feed_Pages::EVENTS,
    Model_Feed_Pages::MAIN
]);

$USER_FEED_LISTS = implode('|', [
    Controller_User_Index::LIST_PAGES,
    Controller_User_Index::LIST_COMMENTS
]);

/**
 * Static pages
 */
Route::set('INDEX', '(<feed_key>(/))(<page_number>)', // #TODO rewrite expression for: IF <feed_key> && <page_number> THEN need this slash (/)
    [
        'feed_key' => $FEED_KEYS,
        'page_number' => $DIGIT,
    ]
)->defaults([
    'controller' => 'index',
    'action' => 'index'
]);

/**
 * Pages section
 */
Route::set('ACTION_FOR_PAGE', 'p/<id>/<action>',
    [
        'id' => $DIGIT,
        'action' => 'delete|promote|pin'
    ]
)->defaults([
    'controller' => 'Page_Modify',
]);

Route::set('NEW_PAGE', 'p/writing')->defaults([
    'controller' => 'Page_Index',
    'action' => 'writing'
]);

Route::set('SAVE_PAGE', 'p/save')->defaults([
    'controller' => 'Page_Modify',
    'action' => 'save'
]);

Route::set('PAGE', 'p/<id>(/<uri>)', ['id' => $DIGIT, 'uri' => $STRING])->defaults([
    'controller' => 'Page_Index',
    'action' => 'show'
]);

/**
 * User section
 */
Route::set('CHANGE_USER_STATUS', 'user/<id>/change/<field>', [
    'id' => $DIGIT,
    'field' => 'role|status'
])->defaults([
    'controller' => 'user_modify',
    'action' => 'promote'
]);

Route::set('PROFILE', 'user/<id>(/<list>(/<page_number>))', ['id' => $DIGIT, 'list' => $USER_FEED_LISTS, 'page_number' => $DIGIT])->defaults([
    'controller' => 'User_Index',
    'action' => 'profile'
]);

Route::set('USER_SETTINGS', 'user/settings')->defaults([
    'controller' => 'User_Modify',
    'action' => 'settings'
]);
Route::set('PASSWORD_CHANGE', 'user/passchange')->defaults([
    'controller' => 'User_Modify',
    'action' => 'request_password_change'
]);
Route::set('fast saving bio', 'user/updateBio')->defaults([
    'controller' => 'User_Modify',
    'action' => 'updateBio'
]);

/**
 * Admin section
 */
Route::set('ADMIN_PAGE', 'admin(/<page>(/<id>))')->defaults([
    'controller' => 'admin',
    'action' => 'index'
]);

/**
 * Ajax routes
 */
Route::set('AJAX_FILE_TRANSPORT', 'upload/<type>')->defaults([
    'controller' => 'transport',
    'action' => 'upload'
]);


Route::set('REPEAT_CONFIRMATION_EMAIL_SENDING', 'ajax/confirmation-email')->defaults([
    'controller' => 'ajax',
    'action' => 'send_confirmation_email'
]);

Route::set('CHANGE_USER_EMAIL', 'user/changeEmail')->defaults([
    'controller' => 'user_modify',
    'action' => 'changeEmail'
]);

/**
 * Downloading files
 */
Route::set('DOWNLOAD_FILE', 'file/<file_hash_hex>', ['file_hash_hex' => $STRING])->defaults([
    'controller' => 'files',
    'action' => 'download'
]);


/** Auth */
Route::set('AUTH_PAGE', 'auth(/<method>)')->defaults([
    'controller' => 'auth_auth',
    'action' => 'auth'
]);

Route::set('SIGNUP', '<action>(/<hash>)', ['action' => 'signup|confirm', 'hash' => $STRING])->defaults([
    'controller' => 'auth_signup',
    'action' => 'signup'
]);

Route::set('LOGOUT', 'logout')->defaults([
    'controller' => 'auth_auth',
    'action' => 'logout'
]);

Route::set('SEND_RESET_PASSWORD_EMAIL', 'reset')->defaults([
    'controller' => 'auth_auth',
    'action' => 'reset'
]);
Route::set('SET_NEW_PASSWORD', '<method>/<hash>', ['method' => 'reset|change', 'hash' => $STRING])
    ->defaults([
        'controller' => 'auth_auth',
        'action' => 'reset_password'
]);


/**
 * Comments
 */
Route::set('ADD_COMMENT_SCRIPT', 'add-comment/p-<id>', ['id' => $DIGIT])->defaults([
    'controller' => 'comments',
    'action' => 'add'
]);

Route::set('DEL_COMMENT_SCRIPT', 'delete-comment/<comment_id>', ['comment_id' => $DIGIT])
    ->defaults([
        'controller' => 'comments',
        'action' => 'delete'
]);

Route::set('FETCH_URL', 'fetchURL')
    ->defaults([
        'controller' => 'Parser',
        'action' => 'fetchURL'
    ]);
