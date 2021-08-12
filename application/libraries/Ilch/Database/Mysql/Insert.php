<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use Ilch\Database\Mysql as DB;

class Insert extends QueryBuilder
{
    /** @var  array */
    protected $values;
    /** @var  array */
    protected $columns;

    /**
     * @param \Ilch\Database\Mysql $db
     * @param string|null $into table without prefix
     * @param array|null $values values as [name => value]
     */
    public function __construct(DB $db, $into = null, $values = null)
    {
        parent::__construct($db);

        if (isset($values)) {
            $this->values($values);
        }
        if (isset($into)) {
            $this->into($into);
        }
    }

    /**
     * @param array $values values as [name => value]
     * @return Insert
     */
    public function values(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @param string $table table without prefix
     * @return Insert
     */
    public function into($table)
    {
        $this->table = (string) $table;
        return $this;
    }

    /**
     * @param array $columns as ['firstColumn', 'secondColumn']
     * @return Insert
     */
    public function columns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Execute the query builder.
     *
     * @return integer generated autoincrement primary key
     */
    public function execute()
    {
        $this->db->query($this->generateSql());
        return $this->db->getLastInsertId();
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

        $sql = 'INSERT INTO ' . $this->db->quote('[prefix]_'.$this->table). ' (';
        $sqlValues = [];
        $sqlFields = $sqlValues;

        if (!empty($this->values)) {
            if (empty($this->columns)) {
                foreach ($this->values as $key => $value) {
                    if ($value === null) {
                        continue;
                    }

                    $sqlFields[] = $this->db->quote($key);
                    $sqlValues[] = $this->db->escape($value, true);
                }
            } else {
                $escapeFunc = function($value) { return $this->db->escape($value, true); };
                $countOfColumns = \count($this->columns);
                $countOfRows = \count($this->values);

                foreach($this->columns as $column) {
                    $sqlFields[] = $this->db->quote($column);
                }

                foreach($this->values as $value) {
                    if (!\is_array($value)) {
                        throw new \RuntimeException('no valid values for insert');
                    }

                    if ($countOfColumns !== \count($value)) {
                        throw new \RuntimeException('count of values does not fit the count of columns');
                    }

                    if ($countOfRows > 1000) {
                        throw new \RuntimeException('not more than 1000 rows allowed');
                    }

                    $sqlValues[] = '(' . implode(',', array_map($escapeFunc, $value)) . ')';
                }
            }
        }

        if (empty($sqlFields)) {
            throw new \RuntimeException('no valid values for insert');
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES ';

        if (empty($this->columns)) {
            $sql .= '(' . implode(',', $sqlValues) . ')';
        } else {
            $sql .= implode(',', $sqlValues);
        }

        return $sql;
    }
}
