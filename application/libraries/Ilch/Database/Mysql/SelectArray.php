<?php
/**
 * @copyright Ilch 2.0 
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class SelectArray extends QueryBuilder
{
    /**
     * @var string
     */
    protected $_type = 'selectArray';

    /**
     * Gets query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'SELECT '. $this->_getFieldsSql($this->_fields).'
                FROM `[prefix]_'.$this->_table . '` ';

        if ($this->_where != null) {
            $sql .= 'WHERE 1 ' . $this->_getWhereSql($this->_where);
        }

        if (!empty($this->_order)) {
            $sql .= ' ORDER BY';

            foreach ($this->_order as $column => $direction) {
                $sql .= ' `'. $column.'` '.$direction;
            }
        }

        if ($this->_limit !== null) {
            $sql .= ' LIMIT ' . (int)$this->_limit[0];

            if (!empty($this->_limit[1])) {
                $sql .= ', '.(int)$this->_limit[1];
            }
        }

        return $sql;
    }
    
    /**
     * Gets query builder count sql.
     *
     * @return string
     */
    public function generateCountSql()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_'.$this->_table . '` ';

        if ($this->_where != null) {
            $sql .= 'WHERE 1 ' . $this->_getWhereSql($this->_where);
        }

        if (!empty($this->_order)) {
            $sql .= ' ORDER BY';

            foreach ($this->_order as $column => $direction) {
                $sql .= ' `'. $column.'` '.$direction;
            }
        }

        return $sql;
    }
}
