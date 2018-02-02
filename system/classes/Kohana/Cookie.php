<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cookie helper.
 *
 * @package    Kohana
 * @category   Helpers
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Cookie
{

    /**
     * @var string Magic salt to add to the cookie
     */
    public static $salt = null;

    /**
     * @var int Number of seconds before the cookie expires
     */
    public static $expiration = 0;

    /**
     * @var string Restrict the path that the cookie is available to
     */
    public static $path = '/';

    /**
     * @var string Restrict the domain that the cookie is available to
     */
    public static $domain = null;

    /**
     * @var bool Only transmit cookies over secure connections
     */
    public static $secure = false;

    /**
     * @var bool Only transmit cookies over HTTP, disabling Javascript access
     */
    public static $httponly = false;

    /**
     * Gets the value of a signed cookie. Cookies without signatures will not
     * be returned. If the cookie signature is present, but invalid, the cookie
     * will be deleted.
     *
     *     // Get the "theme" cookie, or use "blue" if the cookie does not exist
     *     $theme = Cookie::get('theme', 'blue');
     *
     * @param string $key     cookie name
     * @param mixed  $default default value to return
     *
     * @return string
     */
    public static function get($key, $default = null)
    {
        if (! isset($_COOKIE[$key])) {
            // The cookie does not exist
            return $default;
        }

        // Get the cookie value
        $cookie = $_COOKIE[$key];

        // Find the position of the split between salt and contents
        $split = strlen(Cookie::salt($key, null));

        if (isset($cookie[$split]) and $cookie[$split] === '~') {
            // Separate the salt and the value
            list($hash, $value) = explode('~', $cookie, 2);

            if (Cookie::salt($key, $value) === $hash) {
                // Cookie signature is valid
                return $value;
            }

            // The cookie signature is invalid, delete it
            Cookie::delete($key);
        }

        return $default;
    }

    /**
     * Sets a signed cookie. Note that all cookie values must be strings and no
     * automatic serialization will be performed!
     *
     *     // Set the "theme" cookie
     *     Cookie::set('theme', 'red');
     *
     * @param string $name       name of cookie
     * @param string $value      value of cookie
     * @param int    $expiration lifetime in seconds
     *
     * @return bool
     *
     * @uses    Cookie::salt
     */
    public static function set($name, $value, $expiration = null)
    {
        if ($expiration === null) {
            // Use the default expiration
            $expiration = Cookie::$expiration;
        }

        if ($expiration !== 0) {
            // The expiration is expected to be a UNIX timestamp
            $expiration += time();
        }

        // Add the salt to the cookie value
        $value = Cookie::salt($name, $value) . '~' . $value;

        return setcookie($name, $value, $expiration, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$httponly);
    }

    /**
     * Deletes a cookie by making the value NULL and expiring it.
     *
     *     Cookie::delete('theme');
     *
     * @param string $name cookie name
     *
     * @return bool
     *
     * @uses    Cookie::set
     */
    public static function delete($name)
    {
        // Remove the cookie
        unset($_COOKIE[$name]);

        // Nullify the cookie and make it expire
        return setcookie($name, null, -86400, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$httponly);
    }

    /**
     * Generates a salt string for a cookie based on the name and value.
     *
     *     $salt = Cookie::salt('theme', 'red');
     *
     * @param string $name  name of cookie
     * @param string $value value of cookie
     *
     * @return string
     */
    public static function salt($name, $value)
    {
        // Require a valid salt
        if (! Cookie::$salt) {
            throw new Kohana_Exception('A valid cookie salt is required. Please set Cookie::$salt.');
        }

        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return sha1($agent . $name . $value . Cookie::$salt);
    }
} // End cookie
