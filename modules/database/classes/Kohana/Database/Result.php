<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Database result wrapper.  See [Results](/database/results) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query/Result
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
abstract class Kohana_Database_Result implements Countable, Iterator, SeekableIterator, ArrayAccess
{

    // Executed SQL for this result
    protected $_query;

    // Raw result resource
    protected $_result;

    // Total number of rows and current row
    protected $_total_rows = 0;
    protected $_current_row = 0;

    // Return rows as an object or associative array
    protected $_as_object;

    // Parameters for __construct when using object results
    protected $_object_params = null;

    /**
     * Sets the total number of rows and stores the result locally.
     *
     * @param mixed  $result    query result
     * @param string $sql       SQL query
     * @param mixed  $as_object
     * @param array  $params
     */
    public function __construct($result, $sql, $as_object = false, array $params = null)
    {
        // Store the result locally
        $this->_result = $result;

        // Store the SQL locally
        $this->_query = $sql;

        if (is_object($as_object)) {
            // Get the object class name
            $as_object = get_class($as_object);
        }

        // Results as objects or associative arrays
        $this->_as_object = $as_object;

        if ($params) {
            // Object constructor params
            $this->_object_params = $params;
        }
    }

    /**
     * Result destruction cleans up all open result sets.
     *
     */
    abstract public function __destruct();

    /**
     * Get a cached database result from the current result iterator.
     *
     *     $cachable = serialize($result->cached());
     *
     * @return Database_Result_Cached
     *
     * @since   3.0.5
     */
    public function cached()
    {
        return new Database_Result_Cached($this->as_array(), $this->_query, $this->_as_object);
    }

    /**
     * Return all of the rows in the result as an array.
     *
     *     // Indexed array of all rows
     *     $rows = $result->as_array();
     *
     *     // Associative array of rows by "id"
     *     $rows = $result->as_array('id');
     *
     *     // Associative array of rows, "id" => "name"
     *     $rows = $result->as_array('id', 'name');
     *
     * @param string $key   column for associative keys
     * @param string $value column for values
     *
     * @return array
     */
    public function as_array($key = null, $value = null)
    {
        $results = [];

        if ($key === null and $value === null) {
            // Indexed rows

            foreach ($this as $row) {
                $results[] = $row;
            }
        } elseif ($key === null) {
            // Indexed columns

            if ($this->_as_object) {
                foreach ($this as $row) {
                    $results[] = $row->$value;
                }
            } else {
                foreach ($this as $row) {
                    $results[] = $row[$value];
                }
            }
        } elseif ($value === null) {
            // Associative rows

            if ($this->_as_object) {
                foreach ($this as $row) {
                    $results[$row->$key] = $row;
                }
            } else {
                foreach ($this as $row) {
                    $results[$row[$key]] = $row;
                }
            }
        } else {
            // Associative columns

            if ($this->_as_object) {
                foreach ($this as $row) {
                    $results[$row->$key] = $row->$value;
                }
            } else {
                foreach ($this as $row) {
                    $results[$row[$key]] = $row[$value];
                }
            }
        }

        $this->rewind();

        return $results;
    }

    /**
     * Return the named column from the current row.
     *
     *     // Get the "id" value
     *     $id = $result->get('id');
     *
     * @param string $name    column to get
     * @param mixed  $default default value if the column does not exist
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $row = $this->current();

        if ($this->_as_object) {
            if (isset($row->$name)) {
                return $row->$name;
            }
        } else {
            if (isset($row[$name])) {
                return $row[$name];
            }
        }

        return $default;
    }

    /**
     * Implements [Countable::count], returns the total number of rows.
     *
     *     echo count($result);
     *
     * @return int
     */
    public function count()
    {
        return $this->_total_rows;
    }

    /**
     * Implements [ArrayAccess::offsetExists], determines if row exists.
     *
     *     if (isset($result[10]))
     *     {
     *         // Row 10 exists
     *     }
     *
     * @param int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ($offset >= 0 and $offset < $this->_total_rows);
    }

    /**
     * Implements [ArrayAccess::offsetGet], gets a given row.
     *
     *     $row = $result[10];
     *
     * @param int $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (! $this->seek($offset)) {
            return null;
        }

        return $this->current();
    }

    /**
     * Implements [ArrayAccess::offsetSet], throws an error.
     *
     * [!!] You cannot modify a database result.
     *
     * @param int   $offset
     * @param mixed $value
     *
     * @throws Kohana_Exception
     */
    final public function offsetSet($offset, $value)
    {
        throw new Kohana_Exception('Database results are read-only');
    }

    /**
     * Implements [ArrayAccess::offsetUnset], throws an error.
     *
     * [!!] You cannot modify a database result.
     *
     * @param int $offset
     *
     * @throws Kohana_Exception
     */
    final public function offsetUnset($offset)
    {
        throw new Kohana_Exception('Database results are read-only');
    }

    /**
     * Implements [Iterator::key], returns the current row number.
     *
     *     echo key($result);
     *
     * @return int
     */
    public function key()
    {
        return $this->_current_row;
    }

    /**
     * Implements [Iterator::next], moves to the next row.
     *
     *     next($result);
     *
     * @return $this
     */
    public function next()
    {
        ++$this->_current_row;

        return $this;
    }

    /**
     * Implements [Iterator::prev], moves to the previous row.
     *
     *     prev($result);
     *
     * @return $this
     */
    public function prev()
    {
        --$this->_current_row;

        return $this;
    }

    /**
     * Implements [Iterator::rewind], sets the current row to zero.
     *
     *     rewind($result);
     *
     * @return $this
     */
    public function rewind()
    {
        $this->_current_row = 0;

        return $this;
    }

    /**
     * Implements [Iterator::valid], checks if the current row exists.
     *
     * [!!] This method is only used internally.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->offsetExists($this->_current_row);
    }
} // End Database_Result
