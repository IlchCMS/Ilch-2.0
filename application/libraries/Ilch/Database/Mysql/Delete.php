<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;
defined('ACCESS') or die('no direct access');

class Delete extends QueryBuilder
{
    /**
     * @var string
     */
    protected $type = 'delete';

    /**
     * Gets delete query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'DELETE  FROM `[prefix]_'.$this->table.'`';

        if ($this->where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($this->where);
        }

        return $sql;
    }
}
