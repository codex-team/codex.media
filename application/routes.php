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

Route::set('index', '')->defaults(array(
    'controller' => 'index',
    'action' => 'index'
));

Route::set('VIEW_PAGE', 'page/(<id>)(<uri>)', array( 'id' => $DIGIT , 'uri' => $STRING ))->defaults(array(
    'controller' => 'pages',
    'action' => 'page'
));

Route::set('PROFILE', 'user/<id>', array( 'id' => $DIGIT ))->defaults(array(
    'controller' => 'user',
    'action' => 'profile'
));

Route::set('Contacts', 'contacts')->defaults(array(
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





Route::set('AJAX_FILE_TRANSPORT', 'ajax/transport')->defaults(array(
    'controller'      => 'ajax',
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

Route::set('ADD_COMMENT_SCRIPT', 'page/addcomment(/<id>)(/<uri>)', array( 'id' => $DIGIT , 'uri' => $STRING ))->defaults(array(
    'controller' => 'comments',
    'action' => 'add'
));

Route::set('DEL_COMMENT_SCRIPT', 'page/(<id>)(<uri>)/delcomment/<comment_id>', array(
    'comment_id'    => $DIGIT, 
    'id'            => $DIGIT, 
    'uri'           => $STRING ))->defaults(array(
    'controller' => 'comments',
    'action' => 'delete'
));




// Defaults
// Route::set('default', '(<controller>(/<action>(/<id>)))')
//     ->defaults(array(
//         'controller' => 'index',
//         'action'     => 'index',
//     ));