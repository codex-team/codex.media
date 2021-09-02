<?php


/**
 * The directory in which your application specific resources are located.
 * The application directory must contain the bootstrap.php file.
 *
 * @link http://kohanaframework.org/guide/about.install#application
 */
$application = 'application';

/**
 * The directory in which your modules are located.
 *
 * @link http://kohanaframework.org/guide/about.install#modules
 */
$modules = 'modules';

/**
 * The directory in which the Kohana resources are located. The system
 * directory must contain the classes/kohana.php file.
 *
 * @link http://kohanaframework.org/guide/about.install#system
 */
$system = 'system';

/**
 * The directory where installed composer libs
 */
$vendor = 'vendor';

/**
 * The directory where public files are stored
 */
$public = 'public';

/**
 * The default extension of resource files. If you change this, all resources
 * must be renamed to use the new extension.
 *
 * @link http://kohanaframework.org/guide/about.install#ext
 */
define('EXT', '.php');

/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 *
 * @link http://www.php.net/manual/errorfunc.configuration#ini.error-reporting
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 *
 * When using a legacy application with PHP >= 5.3, it is recommended to disable
 * deprecated notices. Disable with: E_ALL & ~E_DEPRECATED
 */
error_reporting(E_ALL | E_STRICT);

/**
 * End of standard configuration! Changing any of the code below should only be
 * attempted by those with a working knowledge of Kohana internals.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 */

// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

// Make the application relative to the docroot, for symlink'd index.php
if (! is_dir($application) and is_dir(DOCROOT . $application)) {
    $application = DOCROOT . $application;
}

// Make the modules relative to the docroot, for symlink'd index.php
if (! is_dir($modules) and is_dir(DOCROOT . $modules)) {
    $modules = DOCROOT . $modules;
}

// Make the system relative to the docroot, for symlink'd index.php
if (! is_dir($system) and is_dir(DOCROOT . $system)) {
    $system = DOCROOT . $system;
}

// Make the vendor relative to the docroot, for symlink'd index.php
if (! is_dir($vendor) and is_dir(DOCROOT . $vendor)) {
    $vendor = DOCROOT . $vendor;
}

// Make the public relative to the docroot, for symlink'd index.php
if (! is_dir($public) and is_dir(DOCROOT . $public)) {
    $public = DOCROOT . $public;
}

// Define the absolute paths for configured directories
define('APPPATH', realpath($application) . DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules) . DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system) . DIRECTORY_SEPARATOR);
define('VENDORPATH', realpath($vendor) . DIRECTORY_SEPARATOR);
define('PUBLICPATH', realpath($public) . DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($application, $modules, $system, $vendor, $public);

// Load Composer autoload
require VENDORPATH . 'autoload' . EXT;

/**
 * Load Dotenv
 *
 * @see https://github.com/vlucas/phpdotenv
 */
if (is_file(DOCROOT . '.env')) {
    $dotenv = new Dotenv\Dotenv(DOCROOT);
    $dotenv->load();
}

/**
 * Check for maintenance dummy page
 */
include 'maintenance' . EXT;

/**
 * Load the installation check
 */
if (file_exists('install' . EXT)) {
    return include 'install' . EXT;
}

/**
 * Define the start time of the application, used for profiling.
 */
if (! defined('KOHANA_START_TIME')) {
    define('KOHANA_START_TIME', microtime(true));
}

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
if (! defined('KOHANA_START_MEMORY')) {
    define('KOHANA_START_MEMORY', memory_get_usage());
}

// Bootstrap the application
require APPPATH . 'bootstrap' . EXT;

if (PHP_SAPI == 'cli') { // Try and load minion
    class_exists('Minion_Task') or die('Please enable the Minion module for CLI support.');
    set_exception_handler(['Minion_Exception', 'handler']);

    Minion_Task::factory(Minion_CLI::options())->execute();
} else {
    /**
     * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
     * If no source is specified, the URI will be automatically detected.
     */
    echo Request::factory(true, [], false)
        ->execute()
        ->send_headers(true)
        ->body();
}
