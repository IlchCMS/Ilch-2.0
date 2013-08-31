<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
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
		if($this->conn->select_db($db) === false)
		{
			throw new InvalidArgumentException('could not find database with name "'.$db.'"');
		}
	}

	/**
	 * Connects to database.
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
	 * Execute sql query.
	 *
	 * @param string $sql
	 * @return mysqli_result
	 */
	public function query($sql)
	{
		$sql = str_replace('[prefix]', $this->prefix, $sql);

		return mysqli_query($this->conn, $sql);
	}

	/**
	 * Select on cell from table.
	 *
	 * @param string $sql
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
	 * @param string $table
	 * @param array $where
	 * @return string|int
	 */
	public function selectCell($cell, $table, $where = null)
	{
		$sql = 'SELECT `' . $cell . '`
				FROM `' . $this->prefix . $table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		$sql .= ' LIMIT 1';

		return $this->queryCell($sql);
	}

	/**
	 * Select one row from table.
	 *
	 * @param string $sql
	 * @return array
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
	 * @param string $table
	 * @param array $where
	 * @return array
	 */
	public function selectRow($fields, $table, $where = null)
	{
		$sql = 'SELECT `' . $this->_getFieldsSql($fields) . '`
				FROM `' . $this->prefix . $table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		$sql .= ' LIMIT 1';

		return $this->queryRow($sql);
	}

	/**
	 * Select an array from db-table.
	 *
	 * @param string $sql
	 * @return array
	 */
	public function queryArray($sql)
	{
		$rows = array();
		$result = $this->query($sql);

		while($row = mysqli_fetch_assoc($result))
		{
			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * Select an array from db-table.
	 *
	 * @param array $fields
	 * @param string $table
	 * @param array $where
	 * @return array
	 */
	public function selectArray($fields, $table, $where = null)
	{
		$sql = 'SELECT `' . $this->_getFieldsSql($fields) . '`
				FROM `' . $this->prefix . $table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		return $this->queryArray($sql);
	}

	/**
	 * Update entries from the table.
	 *
	 * @param array $fields
	 * @param string $table
	 * @param array $where
	 */
	public function update($fields, $table, $where = null)
	{
		$sql = 'UPDATE `' . $this->prefix . $table . '` SET ';

		foreach($fields as $key => $value)
		{
			$up[] = '`' . $key . '` = "' . $value . '"';
		}

		$sql .= implode(',', $up);

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		$this->query($sql);
	}

	/**
	 * Insert entries to the table.
	 *
	 * @param array $fields
	 * @param string $table
	 */
	public function insert($fields, $table)
	{
		$sql = 'INSERT INTO `' . $this->prefix . $table . '` ( ';
		$sqlFields = array();
		$sqlValues = array();
		
		foreach($fields as $key => $value)
		{
			$sqlFields[] = '`' . $key . '`';
		}

		$sql .= implode(',', $sqlFields);
		$sql .= ') VALUES (';

		foreach($fields as $key => $value)
		{
			$sqlValues[] = '"' . $this->escape($value) . '"';
		}

		$sql .= implode(',', $sqlValues) . ')';
		$this->query($sql);
	}

	/**
	 * Deletes entries from the table.
	 *
	 * @param string $table
	 * @param array $where
	 */
	public function delete($table, $where = null)
	{
		$sql = 'DELETE FROM `' . $this->prefix . $table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		$this->query($sql);
	}

	/**
	 * Create the field part for the given array.
	 *
	 * @param array $fields
	 * @return string
	 */
	protected function _getFieldsSql($fields)
	{
		return implode('`,`', (array) $fields);
	}

	/**
	 * Create the where part for the given array.
	 *
	 * @param array $where
	 * @return string
	 */
	protected function _getWhereSql($where)
	{
		$sql = '';

		foreach ($where as $key => $value)
		{
			$sql .= 'AND `' . $key . '` = "' . $this->escape($value) . '" ';
		}

		return $sql;
	}

	/**
	 * Escape the given value for a sql query.
	 *
	 * @param string $value
	 * @return string
	 */
	public function escape($value)
	{
		return mysqli_real_escape_string($this->conn, $value);
	}
}