<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class Delete extends QueryBuilder
{
    /**
     * @param string|null $from table without prefix
     * @param array|null $where conditions @see QueryBuilder::where()
     */
    public function __construct($from = null, $where = null)
    {
        if (isset($from)) {
            $this->from($from);
        }
        if (isset($where)) {
            $this->where($where);
        }
    }

    /**
     * @param string $table table without prefix
     * @return Delete
     */
    public function from($table)
    {
        $this->table = (string) $table;
        return $this;
    }

    /**
     * Execute the query builder.
     *
     * @return integer number of deleted rows
     */
    public function execute()
    {
        $this->db->query($this->generateSql());
        return $this->db->getAffectedRows();
    }

    /**
     * Gets delete query builder sql.
     *
     * @return string
     */
    public function generateSql()
    {
        $sql = 'DELETE  FROM ' . $this->db->quote('[prefix]_' . $this->table);

        if (isset($this->where)) {
            $sql .= ' WHERE ' . $this->where;
        }

        return $sql;
    }
}
