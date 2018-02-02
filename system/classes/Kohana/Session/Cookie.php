<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cookie-based session class.
 *
 * @package    Kohana
 * @category   Session
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Session_Cookie extends Session
{
    /**
     * @param string $id session id
     *
     * @return string
     */
    protected function _read($id = null)
    {
        return Cookie::get($this->_name, null);
    }

    /**
     */
    protected function _regenerate()
    {
        // Cookie sessions have no id
        return null;
    }

    /**
     * @return bool
     */
    protected function _write()
    {
        return Cookie::set($this->_name, $this->__toString(), $this->_lifetime);
    }

    /**
     * @return bool
     */
    protected function _restart()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function _destroy()
    {
        return Cookie::delete($this->_name);
    }
} // End Session_Cookie
