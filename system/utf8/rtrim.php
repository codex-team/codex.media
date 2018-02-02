<?php defined('SYSPATH') or die('No direct script access.');
/**
 * UTF8::rtrim
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
function _rtrim($str, $charlist = null)
{
    if ($charlist === null) {
        return rtrim($str);
    }

    if (UTF8::is_ascii($charlist)) {
        return rtrim($str, $charlist);
    }

    $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

    return preg_replace('/[' . $charlist . ']++$/uD', '', $str);
}
