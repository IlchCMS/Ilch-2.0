<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Database\Mysql;

use \Ilch\Database\Mysql as DB;

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
     * @return $this|\Ilch\Database\Mysql\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function where($where)
    {
        if (is_array($where)) {
            $where = $this->createCompositeExpression($where);
        }
        if (!$where instanceof Expression\CompositePart) {
            throw new \InvalidArgumentException('array or Expression\CompositePart expected');
        }
        $this->where = $where;

        return $this;
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
        return $this->createCompositeExpression($conditions);
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
     * Creates Expression for Where
     *
     * @param array $where parts as CompositePart or array
     * @param string $type 'and' or 'or'
     * @return Expression\Composite
     */
    protected function createCompositeExpression(array $where, $type = 'and')
    {
        $parts = [];
        foreach ($where as $key => $value) {
            if ($value instanceof Expression\CompositePart) {
                $parts[] = $value;
            } else {
                $parts[] = $this->createComparisonExpression($key, $value);
            }
        }
        $compositeClass = __NAMESPACE__ . '\Expression\\' . ucfirst($type) . 'X';
        return new $compositeClass($parts);
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
        $singleComparisonOperators =  ['=', '<=', '=>', '<', '>', '!=', '<>'];

        // expect comparison of 2 fields -> don't escape (f.e. join conditions)
        if (is_int($key)) {
            $conditionParts = explode(' ', $value);
            if (count($conditionParts) != 3 || !in_array($conditionParts[1], $singleComparisonOperators)) {
                throw new \InvalidArgumentException('Invalid comparison expression');
            }
            $left = $this->db->quote($conditionParts[0]);
            $operator = $conditionParts[1];
            $right = $this->db->quote($conditionParts[2]);
        } else {
            // string key -> comparison with value(s)
            $keyParts = explode(' ', $key);
            $left = $this->db->quote(array_shift($keyParts));
            if (!empty($keyParts)) {
                $operator = implode(' ', $keyParts);
            } else {
                $operator = '=';
            }

            if (is_array($value)) {
                if ($operator === '=') {
                    $operator = 'IN';
                } elseif (in_array($operator, ['!=', '<>'])) {
                    $operator = 'NOT IN';
                }
                if (!in_array($operator, ['IN', 'NOT IN'])) {
                    throw new \InvalidArgumentException('invalid operator for multiple value comparison');
                }
                $right = '("' . implode('", "', $this->db->escapeArray($value)) . '")';
            } else {
                if (!in_array($operator, $singleComparisonOperators)) {
                    throw new \InvalidArgumentException('invalid operator for single value comparison');
                }
                $right = '"'.$this->db->escape($value).'"';
            }
        }

        return new Expression\Comparison($left, $operator, $right);
    }
}
