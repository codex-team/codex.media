<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

$DIGIT  = '\d+';
$STRING = '[-a-zA-Z\d]+';

$FEED_KEYS = Model_Page::FEED_KEY_NEWS.'|'.Model_Page::FEED_KEY_TEACHERS_BLOGS.'|'.Model_Page::FEED_KEY_BLOGS;


/**
 * Static pages
 */
Route::set('INDEX', '(<feed_key>(/))(<page_number>)', // #TODO rewrite expression for: IF <feed_key> && <page_number> THEN need this slash (/)
    array(
        'feed_key' => $FEED_KEYS,
        'page_number' => $DIGIT,
    )
)->defaults(array(
    'controller' => 'index',
    'action' => 'index'
));

Route::set('USERS_LIST', 'users(/<type>)', array('type' => 'teachers'))->defaults(array(
    'controller' => 'index',
    'action' => 'users_list'
));

Route::set('CONTACTS', 'contacts')->defaults(array(
    'controller' => 'index',
    'action' => 'contacts'
));


/**
 * Pages section
 */
Route::set('ACTION_FOR_PAGE', 'p/<id>/<action>',
    array(
        'id' => $DIGIT,
        'action' => 'delete|promote'
    )
)->defaults(array(
    'controller' => 'pages',
));

Route::set('NEW_PAGE', 'p/writing')->defaults(array(
    'controller' => 'pages',
    'action' => 'save'
));

Route::set('PAGE', 'p/<id>(/<uri>)', array('id' => $DIGIT, 'uri' => $STRING))->defaults(array(
    'controller' => 'pages',
    'action' => 'show'
));





/**
 * User section
 */
Route::set('PROFILE', 'user/<id>(/<list>)', array('id' => $DIGIT, 'list' => 'pages|comments'))->defaults(array(
    'controller' => 'user',
    'action' => 'profile'
));
Route::set('USER_SETTINGS', 'user/settings')->defaults(array(
    'controller' => 'user',
    'action' => 'settings'
));


/**
* Admin section
*/
Route::set('ADMIN_PAGE', 'admin(/<page>(/<id>))')->defaults(array(
    'controller' => 'admin',
    'action' => 'index'
));

/**
* Ajax routes
*/
Route::set('AJAX_FILE_TRANSPORT', 'upload/<type>')->defaults(array(
    'controller'      => 'transport',
    'action'          => 'upload'
));


Route::set('REPEAT_CONFIRMATION_EMAIL_SENDING', 'ajax/confirmation-email')->defaults(array(
    'controller'      => 'ajax',
    'action'          => 'send_confirmation_email'
));

// Route::set('GETTING_PAGE_FROM_URL', 'ajax/get_page')->defaults(array(
//     'controller'      => 'parser',
//     'action'          => 'get_page'
// ));


/**
* Downloading files
*/
Route::set('DOWNLOAD_FILE', 'file/<file_hash_hex>', array('file_hash_hex' => $STRING))->defaults(array(
    'controller' => 'files',
    'action' => 'download'
));


/** Auth */
Route::set('AUTH_PAGE', 'auth(/<method>)')->defaults(array(
    'controller' => 'auth_auth',
    'action' => 'auth'
));

Route::set('SIGNUP', '<action>(/<hash>)', array('action' => 'signup|confirm', 'hash' => $STRING))->defaults(array(
    'controller' => 'auth_signup',
    'action' => 'signup'
));

Route::set('LOGOUT', 'logout')->defaults(array(
    'controller' => 'auth_auth',
    'action' => 'logout'
));

Route::set('SEND_RESET_PASSWORD_EMAIL', 'reset')->defaults(array(
    'controller' => 'auth_auth',
    'action' => 'reset'
));
Route::set('SET_NEW_PASSWORD', 'reset/<hash>')->defaults(array(
    'controller' => 'auth_auth',
    'action' => 'reset_password'
));


/**
 * Comments
 */
Route::set('ADD_COMMENT_SCRIPT', 'add-comment/p-<id>', array('id' => $DIGIT))->defaults(array(
    'controller' => 'comments',
    'action' => 'add'
));

Route::set('DEL_COMMENT_SCRIPT', 'delete-comment/<comment_id>', array('comment_id' => $DIGIT))
    ->defaults(array(
        'controller' => 'comments',
        'action'     => 'delete'
));