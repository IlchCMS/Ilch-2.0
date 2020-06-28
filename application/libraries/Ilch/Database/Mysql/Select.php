<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use \Ilch\Database\Mysql as DB;

class Select extends QueryBuilder
{
    /**
     * @var integer|null
     */
    protected $limit;

    /**
     * @var integer|null
     */
    protected $offset;

    /**
     * @var integer|null
     */
    protected $page;

    /**
     * @var array|null
     */
    protected $order;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var Expression\Join[]
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $groupByFields = [];

    /** @var bool */
    protected $useFoundRows = false;

    /**
     * Create Select Statement Query Builder
     *
     * @param \Ilch\Database\Mysql $db
     * @param array|string|null $fields
     * @param string|null $table table without prefix
     * @param array|null $where conditions @see QueryBuilder::where()
     * @param array|null $orderBy
     * @param array|int|null $limit
     */
    public function __construct(
        DB $db,
        $fields = null,
        $table = null,
        $where = null,
        array $orderBy = null,
        $limit = null
    ) {
        parent::__construct($db);

        if (isset($fields)) {
            $this->fields($fields);
        }
        if (isset($table)) {
            $this->from($table);
        }
        if (isset($where)) {
            $this->where($where);
        }
        if (isset($orderBy)) {
            $this->order($orderBy);
        }
        if (isset($limit)) {
            $this->limit($limit);
        }
    }

    /**
     * Adds table to query builder.
     *
     * @param string|array $table table without prefix (as array with alias as key)
     * @return \Ilch\Database\Mysql\Select
     */
    public function from($table): Select
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Adds fields to query builder.
     *
     * @param array|string $fields
     * @param boolean $replace [default: true]
     *
     * @return \Ilch\Database\Mysql\Select
     * @throws \InvalidArgumentException for invalid $fields parameter
     */
    public function fields($fields, $replace = true): Select
    {
        if ($fields === '*') {
            return $this;
        }

        $fields = $this->createFieldsArray($fields);

        if ($replace || empty($this->fields)) {
            $this->fields = $fields;
        } else {
            $this->fields = array_merge($this->fields. $fields);
        }

        return $this;
    }

    /**
     * Adds order to query builder.
     *
     * @param array $order [field => direction]
     * @return \Ilch\Database\Mysql\Select
     */
    public function order(array $order): Select
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Sets page for query builder (offset will be used first)
     * @param int $page
     * @return \Ilch\Database\Mysql\Select
     */
    public function page($page): Select
    {
        $this->page = (int) $page;
        return $this;
    }

    /**
     * Adds limit to query builder.
     *
     * @param array|integer $limit if array -> [offset, limit]
     * @return \Ilch\Database\Mysql\Select
     */
    public function limit($limit): Select
    {
        if (is_array($limit)) {
            $limitCount = count($limit);
            if ($limitCount === 1) {
                $this->limit = (int) $limit[0];
            } elseif ($limitCount > 1) {
                $this->offset = (int) $limit[0];
                $this->limit = (int) $limit[1];
            }
        } else {
            $this->limit = (int) $limit;
        }

        return $this;
    }

    /**
     * Sets offset for query builder
     *
     * @param int $offset
     * @return \Ilch\Database\Mysql\Select
     */
    public function offset($offset): Select
    {
        $this->offset = (int) $offset;
        return $this;
    }

    /**
     * Adds a join to the query (builder)
     *
     * @param Expression\Join $join
     * @return \Ilch\Database\Mysql\Select
     */
    public function addJoin(Expression\Join $join): Select
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * @param string|array $table
     * @param string $type
     * @return Expression\Join
     */
    public function createJoin($table, $type = Expression\Join::INNER): Expression\Join
    {
        return new Expression\Join($table, $type);
    }

    /**
     * Create and add a join to the query (builder)
     *
     * @param string|array $table
     * @param string|array $conditions
     * @param string $type
     * @param array $fields
     * @return \Ilch\Database\Mysql\Select
     */
    public function join($table, $conditions, $type = Expression\Join::INNER, array $fields = null): Select
    {
        $join = $this->createJoin($table, $type);
        if (is_string($conditions)) {
            $conditions = [$conditions];
        }
        if (isset($fields)) {
            $join->setFields($fields);
        }
        $join->setConditions($conditions);
        return $this->addJoin($join);
    }

    /**
     * Add GROUP BY to the query (builder)
     *
     * @param array $fields ['field' => 'DESC|ASC', 'field2']
     * @param boolean $replace
     *
     * @return \Ilch\Database\Mysql\Select
     */
    public function group(array $fields, $replace = true): Select
    {
        if ($replace) {
            $this->groupByFields = $fields;
        } else {
            $this->groupByFields = array_merge($this->groupByFields, $fields);
        }

        return $this;
    }

    /**
     * @param $useFoundRows
     * @return \Ilch\Database\Mysql\Select
     */
    public function useFoundRows($useFoundRows = true): Select
    {
        $this->useFoundRows = (bool) $useFoundRows;
        return $this;
    }

    /**
     * Execute the generated query
     *
     * @return \Ilch\Database\Mysql\Result
     */
    public function execute()
    {
        return new Result($this->db->query($this->generateSql()), $this->db);
    }

    /**
     * Generate the SQL executed by execute()
     *
     * @return string
     * @throws \RuntimeException if sql could not be generated
     * @throws \InvalidArgumentException if invalid parts were configured
     */
    public function generateSql()
    {
        if (empty($this->table)) {
            throw new \RuntimeException('table must be set');
        }

        $fields = $this->fields;

        $sql = 'SELECT ';

        if ($this->useFoundRows) {
            $sql .= 'SQL_CALC_FOUND_ROWS ';
        }

        // generate sql for joins
        $joinSql = [];
        foreach ($this->joins as $join) {
            $temp = ' ' . $join->getType() . ' JOIN ' . $this->getTableSql($join->getTable());
            $joinCondition = $join->getConditions();
            if (!empty($joinCondition)) {
                $temp .= ' ON ' . $this->createCompositeExpression($joinCondition, $join->getConditionsType());
            }
            $joinFields = $join->getFields();
            if (!empty($joinFields)) {
                $fields = array_merge($fields, $this->createFieldsArray($joinFields));
            }
            $joinSql[] = $temp;
        }

        // start query
        $sql .= $this->getFieldsSql($fields)
            . ' FROM ' . $this->getTableSql($this->table);

        $sql .= implode(' ', $joinSql);

        $sql .= $this->generateWhereSql();
        $sql .= $this->generateGroupBySql();

        // add ORDER BY to sql
        if (!empty($this->order)) {
            $sql .= ' ORDER BY ';
            $fields = [];
            foreach ($this->order as $column => $direction) {
                $fields[] = $this->db->quote($column) . ' ' . $direction;
            }
            $sql .= implode(',', $fields);
        }

        // add LIMIT to sql
        if (isset($this->offset) && !isset($this->limit)) {
            $limit = 99999999;
        } else {
            $limit = $this->limit;
        }

        if (isset($limit)) {
            $limitParts = [$limit];

            if (isset($this->offset)) {
                array_unshift($limitParts, $this->offset);
            } elseif (isset($this->page)) {
                array_unshift($limitParts, max($this->page - 1, 0) * $limit);
            }
            $sql .= ' LIMIT ' . implode(', ', $limitParts);
        }

        return $sql;
    }

    /**
     * Create the field part for the given array.
     *
     * @param  array $fields
     * @return string
     */
    protected function getFieldsSql($fields): string
    {
        if (empty($fields)) {
            return '*';
        }

        $sqlFields = [];

        foreach ($fields as $key => $value) {
            if (!$value instanceof Expression\Expression) {
                $value = $this->db->quote($value);
            }
            //non int key -> alias
            if (!is_int($key)) {
                $value .= ' AS ' . $this->db->quote($key, true);
            }
            $sqlFields[] = $value;
        }

        return implode(',', $sqlFields);
    }

    /**
     * Create sql for a table (with optional alias)
     *
     * @param string|array $table
     *
     * @return string
     */
    protected function getTableSql($table): string
    {
        if (is_array($table)) {
            $tableName = reset($table);
            $tableAlias = key($table);
        } else {
            $tableName = $table;
        }

        $sql = $this->db->quote('[prefix]_' . $tableName);
        if (isset($tableAlias) && is_string($tableAlias)) {
            $sql .= ' AS ' . $this->db->quote($tableAlias, true);
        }

        return $sql;
    }

    /**
     * Create an array of fields
     *
     * @param string|array $fields
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function createFieldsArray($fields)
    {
        if (is_string($fields)) {
            //function like COUNT()
            if (strpos($fields, '(') !== false) {
                $fields = [new Expression\Expression($fields)];
                //single field
            } elseif (strpos($fields, ' ') !== false) {
                // Added to support for example "DISTINCT c1, c2, c3"
                $fields = [new Expression\Expression($fields)];
            } else {
                $fields = [$fields];
            }
        } elseif ($fields instanceof Expression\Expression) {
            $fields = [$fields];
        }
        if (!is_array($fields)) {
            throw new \InvalidArgumentException('array or single field (or function) string expected');
        }

        return $fields;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function generateGroupBySql(): string
    {
        $sql = '';
        // add GROUP BY to sql
        if (!empty($this->groupByFields)) {
            $sql .= ' GROUP BY ';
            $fields = [];
            foreach ($this->groupByFields as $key => $value) {
                if (is_int($key)) {
                    $fields[] = $this->db->quote($value);
                } else {
                    if (!in_array($value, ['ASC', 'DESC'])) {
                        throw new \InvalidArgumentException('Invalid GROUP BY option: ' . $value);
                    }
                    $fields[] = $this->db->quote($key) . ' ' . $value;
                }
            }
            $sql .= implode(',', $fields);
        }
        return $sql;
    }
}
