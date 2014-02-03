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
     * @var string
     */
    protected $_type = '';

    /**
     * @var integer|null
     */
    protected $_limit;
    
    /**
     * @var array|null
     */
    protected $_where;

    /**
     * @var array|null
     */
    protected $_fields;

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
     * Adds fields to query builder.
     *
     * @param array $fields
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function fields($fields)
    {
        $this->_fields = $fields;

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
     * Adds order to query builder.
     *
     * @param array $order
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function order($order)
    {
        $this->_order = $order;

        return $this;
    }
    
    /**
     * Adds limit to query builder.
     *
     * @param integer $limit
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function limit($limit)
    {
        $this->_limit = $limit;

        return $this;
    }
    
    /**
     * Adds cell to query builder.
     *
     * @param string $cell
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function cell($cell)
    {
        $this->_cell = $cell;

        return $this;
    }

    /**
     * Execute the query builder.
     *
     * @return mixed
     */
    public function execute()
    {
        if ($this->_type == 'insert') {
            $this->_db->query($this->generateSql());
            return $this->_db->getLink()->insert_id;
        } elseif ($this->_type == 'selectCell') {
            return $this->_db->queryCell($this->generateSql());
        } elseif ($this->_type == 'selectRow') {
            return $this->_db->queryRow($this->generateSql());
        } elseif ($this->_type == 'selectArray') {
            return $this->_db->queryArray($this->generateSql());
        } else {
            return $this->_db->query($this->generateSql());        
        }
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
    
    /**
     * Create the field part for the given array.
     *
     * @param  array  $fields
     * @return string
     */
    protected function _getFieldsSql($fields)
    {
        /*
         * @todo check on sign "(" on fields.
         */
        if ($fields === '*' || $fields === 'COUNT(*)') {
            return $fields;
        }

        return '`'.implode('`,`', (array) $fields).'`';
    }
}
