<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use Ilch\Database\Mysql as DB;

class Select extends QueryBuilder
{
    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var int|null
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
     * @param DB $db
     * @param array|string|null $fields
     * @param string|null $table table without prefix
     * @param array|null $where conditions @see QueryBuilder::where()
     * @param array|null $orderBy
     * @param array|int|null $limit
     */
    public function __construct(
        DB $db,
        $fields = null,
        ?string $table = null,
        ?array $where = null,
        ?array $orderBy = null,
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
     * @return Select
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
     * @param bool $replace [default: true]
     *
     * @return Select
     * @throws \InvalidArgumentException for invalid $fields parameter
     */
    public function fields($fields, bool $replace = true): Select
    {
        if ($fields === '*') {
            return $this;
        }

        $fields = $this->createFieldsArray($fields);

        if ($replace || empty($this->fields)) {
            $this->fields = $fields;
        } else {
            $this->fields = array_merge($this->fields . $fields);
        }

        return $this;
    }

    /**
     * Adds order to query builder.
     *
     * @param array $order [field => direction]
     * @return Select
     */
    public function order(array $order): Select
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Sets page for query builder (offset will be used first)
     * @param int $page
     * @return Select
     */
    public function page(int $page): Select
    {
        $this->page = (int) $page;
        return $this;
    }

    /**
     * Adds limit to query builder.
     *
     * @param array|int $limit if array -> [offset, limit]
     * @return Select
     */
    public function limit($limit): Select
    {
        if (\is_array($limit)) {
            $limitCount = \count($limit);
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
     * @return Select
     */
    public function offset(int $offset): Select
    {
        $this->offset = (int) $offset;
        return $this;
    }

    /**
     * Adds a join to the query (builder)
     *
     * @param Expression\Join $join
     * @return Select
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
    public function createJoin($table, string $type = Expression\Join::INNER): Expression\Join
    {
        return new Expression\Join($table, $type);
    }

    /**
     * Create and add a join to the query (builder)
     *
     * @param string|array $table
     * @param string|array $conditions
     * @param string $type
     * @param array|null $fields
     * @return Select
     */
    public function join($table, $conditions, string $type = Expression\Join::INNER, ?array $fields = null): Select
    {
        $join = $this->createJoin($table, $type);
        if (\is_string($conditions)) {
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
     * @param bool $replace
     * @return Select
     */
    public function group(array $fields, bool $replace = true): Select
    {
        if ($replace) {
            $this->groupByFields = $fields;
        } else {
            $this->groupByFields = array_merge($this->groupByFields, $fields);
        }

        return $this;
    }

    /**
     * @param bool $useFoundRows
     * @return Select
     */
    public function useFoundRows(bool $useFoundRows = true): Select
    {
        $this->useFoundRows = (bool) $useFoundRows;
        return $this;
    }

    /**
     * Execute the generated query
     *
     * @return Result
     */
    public function execute(): Result
    {
        $result = new Result($this->db->query($this->generateSql()), $this->db);

        if ($this->useFoundRows) {
            $resultTotalRows = new Result($this->db->query($this->generateSqlFoundRows()), $this->db);
            $result->setFoundRows($resultTotalRows->fetchCell());
        }

        return $result;
    }

    /**
     * Generate sql for joins
     *
     * @param array $fields
     * @return array
     * @since 2.1.43
     */
    private function generateSqlForJoins(array $fields): array
    {
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

        return compact('joinSql', 'fields');
    }

    /**
     * Generate the SQL to get the total rows.
     * This is a replacement for the since MySQL 8.0.17 deprecated
     * SQL_CALC_FOUND_ROWS query modifier and FOUND_ROWS().
     *
     * @return string
     * @since 2.1.43
     */
    public function generateSqlFoundRows(): string
    {
        if (empty($this->table)) {
            throw new \RuntimeException('table must be set');
        }

        $fields = $this->fields;

        $sql = 'SELECT COUNT(*) FROM ' . $this->getTableSql($this->table);

        // generate sql for joins
        $joinSql = $this->generateSqlForJoins($fields);
        $joinSql = ($joinSql !== []) ? $joinSql['joinSql'] : [];

        $sql .= implode(' ', $joinSql);
        $sql .= $this->generateWhereSql();
        $groupBy = $this->generateGroupBySql();
        $sql .= $groupBy;

        // Special handling for sql queries with the group by clause.
        if ($groupBy !== '') {
            $sql = 'SELECT COUNT(*) FROM (' . $sql . ') AS countOfRows';
        }

        return $sql;
    }

    /**
     * Generate the SQL executed by execute()
     *
     * @return string
     * @throws \RuntimeException if sql could not be generated
     * @throws \InvalidArgumentException if invalid parts were configured
     */
    public function generateSql(): string
    {
        if (empty($this->table)) {
            throw new \RuntimeException('table must be set');
        }

        $fields = $this->fields;

        $sql = 'SELECT ';

        // generate sql for joins
        $sqlAndFields = $this->generateSqlForJoins($fields);
        $joinsSql = ($sqlAndFields !== []) ? $sqlAndFields['joinSql'] : [];
        $fields = ($sqlAndFields !== []) ? $sqlAndFields['fields'] : [];

        // start query
        $sql .= $this->getFieldsSql($fields)
            . ' FROM ' . $this->getTableSql($this->table);

        $sql .= implode('', $joinsSql);

        $sql .= $this->generateWhereSql();
        $sql .= $this->generateGroupBySql();

        $sql .= $this->generateOrderBySql();

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
     * @param array $fields
     * @return string
     */
    protected function getFieldsSql(array $fields): string
    {
        if (empty($fields)) {
            return '*';
        }

        $sqlFields = [];

        foreach ($fields as $key => $value) {
            if (!$value instanceof Expression\Expression) {
                if (strpos($value, '(') === false) {
                    $value = $this->db->quote($value);
                }
            }
            //non int key -> alias
            if (!\is_int($key)) {
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
        if (\is_array($table)) {
            $tableName = reset($table);
            $tableAlias = key($table);
        } else {
            $tableName = $table;
        }

        $sql = $this->db->quote('[prefix]_' . $tableName);
        if (isset($tableAlias) && \is_string($tableAlias)) {
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
    protected function createFieldsArray($fields): array
    {
        if (\is_string($fields)) {
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
        if (!\is_array($fields)) {
            throw new \InvalidArgumentException('array or single field (or function) string expected');
        }

        return $fields;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @since 2.1.50 supports for functions in group by
     */
    protected function generateGroupBySql(): string
    {
        $sql = '';
        // add GROUP BY to sql
        if (!empty($this->groupByFields)) {
            $sql .= ' GROUP BY ';
            $fields = [];
            foreach ($this->groupByFields as $key => $value) {
                if (\is_int($key)) {
                    // Regular expression to detect a function call like AVG().
                    if (preg_match('([a-zA-Z_]+\(.*\))', $value) === 1) {
                        $fields[] = $value;
                    } else {
                        $fields[] = $this->db->quote($value);
                    }
                } else {
                    // A key => value pair like 'field' => 'DESC' is no longer supported. $key should always be an integer.
                    throw new \InvalidArgumentException('Invalid GROUP BY option: ' . $value);
                }
            }
            $sql .= implode(',', $fields);
        }
        return $sql;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @since 2.1.43
     */
    protected function generateOrderBySql(): string
    {
        $sql = '';
        // add ORDER BY to sql
        if (!empty($this->order)) {
            $sql .= ' ORDER BY ';
            $fields = [];
            foreach ($this->order as $column => $direction) {
                // function with ( )
                if (strpos($column, '(') !== false) {
                    $fields[] = $column . ' ' . $direction;
                } else {
                    $fields[] = $this->db->quote($column) . ' ' . $direction;
                }
            }
            $sql .= implode(',', $fields);
        }
        return $sql;
    }
}
