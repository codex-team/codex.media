<?php defined('SYSPATH') or die('No direct script access.');

// Catch-all route for Codebench classes to run
Route::set('codebench', 'codebench(/<class>)')
    ->defaults([
        'controller' => 'Codebench',
        'action' => 'index',
        'class' => null]);
