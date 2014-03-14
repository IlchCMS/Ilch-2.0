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
            $sql .= ' LIMIT ' . (int)$this->_limit;
        }

        return $sql;
    }
}
