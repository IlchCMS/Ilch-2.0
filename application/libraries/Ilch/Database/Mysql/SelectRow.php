<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class SelectRow extends QueryBuilder
{
    /**
     * Execute the generated query
     *
     * @return array the fetched row
     */
    public function execute()
    {
        return $this->db->queryRow($this->generateSql());
    }

    /**
     * Gets query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'SELECT '. $this->getFieldsSql($this->fields) .'
                FROM `[prefix]_'. $this->table . '` ';

        if ($this->where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($this->where);
        }

        $sql .= ' LIMIT 1';

        return $sql;
    }
}
