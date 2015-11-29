<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */


$DIGIT = '\d+';
$STRING = '[-a-z\d]+';

// -----------------------------------------------------------------------------
// DEFAULT
// -----------------------------------------------------------------------------
Route::set('index', '')->defaults(array(
    'controller' => 'index',
    'action' => 'index'
));
// -----------------------------------------------------------------------------
// DYNAMIC PAGES
// -----------------------------------------------------------------------------
Route::set('VIEW_PAGE', 'page/(<id>)(<uri>)', array( 'id' => $DIGIT , 'uri' => $STRING ))->defaults(array(
    'controller' => 'pages',
    'action' => 'page'
));
// -----------------------------------------------------------------------------
// STATIC PAGES
// -----------------------------------------------------------------------------
Route::set('Contacts', 'contacts')->defaults(array(
    'controller' => 'index',
    'action' => 'contacts'
));
// -----------------------------------------------------------------------------
// USER SECTION
// -----------------------------------------------------------------------------
Route::set('USER', 'user(/<action>(/<id>))', array('action' => $STRING, 'id' => $DIGIT ))->defaults(array(
    'controller' => 'user'
));
// -----------------------------------------------------------------------------
// ADMIN SECTION
// -----------------------------------------------------------------------------
Route::set('ADMIN_PAGE', 'admin(/<page>(/<id>))')->defaults(array(
    'controller' => 'admin',
    'action' => 'index'
));
// -----------------------------------------------------------------------------