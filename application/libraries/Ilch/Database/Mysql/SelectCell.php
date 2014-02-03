<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class SelectCell extends QueryBuilder
{
    /**
     * @var string
     */
    protected $_type = 'selectCell';

    /**
     * Gets select cell query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'SELECT ' . $this->_getFieldsSql($this->_cell) . '
                FROM `[prefix]_'.$this->_table . '` ';

        if ($this->_where != null) {
            $sql .= 'WHERE 1 ' . $this->_getWhereSql($this->_where);
        }

        $sql .= ' LIMIT 1';

        return $sql;
    }
}
