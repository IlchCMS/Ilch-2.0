<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use Ilch\Database\Mysql as DB;

abstract class QueryBuilder
{
    /**
     * @var \Ilch\Database\Mysql
     */
    protected $db;

    /**
     * @var string|array
     */
    protected $table;

    /**
     * @var array|null
     */
    protected $where;

    /**
     * Injects the database adapter.
     *
     * @param \Ilch\Database\Mysql $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * Adds where to query builder.
     *
     * @param array|\Ilch\Database\Mysql\Expression\CompositePart $where
     * @param string $type [default: 'and'] 'and' or 'or'
     * @return $this|\Ilch\Database\Mysql\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function where($where, $type = 'and')
    {
        if (\is_array($where)) {
            if (!\in_array($type, ['and', 'or'])) {
                throw new \InvalidArgumentException('Invalid type: "and" or "or" expected');
            }
            $where = $this->createCompositeExpression($where, $type);
        }
        if (!$where instanceof Expression\CompositePart) {
            throw new \InvalidArgumentException('array or Expression\CompositePart expected');
        }
        $this->where = $where;

        return $this;
    }

    /**
     * Adds OR conditions to the existing where conditions
     *
     * @param array|\Ilch\Database\Mysql\Expression\CompositePart $where
     * @return $this|\Ilch\Database\Mysql\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function orWhere($where)
    {
        return $this->addWhere('or', $where);
    }

    /**
     * Adds AND conditions to the existing where conditions
     *
     * @param array|\Ilch\Database\Mysql\Expression\CompositePart $where
     * @return $this|\Ilch\Database\Mysql\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function andWhere($where)
    {
        return $this->addWhere('and', $where);
    }

    /**
     * Creates Expression\OrX for the conditions in the array
     * @param array $conditions
     * @return Expression\OrX
     */
    public function orX(array $conditions)
    {
        return $this->createCompositeExpression($conditions, 'or');
    }

    /**
     * Creates Expression\AndX for the conditions in the array
     * @param array $conditions
     * @return Expression\AndX
     */
    public function andX(array $conditions)
    {
        return $this->createCompositeExpression($conditions, 'and');
    }

    /**
     * Creates Expression\Comparison if multiple conditions for one field are needed
     *
     * Example:
     * ```php
     *    $qb->comp('field', 5);      // field = 5
     *    $qb->comp('field >', 6);    // field > 6
     *    $qb->comp('field', [5, 6]); // field IN (5, 6)
     * ```
     *
     * @param string $fieldAndOperator field optionally followed by the operator
     * @param mixed $value
     * @return Expression\Comparison
     */
    public function comp($fieldAndOperator, $value)
    {
        return $this->createComparisonExpression($fieldAndOperator, $value);
    }

    /**
     * Content that wont be escaped when inserted into a query (builder)
     * @param $content
     * @return Expression\Expression
     */
    public function expr($content)
    {
        return new Expression\Expression($content);
    }

    /**
     * Execute the generated query
     *
     * @return \Ilch\Database\Mysql\Result|int
     */
    abstract public function execute();

    /**
     * Generate the SQL executed by execute()
     *
     * @return string
     */
    abstract public function generateSql();

    /**
     * Generate WHERE part for SQL
     *
     * @return string
     */
    protected function generateWhereSql()
    {
        $sql = '';
        if (isset($this->where)) {
            $where = (string) $this->where;
            if (!empty($where)) {
                $sql = ' WHERE ' . $where;
            }
        }
        return $sql;
    }

    /**
     * Helper for and/orWhere methods
     *
     * @param string $type 'and' or 'or'
     * @param array|\Ilch\Database\Mysql\Expression\CompositePart $where
     * @return $this|QueryBuilder
     * @throws \InvalidArgumentException
     */
    protected function addWhere($type, $where)
    {
        $oppositeType = $type === 'and' ? 'or' : 'and';
        // add to existing or
        if (is_a($this->where, __NAMESPACE__ . '\Expression\\' . ucfirst($type) . 'X')) {
            if (\is_array($where)) {
                $this->where->addParts($this->createCompositePartArray($where));
            } elseif ($where instanceof Expression\CompositePart) {
                $this->where->addPart($where);
            } else {
                throw new \InvalidArgumentException('array or Expression\CompositePart expected');
            }
            return $this;
        }

        // new or insert existing where into condition
        if (is_a($this->where, __NAMESPACE__ . '\Expression\\' . ucfirst($oppositeType) . 'X')) {
            if (!\is_array($where)) {
                $where = [$where];
            }
            array_unshift($where, $this->where);
        }
        return $this->where($where, $type);
    }

    /**
     * Creates Expression for Where
     *
     * @param array $where parts as CompositePart or array
     * @param string $type 'and' or 'or'
     * @return Expression\Composite
     */
    protected function createCompositeExpression(array $where, $type)
    {
        $compositeClass = __NAMESPACE__ . '\Expression\\' . ucfirst($type) . 'X';
        return new $compositeClass($this->createCompositePartArray($where));
    }

    /**
     * Create an array of composite parts
     * @param $where
     * @return Expression\CompositePart[]
     */
    protected function createCompositePartArray($where)
    {
        $parts = [];
        foreach ($where as $key => $value) {
            if ($value instanceof Expression\CompositePart) {
                $parts[] = $value;
            } else {
                $parts[] = $this->createComparisonExpression($key, $value);
            }
        }
        return $parts;
    }

    /**
     * Create Expression for Comparison
     *
     * @param string|integer $key
     * @param mixed $value
     * @return Expression\Comparison
     * @throws \InvalidArgumentException
     */
    protected function createComparisonExpression($key, $value)
    {
        $singleComparisonOperators =  ['=', '<=', '>=', '<', '>', '!=', '<>', 'LIKE', 'NOT LIKE', 'IS', 'IS NOT'];

        // expect comparison of 2 fields -> don't escape (f.e. join conditions)
        if (\is_int($key)) {
            $conditionParts = explode(' ', $value);
            if (\count($conditionParts) < 3 || ( \count($conditionParts) == 3 && !\in_array($conditionParts[1], $singleComparisonOperators) ) || ( \count($conditionParts) == 4 && !\in_array($conditionParts[1].' '.$conditionParts[2], $singleComparisonOperators) ) ) {
                throw new \InvalidArgumentException('Invalid comparison expression');
            }

            $left = $this->db->quote($conditionParts[0]);
            if (\count($conditionParts) == 4) {
                $operator = $conditionParts[1].' '.$conditionParts[2];
            } else {
                $operator = $conditionParts[1];
            }

            if (\in_array($operator, ['IS', 'IS NOT'])) {
                $right = 'NULL';
            } else if (\count($conditionParts) == 4) {
                $right = $this->db->quote($conditionParts[3]);
            } else {
                $right = $this->db->quote($conditionParts[2]);
            }
        } else {
            // string key -> comparison with value(s)
            $keyParts = explode(' ', $key);
            $left = $this->db->quote(array_shift($keyParts));
            if (!empty($keyParts)) {
                $operator = implode(' ', $keyParts);
            } else {
                $operator = '=';
            }

            if (\is_array($value)) {
                if ($operator === '=') {
                    $operator = 'IN';
                } elseif (\in_array($operator, ['!=', '<>'])) {
                    $operator = 'NOT IN';
                }
                if (!\in_array($operator, ['IN', 'NOT IN'])) {
                    throw new \InvalidArgumentException('invalid operator for multiple value comparison');
                }
                $right = '(' . implode(', ', $this->db->escapeArray($value, true)) . ')';
            } else {
                if (!\in_array($operator, $singleComparisonOperators)) {
                    throw new \InvalidArgumentException('invalid operator for single value comparison');
                }
                if (\in_array($operator, ['IS', 'IS NOT'])) {
                    $right = 'NULL';
                } else {
                    $right = $this->db->escape($value, true);
                }
            }
        }

        return new Expression\Comparison($left, $operator, $right);
    }
}
