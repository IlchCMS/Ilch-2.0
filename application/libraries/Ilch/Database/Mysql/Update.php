<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use Ilch\Database\Mysql as DB;

class Update extends QueryBuilder
{
    /** @var  array */
    protected $values;

    /**
     * @param \Ilch\Database\Mysql $db
     * @param string|null $table table without prefix
     * @param array|null $values values as [name => value]
     * @param array|null $where conditions @see QueryBuilder::where()
     */
    public function __construct(DB $db, $table = null, $values = null, $where = null)
    {
        parent::__construct($db);

        if (isset($values)) {
            $this->values($values);
        }
        if (isset($table)) {
            $this->table($table);
        }
        if (isset($where)) {
            $this->where($where);
        }
    }

    /**
     * @param array $values
     * @return Update
     */
    public function values(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @param $table
     * @return Update
     */
    public function table($table)
    {
        $this->table = (string) $table;
        return $this;
    }

    /**
     * @todo remove after updating modules
     * @deprecated
     * @param array $values
     * @return Update
     */
    public function fields(array $values)
    {
        return $this->values($values);
    }

    /**
     * Execute the generated query
     *
     * @return integer number of changed rows
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
     * @throws \RuntimeException
     */
    public function generateSql()
    {
        if (!isset($this->table)) {
            throw new \RuntimeException('table must be set');
        }

        $sql = 'UPDATE ' . $this->db->quote('[prefix]_' . $this->table) . ' SET ';
        $up = [];

        if (!empty($this->values)) {
            foreach ($this->values as $fieldName => $value) {
                if ($value === null) { //todo: really no null values to db??
                    continue;
                }

                $up[] = $this->db->quote($fieldName) . ' = ' . $this->db->escape($value, true);
            }
        }

        if (empty($up)) {
            throw new \RuntimeException('no valid values for update');
        }

        $sql .= implode(',', $up);

        $sql .= $this->generateWhereSql();

        return $sql;
    }
}
