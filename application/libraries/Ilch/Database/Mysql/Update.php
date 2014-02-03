<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class Update extends QueryBuilder
{
    /**
     * @var string
     */
    protected $_type = 'update';

    /**
     * Gets delete query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'UPDATE `[prefix]_'.$this->_table . '` SET ';
        $up = array();

        foreach ($this->_fields as $key => $value) {
            if ($value === null) {
                continue;
            }

            $up[] = '`' . $key . '` = "' . $this->_db->escape($value) . '"';
        }

        $sql .= implode(',', $up);

        if ($this->_where != null) {
            $sql .= 'WHERE 1 ' . $this->_getWhereSql($this->_where);
        }

        return $sql;
    }
}
