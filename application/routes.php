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

// Route::set('SOCIAL_ACCOUNTS_LINKING', 'linking/<provider>')->defaults(array(
//     'controller'      => 'auth_login',
//     'action'          => 'linking',
//     'siteHitsBlocked' => true
// ));

// Route::set('SOCIAL_ACCOUNTS_UNLINKING', 'unlinking/<provider>')->defaults(array(
//     'controller'      => 'auth_login',
//     'action'          => 'unlinking',
//     'siteHitsBlocked' => true
// ));

// Route::set('logout', 'logout')->defaults(array(
//     'controller'      => 'auth_login',
//     'action'          => 'logout',
//     'siteHitsBlocked' => true
// ));






Route::set('Login', 'login')->defaults(array(
    'controller' => 'auth_login', 'action' => 'login' 
));
Route::set('Logout', 'logout')->defaults(array(
    'controller' => 'auth_login', 'action' => 'logout' 
));

Route::set('Social_Auth', 'login/<provider>')->defaults(array(
    'controller' => 'auth_login',
    'action' => 'social'
));

Route::set('Social_Auth_Callback', 'auth/<provider>')->defaults(array(
    'controller' => 'auth_login', 'action' => 'callback'
));



Route::set('signup', 'signup')->defaults(array(
    'controller' => 'auth_login',
    'action' => 'signup'
));

Route::set('recover', 'recover')->defaults(array(
    'controller' => 'auth_login',
    'action' => 'recover'
));

Route::set('linking', 'linking/<provider>')->defaults(array(
    'controller'      => 'auth_login',
    'action'          => 'linking',

));
Route::set('unlinking', 'unlinking/<provider>')->defaults(array(
    'controller'      => 'auth_login',
    'action'          => 'unlinking'
));

// Defaults
// Route::set('default', '(<controller>(/<action>(/<id>)))')
//     ->defaults(array(
//         'controller' => 'index',
//         'action'     => 'index',
//     ));