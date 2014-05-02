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
    protected $type = 'update';

    /**
     * Gets delete query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'UPDATE `[prefix]_'.$this->table . '` SET ';
        $up = array();

        foreach ($this->fields as $key => $value) {
            if ($value === null) {
                continue;
            }

            $up[] = '`' . $key . '` = "' . $this->db->escape($value) . '"';
        }

        $sql .= implode(',', $up);

        if ($this->where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($this->where);
        }

        return $sql;
    }
}
