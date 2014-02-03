<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class SelectRow extends QueryBuilder
{
    /**
     * @var string
     */
    protected $_type = 'selectRow';

    /**
     * Gets query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'SELECT '. $this->_getFieldsSql($this->_fields) .'
                FROM `[prefix]_'. $this->_table . '` ';

        if ($this->_where != null) {
            $sql .= 'WHERE 1 ' . $this->_getWhereSql($this->_where);
        }

        $sql .= ' LIMIT 1';

        return $sql;
    }
}
