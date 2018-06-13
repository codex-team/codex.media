<?php defined('SYSPATH') or die('No direct script access.');
/**
 * MySQLi database connection. Based on standard MySQL driver.
 *
 * @package    Kohana/Database
 * @category   Drivers
 *
 * @author     Tom Lankhorst (Webhub)
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Database_MySQLi extends Database
{

    // Database in use by each connection
    protected static $_current_databases = [];

    // Use SET NAMES to set the character set
    protected static $_set_names;

    // Identifier for this connection within the PHP driver
    protected $_connection_id;

    // MySQLi uses a backtick for identifiers
    protected $_identifier = '`';

    public function connect()
    {
        if ($this->_connection) {
            return;
        }

        if (Database_MySQLi::$_set_names === null) {
            // Determine if we can use mysqli_set_charset(), which is only
            // available on PHP 5.2.3+ when compiled against MySQLi 5.0+
            Database_MySQLi::$_set_names = ! function_exists('mysqli_set_charset');
        }

        // Extract the connection parameters, adding required variabels
        extract($this->_config['connection'] + [
            'database' => '',
            'hostname' => '',
            'username' => '',
            'password' => '',
            'port' => null,
            'socket' => ''
        ]);

        // Prevent this information from showing up in traces
        unset($this->_config['connection']['username'], $this->_config['connection']['password']);

        try {
            $this->_connection = mysqli_connect($hostname, $username, $password, $database, $port, $socket);
        } catch (Exception $e) {
            // No connection exists
            $this->_connection = null;

            throw new Database_Exception(':error',
                [':error' => $e->getMessage()],
                $e->getCode());
        }

        // \xFF is a better delimiter, but the PHP driver uses underscore
        $this->_connection_id = sha1($hostname . '_' . $username . '_' . $password);

        $this->_select_db($database);

        if (! empty($this->_config['charset'])) {
            // Set the character set
            $this->set_charset($this->_config['charset']);
        }

        if (! empty($this->_config['connection']['variables'])) {
            // Set session variables
            $variables = [];

            foreach ($this->_config['connection']['variables'] as $var => $val) {
                $variables[] = 'SESSION ' . $var . ' = ' . $this->quote($val);
            }

            mysqli_query($this->_connection, 'SET ' . implode(', ', $variables));
        }
    }

    /**
     * Select the database
     *
     * @param string $database Database
     */
    protected function _select_db($database)
    {
        // in MySQLi, the DB is already selected

        Database_MySQLi::$_current_databases[$this->_connection_id] = $database;
    }

    public function disconnect()
    {
        try {
            // Database is assumed disconnected
            $status = true;

            if (is_resource($this->_connection)) {
                if ($status = mysqli_close($this->_connection)) {
                    // Clear the connection
                    $this->_connection = null;

                    // Clear the instance
                    parent::disconnect();
                }
            }
        } catch (Exception $e) {
            // Database is probably not disconnected
            $status = ! is_resource($this->_connection);
        }

        return $status;
    }

    public function set_charset($charset)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if (Database_MySQLi::$_set_names === true) {
            // PHP is compiled against MySQLi 4.x
            $status = (bool) mysqli_query($this->_connection, 'SET NAMES ' . $this->quote($charset));
        } else {
            // PHP is compiled against MySQLi 5.x
            $status = mysqli_set_charset($this->_connection, $charset);
        }

        if ($status === false) {
            throw new Database_Exception(':error',
                [':error' => mysqli_error($this->_connection)],
                mysqli_errno($this->_connection));
        }
    }

    public function query($type, $sql, $as_object = false, array $params = null)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if (Kohana::$profiling) {
            // Benchmark this query for the current instance
            $benchmark = Profiler::start("Database ({$this->_instance})", $sql);
        }

        // Execute the query
        if (($result = mysqli_query($this->_connection, $sql)) === false) {
            if (isset($benchmark)) {
                // This benchmark is worthless
                Profiler::delete($benchmark);
            }

            throw new Database_Exception(':error [ :query ]',
                [':error' => mysqli_error($this->_connection), ':query' => $sql],
                mysqli_errno($this->_connection));
        }

        if (isset($benchmark)) {
            Profiler::stop($benchmark);
        }

        // Set the last query
        $this->last_query = $sql;

        if ($type === Database::SELECT) {
            // Return an iterator of results
            return new Database_MySQLi_Result($result, $sql, $as_object, $params);
        } elseif ($type === Database::INSERT) {
            // Return a list of insert id and rows created
            return [
                mysqli_insert_id($this->_connection),
                mysqli_affected_rows($this->_connection),
            ];
        } else {
            // Return the number of rows affected
            return mysqli_affected_rows($this->_connection);
        }
    }

    public function datatype($type)
    {
        static $types = [
            'blob' => ['type' => 'string', 'binary' => true, 'character_maximum_length' => '65535'],
            'bool' => ['type' => 'bool'],
            'bigint unsigned' => ['type' => 'int', 'min' => '0', 'max' => '18446744073709551615'],
            'datetime' => ['type' => 'string'],
            'decimal unsigned' => ['type' => 'float', 'exact' => true, 'min' => '0'],
            'double' => ['type' => 'float'],
            'double precision unsigned' => ['type' => 'float', 'min' => '0'],
            'double unsigned' => ['type' => 'float', 'min' => '0'],
            'enum' => ['type' => 'string'],
            'fixed' => ['type' => 'float', 'exact' => true],
            'fixed unsigned' => ['type' => 'float', 'exact' => true, 'min' => '0'],
            'float unsigned' => ['type' => 'float', 'min' => '0'],
            'int unsigned' => ['type' => 'int', 'min' => '0', 'max' => '4294967295'],
            'integer unsigned' => ['type' => 'int', 'min' => '0', 'max' => '4294967295'],
            'longblob' => ['type' => 'string', 'binary' => true, 'character_maximum_length' => '4294967295'],
            'longtext' => ['type' => 'string', 'character_maximum_length' => '4294967295'],
            'mediumblob' => ['type' => 'string', 'binary' => true, 'character_maximum_length' => '16777215'],
            'mediumint' => ['type' => 'int', 'min' => '-8388608', 'max' => '8388607'],
            'mediumint unsigned' => ['type' => 'int', 'min' => '0', 'max' => '16777215'],
            'mediumtext' => ['type' => 'string', 'character_maximum_length' => '16777215'],
            'national varchar' => ['type' => 'string'],
            'numeric unsigned' => ['type' => 'float', 'exact' => true, 'min' => '0'],
            'nvarchar' => ['type' => 'string'],
            'point' => ['type' => 'string', 'binary' => true],
            'real unsigned' => ['type' => 'float', 'min' => '0'],
            'set' => ['type' => 'string'],
            'smallint unsigned' => ['type' => 'int', 'min' => '0', 'max' => '65535'],
            'text' => ['type' => 'string', 'character_maximum_length' => '65535'],
            'tinyblob' => ['type' => 'string', 'binary' => true, 'character_maximum_length' => '255'],
            'tinyint' => ['type' => 'int', 'min' => '-128', 'max' => '127'],
            'tinyint unsigned' => ['type' => 'int', 'min' => '0', 'max' => '255'],
            'tinytext' => ['type' => 'string', 'character_maximum_length' => '255'],
            'year' => ['type' => 'string'],
        ];

        $type = str_replace(' zerofill', '', $type);

        if (isset($types[$type])) {
            return $types[$type];
        }

        return parent::datatype($type);
    }

    /**
     * Start a SQL transaction
     *
     * @link http://dev.mysql.com/doc/refman/5.0/en/set-transaction.html
     *
     * @param string $mode Isolation level
     *
     * @return bool
     */
    public function begin($mode = null)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if ($mode and ! mysqli_query($this->_connection, "SET TRANSACTION ISOLATION LEVEL $mode")) {
            throw new Database_Exception(':error',
                [':error' => mysqli_error($this->_connection)],
                mysqli_errno($this->_connection));
        }

        return (bool) mysqli_query($this->_connection, 'START TRANSACTION');
    }

    /**
     * Commit a SQL transaction
     *
     * @return bool
     */
    public function commit()
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        return (bool) mysqli_query($this->_connection, 'COMMIT');
    }

    /**
     * Rollback a SQL transaction
     *
     * @return bool
     */
    public function rollback()
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        return (bool) mysqli_query($this->_connection, 'ROLLBACK');
    }

    public function list_tables($like = null)
    {
        if (is_string($like)) {
            // Search for table names
            $result = $this->query(Database::SELECT, 'SHOW TABLES LIKE ' . $this->quote($like), false);
        } else {
            // Find all table names
            $result = $this->query(Database::SELECT, 'SHOW TABLES', false);
        }

        $tables = [];
        foreach ($result as $row) {
            $tables[] = reset($row);
        }

        return $tables;
    }

    public function list_columns($table, $like = null, $add_prefix = true)
    {
        // Quote the table name
        $table = ($add_prefix === true) ? $this->quote_table($table) : $table;

        if (is_string($like)) {
            // Search for column names
            $result = $this->query(Database::SELECT, 'SHOW FULL COLUMNS FROM ' . $table . ' LIKE ' . $this->quote($like), false);
        } else {
            // Find all column names
            $result = $this->query(Database::SELECT, 'SHOW FULL COLUMNS FROM ' . $table, false);
        }

        $count = 0;
        $columns = [];
        foreach ($result as $row) {
            list($type, $length) = $this->_parse_type($row['Type']);

            $column = $this->datatype($type);

            $column['column_name'] = $row['Field'];
            $column['column_default'] = $row['Default'];
            $column['data_type'] = $type;
            $column['is_nullable'] = ($row['Null'] == 'YES');
            $column['ordinal_position'] = ++$count;

            switch ($column['type']) {
                case 'float':
                    if (isset($length)) {
                        list($column['numeric_precision'], $column['numeric_scale']) = explode(',', $length);
                    }
                break;
                case 'int':
                    if (isset($length)) {
                        // MySQLi attribute
                        $column['display'] = $length;
                    }
                break;
                case 'string':
                    switch ($column['data_type']) {
                        case 'binary':
                        case 'varbinary':
                            $column['character_maximum_length'] = $length;
                        break;
                        case 'char':
                        case 'varchar':
                            $column['character_maximum_length'] = $length;
                            // no break
                        case 'text':
                        case 'tinytext':
                        case 'mediumtext':
                        case 'longtext':
                            $column['collation_name'] = $row['Collation'];
                        break;
                        case 'enum':
                        case 'set':
                            $column['collation_name'] = $row['Collation'];
                            $column['options'] = explode('\',\'', substr($length, 1, -1));
                        break;
                    }
                break;
            }

            // MySQLi attributes
            $column['comment'] = $row['Comment'];
            $column['extra'] = $row['Extra'];
            $column['key'] = $row['Key'];
            $column['privileges'] = $row['Privileges'];

            $columns[$row['Field']] = $column;
        }

        return $columns;
    }

    public function escape($value)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if (($value = mysqli_real_escape_string($this->_connection, (string) $value)) === false) {
            throw new Database_Exception(':error',
                [':error' => mysqli_error($this->_connection)],
                mysqli_errno($this->_connection));
        }

        // SQL standard is to use single-quotes for all values
        return "'$value'";
    }
} // End Database_MySQLi
