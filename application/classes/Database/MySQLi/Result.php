<?php defined('SYSPATH') or die('No direct script access.');
/**
 * MySQLi database result.   See [Results](/database/results) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query/Result
 *
 * @author     Tom Lankhorst
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Database_MySQLi_Result extends Database_Result
{
    protected $_internal_row = 0;

    public function __construct($result, $sql, $as_object = false, array $params = null)
    {
        parent::__construct($result, $sql, $as_object, $params);

        // Find the number of rows in the result
        $this->_total_rows = mysqli_num_rows($result);
    }

    public function __destruct()
    {
        if (is_resource($this->_result)) {
            mysqli_free_result($this->_result);
        }
    }

    public function seek($offset)
    {
        if ($this->offsetExists($offset) and mysqli_data_seek($this->_result, $offset)) {
            // Set the current row to the offset
            $this->_current_row = $this->_internal_row = $offset;

            return true;
        } else {
            return false;
        }
    }

    public function current()
    {
        if ($this->_current_row !== $this->_internal_row and ! $this->seek($this->_current_row)) {
            return null;
        }

        // Increment internal row for optimization assuming rows are fetched in order
        $this->_internal_row++;

        if ($this->_as_object === true) {
            // Return an stdClass
            return mysqli_fetch_object($this->_result);
        } elseif (is_string($this->_as_object)) {
            // Return an object of given class name
            return mysqli_fetch_object($this->_result, $this->_as_object);
        } else {
            // Return an array of the row
            return mysqli_fetch_assoc($this->_result);
        }
    }
} // End Database_MySQLi_Result_Select
