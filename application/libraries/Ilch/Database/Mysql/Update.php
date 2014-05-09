<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

class Update extends QueryBuilder
{
    /** @var  array */
    protected $values;

    /**
     * @param array|null $values values as [name => value]
     * @param string|null $table table without prefix
     * @param array|null $where conditions @see QueryBuilder::where()
     */
    public function __construct($values = null, $table = null, $where = null)
    {
        if (isset($values)) {
            $this->values($values);
        }
        if (isset($into)) {
            $this->into($into);
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

        $sql = 'UPDATE ' . $this->db->quote('[prefix]_'.$this->table) . ' SET ';
        $up = [];

        if (!empty($this->values)) {
            foreach ($this->values as $fieldName => $value) {
                if ($value === null) { //todo: really no null values to db??
                    continue;
                }

                $up[] = $this->db->quote($fieldName) . ' = ' . $this->db->escape($value);
            }
        }

        if (empty($up)) {
            throw new \RuntimeException('no valid values for update');
        }

        $sql .= implode(',', $up);

        if (isset($this->where)) {
            $sql .= ' WHERE ' . $this->where;
        }

        return $sql;
    }
}
