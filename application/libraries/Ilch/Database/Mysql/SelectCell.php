<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class SelectCell extends QueryBuilder
{
    /**
     * @var string
     */
    protected $cell;

    /**
     * Adds cell to query builder.
     *
     * @param string $cell field name
     * @return \Ilch\Database\Mysql\QueryBuilder
     */
    public function cell($cell)
    {
        $this->cell = $cell;

        return $this;
    }

    /**
     * Execute the generated query
     *
     * @return string value of the field
     */
    public function execute()
    {
        return $this->db->queryCell($this->generateSql());
    }

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
