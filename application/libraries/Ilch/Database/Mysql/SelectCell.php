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
    protected $type = 'selectCell';

    /**
     * Gets select cell query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'SELECT ' . $this->getFieldsSql($this->cell) . '
                FROM `[prefix]_'.$this->table . '` ';

        if ($this->where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($this->where);
        }

        $sql .= ' LIMIT 1';

        return $sql;
    }
}
