<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch Pluto
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
		if($this->conn->connect_error)
		{
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
	 */
	public function connect($host, $name, $password)
	{
		$this->conn = @new mysqli($host, $name, $password);

		if($this->conn->connect_error)
		{
			return false;
		}

		@$this->conn->set_charset('utf8');

		return true;
	}

	/**
	 * Execute sql query.
	 *
	 * @param string $sql
	 * @return mysqli_result
	 */
	public function query($sql)
	{
		$sql = str_replace('[prefix]_', $this->prefix, $sql);

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
				FROM `[prefix]_'.$table . '` ';

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
		$sql = 'SELECT '. $this->_getFieldsSql($fields) .'
				FROM `[prefix]_'. $table . '` ';

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
		$sql = 'SELECT '. $this->_getFieldsSql($fields).'
				FROM `[prefix]_'.$table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		return $this->queryArray($sql);
	}

	/**
	 * Select a list from db-table.
	 *
	 * @param string $sql
	 * @return array
	 */
	public function queryList($sql)
	{
		$list = array();
		$result = $this->query($sql);

		while($row = mysqli_fetch_assoc($result))
		{
			$list[] = reset($row);
		}

		return $list;
	}

	/**
	 * Select a list from a db-table.
	 *
	 * @param array $fields
	 * @param string $table
	 * @param array $where
	 * @return array
	 */
	public function selectList($fields, $table, $where = null)
	{
		$sql = 'SELECT '. $this->_getFieldsSql($fields).'
				FROM `[prefix]_'.$table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		return $this->queryList($sql);
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
		$sql = 'UPDATE `[prefix]_'.$table . '` SET ';

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
	 * @return integer
	 */
	public function insert($fields, $table)
	{
		$sql = 'INSERT INTO `[prefix]_'.$table.'` ( ';
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

		return $this->conn->insert_id;
	}

	/**
	 * Deletes entries from the table.
	 *
	 * @param string $table
	 * @param array $where
	 */
	public function delete($table, $where = null)
	{
		$sql = 'DELETE FROM `[prefix]_'.$table . '` ';

		if($where != null)
		{
			$sql .= 'WHERE 1 ' . $this->_getWhereSql($where);
		}

		return $this->query($sql);
	}

	/**
	 * Drops the table from database.
	 *
	 * @param string $table
	 */
	public function drop($table)
	{
		$sql = 'DROP TABLE `'.$table . '`';

		return $this->query($sql);
	}

	/**
	 * Create the field part for the given array.
	 *
	 * @param array $fields
	 * @return string
	 */
	protected function _getFieldsSql($fields)
	{
		if($fields === '*')
		{
			return $fields;
		}

		return '`'.implode('`,`', (array) $fields).'`';
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

	/**
	 * Executes multiple queries given in one string within a single request.
	 *
	 * @param  string $sql The string with the multiple queries.
	 * @return boolean false if the first statement failed. Otherwise true.
	 */
	public function queryMulti($sql)
	{
		$sql = str_replace('[prefix]_', $this->prefix, $sql);

		/*
		 * Executing the multiple queries.
		 */
		if($this->conn->multi_query($sql))
		{
		    while($this->conn->more_results())
		    {
		    	/*
		    	 * If more results are available from the multi_query call we go
		    	 * to the next result and free its memory usage.
		    	 */
		    	$this->conn->next_result();

				$result = $this->conn->store_result();

		        if($result)
		        {
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
		$sql = 'SHOW TABLES LIKE "'.$prefix.'%"';
		$tables = $this->queryArray($sql);

		foreach($tables as $table)
		{
			$tableName = array_values($table);
			$this->drop(reset($tableName));
		}
	}
}