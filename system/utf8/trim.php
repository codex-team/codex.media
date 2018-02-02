<?php defined('SYSPATH') or die('No direct script access.');
/**
 * UTF8::trim
 *
 * @package    Kohana
 *
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 *
 * @param mixed      $str
 * @param null|mixed $charlist
 */
function _trim($str, $charlist = null)
{
    if ($charlist === null) {
        return trim($str);
    }

    return UTF8::ltrim(UTF8::rtrim($str, $charlist), $charlist);
}
