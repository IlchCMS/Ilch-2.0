<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class Insert extends QueryBuilder
{
    /**
     * Execute the query builder.
     *
     * @return integer generated autoincrement primary key
     */
    public function execute()
    {
        $this->db->query($this->generateSql());
        return $this->db->getLink()->insert_id;
    }

    /**
     * Gets delete query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'INSERT INTO `[prefix]_'.$this->table.'` ( ';
        $sqlFields = array();
        $sqlValues = array();

        foreach ($this->fields as $key => $value) {
            if ($value === null) {
                continue;
            }

            $sqlFields[] = '`' . $key . '`';
            $sqlValues[] = '"' . $this->db->escape($value) . '"';
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES (';

        $sql .= implode(',', $sqlValues) . ')';

        return $sql;
    }
}
