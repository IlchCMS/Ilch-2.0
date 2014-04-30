<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database;

class Mysql
{
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
     * @return bool success
     */
    public function connect($host, $name, $password, $port = null)
    {
        $this->conn = @new \mysqli($host, $name, $password, $port);

        if ($this->conn->connect_error) {
            return false;
        }

        @$this->conn->set_charset('utf8');

        return true;
    }

    /**
     * Execute sql query.
     *
     * @param  string $sql
     * @return \mysqli_result
     */
    public function query($sql)
    {
        $mysqliResult = mysqli_query($this->conn, $this->getSqlWithPrefix($sql));

        if (!$mysqliResult) {
            echo '<pre><h4 class="text-danger">MySQL Error:</h4>'
                . $this->conn->errno . ': ' . $this->conn->error
                . '<h5>Query</h5>' . $this->getSqlWithPrefix($sql)
                . '<h5>Debug backtrace</h5>' . debug_backtrace_html() . '</pre>';
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
     * Select on cell from table.
     *
     * @param  string $sql
     * @return string|int
     */
    public function queryCell($sql)
    {
        $row = mysqli_fetch_row($this->query($sql));

        return $row[0];
    }

    /**
     * Select one cell from table.
     *
     * @param string $cell
     * @return \Ilch\Database\Mysql\SelectCell
     */
    public function selectCell($cell)
    {
        $select = new Mysql\SelectCell($this);
        $select->cell($cell);

        return $select;
    }

    /**
     * Select one row from table.
     *
     * @param  string $sql
     * @return array|null
     */
    public function queryRow($sql)
    {
        $row = mysqli_fetch_assoc($this->query($sql));

        return $row;
    }

    /**
     * Select one row from table.
     *
     * @param array $fields
     * @return \Ilch\Database\Mysql\SelectRow
     */
    public function selectRow($fields)
    {
        $select = new Mysql\SelectRow($this);
        $select->fields($fields);

        return $select;
    }

    /**
     * Select an array from db-table.
     *
     * @param  string $sql
     * @return array
     */
    public function queryArray($sql)
    {
        $rows = array();
        $result = $this->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Select an array from db-table.
     *
     * @param array $fields
     * @return \Ilch\Database\Mysql\SelectArray
     */
    public function selectArray($fields)
    {
        $select = new Mysql\SelectArray($this);
        $select->fields($fields);

        return $select;
    }

    /**
     * Select a list from db-table.
     *
     * @param  string $sql
     * @return array
     */
    public function queryList($sql)
    {
        $list = array();
        $result = $this->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = reset($row);
        }

        return $list;
    }

    /**
     * Select a list from a db-table.
     *
     * @param  array $fields
     * @param  string $table
     * @param  array $where
     * @return array
     */
    public function selectList($fields, $table, array $where = null)
    {
        $sql = 'SELECT ' . $this->getFieldsSql($fields) . '
                FROM `[prefix]_' . $table . '` ';

        if ($where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($where);
        }

        return $this->queryList($sql);
    }

    /**
     * Update entries from the table.
     *
     * @param string $table
     * @return \Ilch\Database\Mysql\Update
     */
    public function update($table)
    {
        $updateObj = new Mysql\Update($this);
        $updateObj->from($table);

        return $updateObj;
    }

    /**
     * Insert entries into the table.
     * @param string $table
     * @return \Ilch\Database\Mysql\Update
     */
    public function insert($table)
    {
        $insertObj = new Mysql\Insert($this);
        $insertObj->from($table);

        return $insertObj;
    }

    /**
     * Deletes entries from the table.
     * @param string $table
     * @return \Ilch\Database\Mysql\Delete
     */
    public function delete($table)
    {
        $deleteObj = new Mysql\Delete($this);
        $deleteObj->from($table);

        return $deleteObj;
    }

    /**
     * Drops the table from database.
     *
     * @param string $table
     * @return \mysqli_result
     */
    public function drop($table)
    {
        $sql = 'DROP TABLE `' . $table . '`';

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
     * @param string $field
     * @return string
     */
    public function quote($field)
    {
        return '`' . $field . '`';
    }

    /**
     * Quotes an array of field names
     * @param array $fields
     * @return array
     */
    public function quoteArray(array $fields)
    {
        foreach ($fields as &$field) {
            $field = $this->quote($field);
        }
        return $fields;
    }

    /**
     * Escape the given value for a sql query.
     *
     * @param  string $value
     * @return string
     */
    public function escape($value)
    {
        return mysqli_real_escape_string($this->conn, $value);
    }

    /**
     * Escapes every value in a array for a sql query
     *
     * @param array $array
     * @return array
     */
    public function escapeArray(array $array)
    {
        foreach ($array as &$value) {
            $value = $this->escape($value);
        }
        return $array;
    }

    /**
     * Executes multiple queries given in one string within a single request.
     *
     * @param  string $sql The string with the multiple queries.
     * @return boolean false if the first statement failed. Otherwise true.
     */
    public function queryMulti($sql)
    {
        $result = false;
        $sql = $this->getSqlWithPrefix($sql);

        /*
         * Executing the multiple queries.
         */
        if ($this->conn->multi_query($sql)) {
            while ($this->conn->more_results()) {
                /*
                 * If more results are available from the multi_query call we go
                 * to the next result and free its memory usage.
                 */
                $this->conn->next_result();

                $result = $this->conn->store_result();

                if ($result) {
                    $result->free();
                }
            }
        }

        return $result;
    }

    /**
     * Drop all tables for given prefix.
     *
     * @param string $prefix
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
}
