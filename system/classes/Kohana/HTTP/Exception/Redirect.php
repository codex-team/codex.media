<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Redirect HTTP exception class. Used for all [HTTP_Exception]'s where the status
 * code indicates a redirect.
 *
 * Eg [HTTP_Exception_301], [HTTP_Exception_302] and most of the other 30x's
 *
 * @package    Kohana
 * @category   Exceptions
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Kohana_HTTP_Exception_Redirect extends HTTP_Exception_Expected
{
    /**
     * Specifies the URI to redirect to.
     *
     * @param string     $location URI of the proxy
     * @param null|mixed $uri
     */
    public function location($uri = null)
    {
        if ($uri === null) {
            return $this->headers('Location');
        }

        if (strpos($uri, '://') === false) {
            // Make the URI into a URL
            $uri = URL::site($uri, true, ! empty(Kohana::$index_file));
        }

        $this->headers('Location', $uri);

        return $this;
    }

    /**
     * Validate this exception contains everything needed to continue.
     *
     * @throws Kohana_Exception
     *
     * @return bool
     */
    public function check()
    {
        if ($this->headers('location') === null) {
            throw new Kohana_Exception('A \'location\' must be specified for a redirect');
        }

        return true;
    }
} // End Kohana_HTTP_Exception_Redirect
