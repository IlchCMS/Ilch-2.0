<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class Update extends QueryBuilder
{
    /**
     * Execute the generated query
     *
     * @return integer number of changed rows
     */
    public function execute()
    {
        $res = $this->db->query($this->generateSql());
        return $res->num_rows;
    }

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
