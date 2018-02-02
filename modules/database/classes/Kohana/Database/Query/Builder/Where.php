<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Database query builder for WHERE statements. See [Query Builder](/database/query/builder) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
abstract class Kohana_Database_Query_Builder_Where extends Database_Query_Builder
{

    // WHERE ...
    protected $_where = [];

    // ORDER BY ...
    protected $_order_by = [];

    // LIMIT ...
    protected $_limit = null;

    /**
     * Alias of and_where()
     *
     * @param mixed  $column column name or array($column, $alias) or object
     * @param string $op     logic operator
     * @param mixed  $value  column value
     *
     * @return $this
     */
    public function where($column, $op, $value)
    {
        return $this->and_where($column, $op, $value);
    }

    /**
     * Creates a new "AND WHERE" condition for the query.
     *
     * @param mixed  $column column name or array($column, $alias) or object
     * @param string $op     logic operator
     * @param mixed  $value  column value
     *
     * @return $this
     */
    public function and_where($column, $op, $value)
    {
        $this->_where[] = ['AND' => [$column, $op, $value]];

        return $this;
    }

    /**
     * Creates a new "OR WHERE" condition for the query.
     *
     * @param mixed  $column column name or array($column, $alias) or object
     * @param string $op     logic operator
     * @param mixed  $value  column value
     *
     * @return $this
     */
    public function or_where($column, $op, $value)
    {
        $this->_where[] = ['OR' => [$column, $op, $value]];

        return $this;
    }

    /**
     * Alias of and_where_open()
     *
     * @return $this
     */
    public function where_open()
    {
        return $this->and_where_open();
    }

    /**
     * Opens a new "AND WHERE (...)" grouping.
     *
     * @return $this
     */
    public function and_where_open()
    {
        $this->_where[] = ['AND' => '('];

        return $this;
    }

    /**
     * Opens a new "OR WHERE (...)" grouping.
     *
     * @return $this
     */
    public function or_where_open()
    {
        $this->_where[] = ['OR' => '('];

        return $this;
    }

    /**
     * Closes an open "WHERE (...)" grouping.
     *
     * @return $this
     */
    public function where_close()
    {
        return $this->and_where_close();
    }

    /**
     * Closes an open "WHERE (...)" grouping or removes the grouping when it is
     * empty.
     *
     * @return $this
     */
    public function where_close_empty()
    {
        $group = end($this->_where);

        if ($group and reset($group) === '(') {
            array_pop($this->_where);

            return $this;
        }

        return $this->where_close();
    }

    /**
     * Closes an open "WHERE (...)" grouping.
     *
     * @return $this
     */
    public function and_where_close()
    {
        $this->_where[] = ['AND' => ')'];

        return $this;
    }

    /**
     * Closes an open "WHERE (...)" grouping.
     *
     * @return $this
     */
    public function or_where_close()
    {
        $this->_where[] = ['OR' => ')'];

        return $this;
    }

    /**
     * Applies sorting with "ORDER BY ..."
     *
     * @param mixed  $column    column name or array($column, $alias) or object
     * @param string $direction direction of sorting
     *
     * @return $this
     */
    public function order_by($column, $direction = null)
    {
        $this->_order_by[] = [$column, $direction];

        return $this;
    }

    /**
     * Return up to "LIMIT ..." results
     *
     * @param int $number maximum results to return or NULL to reset
     *
     * @return $this
     */
    public function limit($number)
    {
        $this->_limit = $number;

        return $this;
    }
} // End Database_Query_Builder_Where
