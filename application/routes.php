<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

// Route::set('user', 'user(/<id>)')->defaults(array(
//     'controller' => 'user',
//     'action' => 'index'
// ));


$DIGIT = '\d+';
$STRING = '[-a-z\d]+';

Route::set('INDEX', '(<page_number>)', array( 'page_number' => $DIGIT ))->defaults(array(
    'controller' => 'index',
    'action' => 'index'
));


/**
 *  Pages section
 */

#Route::set('NEW', 'p/add-<type>', array( 'type' => 'page|news' ))->defaults(array(
#    'controller' => 'pages',
#    'action' => 'add_new'
#));

Route::set('NEW_PAGE', 'p/save')->defaults(array(
    'controller' => 'pages',
    'action' => 'save'
));


Route::set('DELETE_PAGE', 'p/<id>/<uri>/delete', array( 'id' => $DIGIT , 'uri' => $STRING))->defaults(array(
    'controller' => 'pages',
    'action' => 'delete_page'
));

Route::set('PAGE', 'p/<id>(/<uri>)', array( 'id' => $DIGIT , 'uri' => $STRING))->defaults(array(
    'controller' => 'pages',
    'action' => 'show_page'
));



Route::set('PROFILE', 'user/<id>', array( 'id' => $DIGIT ))->defaults(array(
    'controller' => 'user',
    'action' => 'profile'
));
Route::set('USER_SETTINGS', 'user/settings')->defaults(array(
    'controller' => 'user',
    'action' => 'settings'
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
*   Admin section
*/

Route::set('ADMIN_PAGE', 'admin(/<page>(/<id>))')->defaults(array(
    'controller' => 'admin',
    'action' => 'index'
));





Route::set('AJAX_FILE_TRANSPORT', 'file/transport')->defaults(array(
    'controller'      => 'transport',
    'action'          => 'file_uploader',
    'show'            => true,
    'siteHitsBlocked' => true
));

Route::set('PAGE_FILES_TRANSPORT', 'ajax/file_transport')->defaults(array(
    'controller'      => 'admin',
    'action'          => 'file_uploader'
));

Route::set('PAGE_FILES_EDITING', 'ajax/edit_file/<type>')->defaults(array(
    'controller'      => 'ajax',
    'action'          => 'edit_file'
));

Route::set('GETTING_PAGE_FROM_URL', 'ajax/get_page')->defaults(array(
    'controller'      => 'parser',
    'action'          => 'get_page'
));



/** Auth */
Route::set('AUTH_PAGE', 'auth(/<method>)')->defaults(array(
    'controller' => 'auth_auth',
    'action' => 'auth'
));

Route::set('LOGOUT', 'logout')->defaults(array(
    'controller' => 'auth_auth',
    'action' => 'logout'
));


/** Comments */

Route::set('ADD_COMMENT_SCRIPT', 'p/<id>/<uri>/add-comment', array( 'id' => $DIGIT , 'uri' => $STRING))->defaults(array(
    'controller' => 'comments',
    'action' => 'add'
));

Route::set('DEL_COMMENT_SCRIPT', 'p/<id>/<uri>/delete-comment/<comment_id>', array(
        'id'         => $DIGIT,
        'uri'        => $STRING,
        'comment_id' => $DIGIT))
    ->defaults(array(
        'controller' => 'comments',
        'action'     => 'delete'
));



// Defaults
// Route::set('default', '(<controller>(/<action>(/<id>)))')
//     ->defaults(array(
//         'controller' => 'index',
//         'action'     => 'index',
//     ));
