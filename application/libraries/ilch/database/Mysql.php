<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Database_Mysql
{
    /**
     * @var string|null
     */
    protected $prefix = null;

    /**
     * @var Mysqli|null
     */
    protected $conn = null;

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
     * Get the mysqli object.
     *
     * @return Mysqli
     */
    public function getLink()
    {
        return $this->conn;
    }

    /**
     * Set the database.
     *
     * @param string $db
     */
    public function setDatabase($db)
    {
        $this->conn->select_db($db);
    }

    /**
     * Connect to database.
     *
     * @param string $host
     * @param string $name
     * @param string $password
     */
    public function connect($host, $name, $password)
    {
        $this->conn = @new mysqli($host, $name, $password);
        $this->conn->set_charset('utf8');
    }

    /**
     * @param string $sql
     * @return mysqli_result
     */
    public function query($sql)
    {
        $sql = str_replace('[prefix]', $this->prefix, $sql);

        return mysqli_query($this->conn, $sql);
    }

    /**
     * @param string $sql
     * @return string|int
     */
    public function queryCell($sql)
    {
        $row = mysqli_fetch_row($this->query($sql));
        return $row[0];
    }

    /**
     * @param string $cell
     * @param string $table
     * @param array $where
     * @return string|int
     */
    public function selectCell($cell, $table, $where = null)
    {
        $sql = 'SELECT `' . $cell . '`
                FROM `' . $this->prefix . $table . '` ';

        if ($where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($where);
        }

        $sql .= ' LIMIT 1';

        return $this->queryCell($sql);
    }

    /**
     * @param string $sql
     * @return array
     */
    public function queryRow($sql)
    {
        $row = mysqli_fetch_assoc($this->query($sql));
        return $row;
    }

    /**
     * @param array $fields
     * @param string $table
     * @param array $where
     * @return array
     */
    public function selectRow($fields, $table, $where = null)
    {
        $sql = 'SELECT `' . $this->getFieldsSql($fields) . '`
                FROM `' . $this->prefix . $table . '` ';

        if ($where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($where);
        }

        $sql .= ' LIMIT 1';

        return $this->queryRow($sql);
    }

    /**
     * @param string $sql
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
     * @param array $fields
     * @param string $table
     * @param array $where
     * @return array
     */
    public function selectArray($fields, $table, $where = null)
    {
        $sql = 'SELECT `' . $this->getFieldsSql($fields) . '`
                FROM `' . $this->prefix . $table . '` ';

        if ($where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($where);
        }

        return $this->queryArray($sql);
    }

    /**
     * @param array $fields
     * @param string $table
     * @param array $where
     */
    public function update($fields, $table, $where = null)
    {
        $sql = 'UPDATE `' . $this->prefix . $table . '` SET ';

        foreach ($fields as $key => $value) {
            $up[] = '`' . $key . '` = "' . $value . '"';
        }

        $sql .= implode(',', $up);

        if ($where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($where);
        }

        $this->query($sql);
    }

    /**
     * @param array $fields
     * @param string $table
     */
    public function insert($fields, $table)
    {
        $sql = 'INSERT INTO `' . $this->prefix . $table . '` ( ';
        $sqlFields = array();
        $sqlValues = array();
        
        foreach ($fields as $key => $value) {
            $sqlFields[] = '`' . $key . '`';
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES (';

        foreach ($fields as $key => $value) {
            $sqlValues[] = '"' . $this->escape($value) . '"';
        }

        $sql .= implode(',', $sqlValues) . ')';
        $this->query($sql);
    }

    /**
     * @param string $table
     * @param array $where
     */
    public function delete($table, $where = null)
    {
        $sql = 'DELETE FROM `' . $this->prefix . $table . '` ';

        if ($where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($where);
        }

        $this->query($sql);
    }

    /**
     * @param array $fields
     * @return string
     */
    protected function getFieldsSql($fields)
    {
        return implode('`,`', (array) $fields);
    }

    /**
     * @param array $where
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
     * @param string $value
     * @return string
     */
    public function escape($value)
    {
        return mysqli_real_escape_string($this->conn, $value);
    }
}