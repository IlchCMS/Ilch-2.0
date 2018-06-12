<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database;

class Mysql
{
    const IGNORE_ERRORS = 0;
    const THROW_EXCEPTIONS = 1;
    const OUTPUT_ERRORS = 2;

    const FORMAT_DATETIME = 'Y-m-d H:i:s';
    const FORMAT_DATE = 'Y-m-d';

    public static $errorHandling = self::THROW_EXCEPTIONS;
    
    /**
     * @var string|null
     */
    protected $prefix = null;

    /**
     * @var \mysqli|null
     */
    protected $conn = null;

    /**
     * Close database connection.
     */
    public function __destruct()
    {
        if ($this->conn !== null) {
            @$this->conn->close();
        }
    }

    /**
     * Set the table prefix.
     *
     * @param string $pref
     */
    public function setPrefix($pref)
    {
        $this->prefix = $pref;
    }

    /**
     * Gets the table prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get the mysqli object.
     *
     * @return \mysqli
     */
    public function getLink()
    {
        return $this->conn;
    }

    /**
     * Set the database.
     *
     * @param string $db
     * @return bool success
     */
    public function setDatabase($db)
    {
        if ($this->conn->connect_error) {
            return false;
        }

        return @$this->conn->select_db($db);
    }

    /**
     * Connects to database.
     *
     * @param string $host
     * @param string $name
     * @param string $password
     * @param integer|null $port
     * @throws \RuntimeException
     */
    public function connect($host, $name, $password, $port = null)
    {
        $this->conn = @new \mysqli($host, $name, $password, null, $port);
        if (mysqli_connect_error() !== null) {
            throw new \RuntimeException('Cannot connect to database.');
        }

        $this->conn->set_charset('utf8');

        $mode = "SET SESSION sql_mode = 'ONLY_FULL_GROUP_BY'";
        mysqli_query($this->conn, $mode);
    }

    /**
     * Execute sql query.
     *
     * @param  string $sql
     * @return \mysqli_result
     * @throws Exception
     */
    public function query($sql)
    {
        $sql = $this->getSqlWithPrefix($sql);
        $mysqliResult = mysqli_query($this->conn, $sql);

        if (!$mysqliResult) {
            $this->handleError($sql);
        }

        return $mysqliResult;
    }

    /**
     * Adds prefix to tables.
     *
     * @param string $sql
     * @return string
     */
    public function getSqlWithPrefix($sql)
    {
        if (preg_match("/^UPDATE `?\[prefix\]_\S+`?\s+SET/is", $sql)) {
            $sql = preg_replace(
                "/^UPDATE `?\[prefix\]_(\S+?)`?([\s\.,]|$)/i",
                "UPDATE `" . $this->prefix . "\\1`\\2",
                $sql
            );
        } elseif (preg_match("/^INSERT INTO `?\[prefix\]_\S+`?\s+[a-z0-9\s,\)\(]*?VALUES/is", $sql)) {
            $sql = preg_replace(
                "/^INSERT INTO `?\[prefix\]_(\S+?)`?([\s\.,]|$)/i",
                "INSERT INTO `" . $this->prefix . "\\1`\\2",
                $sql
            );
        } else {
            $sql = preg_replace("/\[prefix\]_(\S+?)([\s\.,]|$)/", $this->prefix . "\\1\\2", $sql);
        }

        return $sql;
    }

    /**
     * Returns number of affected rows of the last query
     * @return integer
     */
    public function getAffectedRows()
    {
        return (int) $this->conn->affected_rows;
    }

    /**
     * Returns last auto generated primary key
     * @return integer|null
     */
    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }

    /**
     * Create Select Statement Query Builder
     * @param array|string|null $fields
     * @param string|null $table table without prefix
     * @param array|null $where conditions @see QueryBuilder::where()
     * @param array|null $orderBy
     * @param array|int|null $limit
     * @return Mysql\Select
     */
    public function select($fields = null, $table = null, $where = null, array $orderBy = null, $limit = null)
    {
        return new Mysql\Select($this, $fields, $table, $where, $orderBy, $limit);
    }

    /**
     * Select on cell from table.
     *
     * @param  string $sql
     * @return string|int
     * @throws Exception
     */
    public function queryCell($sql)
    {
        $row = mysqli_fetch_row($this->query($sql));

        return $row[0];
    }

    /**
     * Check if table exists.
     *
     * @param  string $table
     * @return true|false
     * @throws Exception
     */
    public function ifTableExists($table)
    {
        $sql = "SHOW TABLES LIKE '$table'";
        $result = $this->query($sql);

        if (mysqli_num_rows($result) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if column in table exists.
     *
     * @param  string $table
     * @param  string $column
     * @return true|false
     * @throws Exception
     */
    public function ifColumnExists($table, $column)
    {
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = '$column' AND TABLE_NAME = '$table'";
        $result = $this->query($sql);

        if (mysqli_num_rows($result) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Select one row from table.
     *
     * @param  string $sql
     * @return array|null
     * @throws Exception
     */
    public function queryRow($sql)
    {
        $row = mysqli_fetch_assoc($this->query($sql));

        return $row;
    }

    /**
     * Select an array from db-table.
     *
     * @param  string $sql
     * @return array
     * @throws Exception
     */
    public function queryArray($sql)
    {
        $rows = [];
        $result = $this->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Select a list from db-table.
     *
     * @param  string $sql
     * @return array
     * @throws Exception
     */
    public function queryList($sql)
    {
        $list = [];
        $result = $this->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = reset($row);
        }

        return $list;
    }

    /**
     * Create Update Query Builder
     *
     * @param string|null $table
     * @param array|null $values values as [name => value]
     * @param array|null $where conditions @see QueryBuilder::where()
     *
     * @return \Ilch\Database\Mysql\Update
     */
    public function update($table = null, $values = null, $where = null)
    {
        return new Mysql\Update($this, $table, $values, $where);
    }

    /**
     * Create Insert Query Builder
     *
     * @param string|null $into table without prefix
     * @param array|null $values values as [name => value]
     *
     * @return \Ilch\Database\Mysql\Insert
     */
    public function insert($into = null, $values = null)
    {
        return new Mysql\Insert($this, $into, $values);
    }

    /**
     * Create Delete Query Builder
     *
     * @param string|null $from table without prefix
     * @param array|null $where conditions @see QueryBuilder::where()
     *
     * @return \Ilch\Database\Mysql\Delete
     */
    public function delete($from = null, $where = null)
    {
        return new Mysql\Delete($this, $from, $where);
    }

    /**
     * Drops the table from database.
     *
     * @todo why no prefix usage?? at least as option
     *
     * @param string $table
     * @return \mysqli_result
     * @throws Exception
     */
    public function drop($table)
    {
        $sql = 'DROP TABLE `' . $table . '`';

        return $this->query($sql);
    }

    /**
     * Truncate the table.
     *
     * @param string $table
     * @return \mysqli_result
     * @throws Exception
     */
    public function truncate($table)
    {
        $sql = 'TRUNCATE TABLE `' . $table . '`';
        $sql = $this->getSqlWithPrefix($sql);

        return $this->query($sql);
    }

    /**
     * Create the field part for the given array.
     *
     * @param  array $fields
     * @return string
     */
    protected function getFieldsSql($fields)
    {
        if (!is_array($fields) && ($fields === '*' || strpos($fields, '(') !== false)) {
            return $fields;
        }

        return '`' . implode('`,`', (array)$fields) . '`';
    }

    /**
     * Create the where part for the given array.
     *
     * @param  array $where
     * @return string
     */
    protected function getWhereSql($where)
    {
        $sql = '';

        foreach ($where as $key => $value) {
            $sql .= 'AND `' . $key . '` = "' . $this->escape($value) . '" ';
        }

        return $sql;
    }

    /**
     * Quotes a field name
     *
     * @param string $field field, f.e. field, a.field, table.field
     * @param boolean $complete [default: false] quotes complete field
     *
     * @return string
     * @throws \InvalidArgumentException for invalid field expressions
     */
    public function quote($field, $complete = false)
    {
        if ($complete || strpos($field, '.') === false) {
            return '`' . $field . '`';
        }
        $parts = explode('.', $field);
        if (count($parts) > 2) {
            throw new \InvalidArgumentException('Invalid field expression: ' . $field);
        }
        return '`' . $parts[0] . '`.`' . $parts[1] . '`';
    }

    /**
     * Escape the given value for a sql query. Optionally add quotes
     *
     * @param  string $value
     * @param  boolean $andQuote [default: false] add quotes around
     * @return string
     */
    public function escape($value, $andQuote = false)
    {
        $escaped = mysqli_real_escape_string($this->conn, $value);

        if ($andQuote == true) {
            $escaped = '"' . $escaped . '"';
        }
        return $escaped;
    }

    /**
     * Escapes every value in a array for a sql query. Optionally add quotes
     *
     * @param array $array
     * @param bool $andQuote [default: false] add quotes around each value
     * @return array
     */
    public function escapeArray(array $array, $andQuote = false)
    {
        foreach ($array as &$value) {
            $value = $this->escape($value, $andQuote);
        }
        return $array;
    }

    /**
     * Executes multiple queries given in one string within a single request.
     *
     * @param  string $sql The string with the multiple queries.
     * @return boolean false if the first statement failed. Otherwise true.
     * @throws Exception
     */
    public function queryMulti($sql)
    {
        $result = false;
        $sql = $this->getSqlWithPrefix($sql);
        $subNo = 1;
        /*
         * Executing multiple queries.
         */
        if ($this->conn->multi_query($sql)) {
            while ($this->conn->more_results()) {
                /*
                 * If more results are available from the multi_query call we go
                 * to the next result and free its memory usage.
                 */
                $this->conn->next_result();

                $result = $this->conn->store_result();
                $subNo++;

                if ($result) {
                    $result->free();
                } elseif ($this->conn->errno !== 0) {
                    $this->handleError($sql, $subNo);
                }
            }
        } else {
            $this->handleError($sql, $subNo);
        }

        return $result;
    }

    /**
     * Drop all tables for given prefix.
     *
     * @param string $prefix
     * @throws Exception
     */
    public function dropTablesByPrefix($prefix)
    {
        $sql = 'SHOW TABLES LIKE "' . $prefix . '%"';
        $tables = $this->queryArray($sql);

        foreach ($tables as $table) {
            $tableName = array_values($table);
            $this->drop(reset($tableName));
        }
    }

    /**
     * @param string $sql
     * @param int|null $subQuery
     * @return void
     * @throws Exception
     */
    protected function handleError($sql, $subQuery = null)
    {
        switch (self::$errorHandling) {
            default:
            case self::OUTPUT_ERRORS:
                echo '<pre><h4 class="text-danger">MySQL Error:</h4>',
                    $this->conn->errno . ': ' . $this->conn->error,
                '<h5>Query', ($subQuery === null ? '' : ' (Error in SubQuery ' . $subQuery . ')'), '</h5>', $sql,
                '<h5>Debug backtrace</h5>', debug_backtrace_html(2), '</pre>';
                //flush to make error visible (a redirect could suppress it)
                flush();
                break;
            case self::THROW_EXCEPTIONS:
                $subQueryString = $subQuery !== null ? sprintf("[SubQuery %d]", $subQuery) : '';
                $errorMessage = sprintf("MySQL Error: %s\nin Query%s: %s", $this->conn->error, $subQueryString, $sql);
                throw new Exception($errorMessage, $this->conn->errno);
                break;
            case self::IGNORE_ERRORS:
                break;
        }
    }
}
