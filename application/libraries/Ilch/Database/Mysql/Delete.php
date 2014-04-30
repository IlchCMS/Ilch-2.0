<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class Delete extends QueryBuilder
{
    /**
     * Execute the query builder.
     *
     * @return \mysqli_result
     */
    public function execute()
    {
        $res = $this->db->query($this->generateSql());
    }

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
