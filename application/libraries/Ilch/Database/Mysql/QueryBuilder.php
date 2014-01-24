<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class QueryBuilder
{
    /**
     * Injects the database adapter.
     *
     * @param Ilch\Database\Mysql $db
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    /**
     * Adds table to query builder.
     *
     * @param string $table
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function from($table)
    {
        $this->_table = $table;

        return $this;
    }

    /**
     * Adds where to query builder.
     *
     * @param array $where
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function where($where)
    {
        $this->_where = $where;

        return $this;
    }

    /**
     * Execute the query builder.
     *
     * @return mysqli_query
     */
    public function execute()
    {
        return $this->_db->query($this->generateSql());
    }

    /**
     * Create the where part for the given array.
     *
     * @param  array $where
     * @return string
     */
    protected function _getWhereSql($where)
    {
        $sql = '';

        foreach ($where as $key => $value) {
            $sql .= 'AND `' . $key . '` = "' . $this->_db->escape($value) . '" ';
        }

        return $sql;
    }
}
