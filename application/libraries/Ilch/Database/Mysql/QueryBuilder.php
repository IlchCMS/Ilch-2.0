<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

abstract class QueryBuilder
{
    /**
     * @var \Ilch\Database\Mysql
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array|null
     */
    protected $limit;
    
    /**
     * @var array|null
     */
    protected $where;

    /**
     * @var array|null
     */
    protected $order;

    /**
     * @var array|null
     */
    protected $fields;

    /**
     * Injects the database adapter.
     *
     * @param \Ilch\Database\Mysql $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Adds table to query builder.
     *
     * @param string $table
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function from($table)
    {
        $this->table = $table;

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
        $this->fields = $fields;

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
        $this->where = $where;

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
        $this->order = $order;

        return $this;
    }
    
    /**
     * Adds limit to query builder.
     *
     * @param array $limit
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
    
    /**
     * Execute the generated query
     *
     * @return mixed
     */
    abstract public function execute();

    /**
     * Generate the SQL executed by execute()
     *
     * @return string
     */
    abstract public function generateSql();

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
            $sql .= 'AND `' . $key . '` = "' . $this->db->escape($value) . '" ';
        }

        return $sql;
    }
    
    /**
     * Create the field part for the given array.
     *
     * @param  array  $fields
     * @return string
     */
    protected function getFieldsSql($fields)
    {
        if (!is_array($fields) && ($fields === '*' || strpos($fields, '(') !== false)) {
            return $fields;
        }

        return '`'.implode('`,`', (array) $fields).'`';
    }
}
