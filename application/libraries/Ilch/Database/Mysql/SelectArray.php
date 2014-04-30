<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class SelectArray extends QueryBuilder
{
    /**
     * Execute the generated query
     *
     * @return array
     */
    public function execute()
    {
        return $this->db->queryArray($this->generateSql());
    }

    /**
     * Gets query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'SELECT '. $this->getFieldsSql($this->fields).'
                FROM `[prefix]_'.$this->table . '` ';

        if ($this->where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($this->where);
        }

        if (!empty($this->order)) {
            $sql .= ' ORDER BY';

            foreach ($this->order as $column => $direction) {
                $sql .= ' `'. $column.'` '.$direction;
            }
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . (int)$this->limit[0];

            if (!empty($this->limit[1])) {
                $sql .= ', '.(int)$this->limit[1];
            }
        }

        return $sql;
    }
    
    /**
     * Gets query builder count sql.
     *
     * @return string
     */
    public function generateCountSql()
    {
        $sql = 'SELECT COUNT(*)
                FROM `[prefix]_'.$this->table . '` ';

        if ($this->where != null) {
            $sql .= 'WHERE 1 ' . $this->getWhereSql($this->where);
        }

        if (!empty($this->order)) {
            $sql .= ' ORDER BY';

            foreach ($this->order as $column => $direction) {
                $sql .= ' `'. $column.'` '.$direction;
            }
        }

        return $sql;
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->db->queryCell($this->generateCountSql());
    }
}
