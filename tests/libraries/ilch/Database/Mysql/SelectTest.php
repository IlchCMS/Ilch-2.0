<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database\Mysql;

use Ilch\Database\Mysql\Expression\Expression;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Object under test
     * @var Select
     */
    protected $out;

    protected function setUp(): void
    {
        parent::setUp();

        $db = $this->getMockBuilder('\Ilch\Database\Mysql')
            ->disableOriginalConstructor()
            ->onlyMethods(['escape'])
            ->getMockForAbstractClass();
        $db->method('escape')
            ->willReturnCallback(function ($value, $addQuotes = false) {
                if ($addQuotes) {
                    $value = '"' . $value . '"';
                }
                return $value;
            });

        $this->out = new Select($db);
    }

    public function testGenerateSqlWithoutTable()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('table must be set');

        $this->out->generateSql();
    }

    /**
     * @dataProvider dpForTable
     *
     * @param string|string[] $table
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlForTable($table, string $expectedSqlPart)
    {
        $this->out->from($table);

        $expected = 'SELECT * FROM ' . $expectedSqlPart;

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForTable(): array
    {
        return [
            'simple tablename' => [
                'table' => 'Test',
                'expectedSqlPart' => '`[prefix]_Test`'
            ],
            'table with alias' => [
                'table' => ['alias' => 'Test'],
                'expectedSqlPart' => '`[prefix]_Test` AS `alias`'
            ],
            'table with invalid alias' => [
                'table' => [1 => 'Test'],
                'expectedSqlPart' => '`[prefix]_Test`'
            ]
        ];
    }

    /**
     * @dataProvider dpForFields
     *
     * @param string|array $fields
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlForFields($fields, string $expectedSqlPart)
    {
        $this->out->from('Test')
            ->fields($fields);

        $expected = 'SELECT ' . $expectedSqlPart . ' FROM `[prefix]_Test`';

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForFields(): array
    {
        return [
            'string all' => [
                'fields'          => new Expression('a.*'),
                'expectedSqlPart' => 'a.*'
            ],
            'string field' => [
                'fields'          => 'field',
                'expectedSqlPart' => '`field`'
            ],
            'array multiple fields' => [
                'fields'          => ['field1', 'field2'],
                'expectedSqlPart' => '`field1`,`field2`'
            ],
            'array multiple fields all' => [
                'fields'          => [new Expression('a.*'), 'field2'],
                'expectedSqlPart' => 'a.*,`field2`'
            ],
            'array multiple fields with alias' => [
                'fields'          => ['name' => 'field1', 'super' => 'field2'],
                'expectedSqlPart' => '`field1` AS `name`,`field2` AS `super`'
            ],
            'string expression' => [
                'fields'          => 'COUNT(*)',
                'expectedSqlPart' => 'COUNT(*)'
            ],
            'object expression' => [
                'fields'          =>  new Expression('SUM(`field`)'),
                'expectedSqlPart' => 'SUM(`field`)'
            ],
            'multiple expressions with alias' => [
                'fields' => [
                    'fieldSum' => new Expression('SUM(`field`)'),
                    'count' => new Expression('COUNT(*)')
                ],
                'expectedSqlPart' => 'SUM(`field`) AS `fieldSum`,COUNT(*) AS `count`'
            ]
        ];
    }

    /**
     * @dataProvider dpForWhere
     *
     * @param array|callable $where
     * @param string $expectedSqlPart
     * @param string|null $type
     */
    public function testGenerateSqlForWhere($where, string $expectedSqlPart, string $type = null)
    {
        if (\is_callable($where)) {
            $where = $where($this->out);
        }

        $this->out->from('Test');
        if (isset($type)) {
            $this->out->where($where, $type);
        } else {
            $this->out->where($where);
        }

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE ' . $expectedSqlPart;

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * Data provider for testGenerateSqlForWhere
     * @return array
     */
    public function dpForWhere(): array
    {
        return [
            'simple condition'  => [
                'where'           => ['field1' => 5],
                'expectedSqlPart' => '`field1` = "5"'
            ],
            'and conditions'    => [
                'where'           => ['field1' => 5, 'field2' => 'super'],
                'expectedSqlPart' => '(`field1` = "5" AND `field2` = "super")'
            ],
            'or conditions by type'     => [
                'where'           => ['field1' => 5, 'field2' => 'super'],
                'expectedSqlPart' => '(`field1` = "5" OR `field2` = "super")',
                'type'            => 'or'
            ],
            'or conditions with object'     => [
                'where'           => function (Select $out) {
                        return $out->orX(['field1' => 5, 'field2' => 'super']);
                    },
                'expectedSqlPart' => '(`field1` = "5" OR `field2` = "super")'
            ],
            'complex condition' => [
                'where'           => function (Select $out) {
                        return [
                            'field1' => 5,
                            $out->orX([$out->comp('field2', 'super'), $out->comp('field2', 'geil')])
                        ];
                    },
                'expectedSqlPart' => '(`field1` = "5" AND (`field2` = "super" OR `field2` = "geil"))'
            ],
            //array - positive
            'in array'          => [
                'where'           => ['field' => [5, 6]],
                'expectedSqlPart' => '`field` IN ("5", "6")'
            ],
            'in array - ='      => [
                'where'           => ['field =' => [5, 6]],
                'expectedSqlPart' => '`field` IN ("5", "6")'
            ],
            'in array - IN'     => [
                'where'           => ['field IN' => [5, 6]],
                'expectedSqlPart' => '`field` IN ("5", "6")'
            ],
            //array - negative
            'not in array - !='     => [
                'where'           => ['field !=' => [5, 6]],
                'expectedSqlPart' => '`field` NOT IN ("5", "6")'
            ],
            'not in array - <>'     => [
                'where'           => ['field <>' => [5, 6]],
                'expectedSqlPart' => '`field` NOT IN ("5", "6")'
            ],
            'not in array - NOT IN' => [
                'where'           => ['field NOT IN' => [5, 6]],
                'expectedSqlPart' => '`field` NOT IN ("5", "6")'
            ],
            //IS Function
            'IS null' => [
                'where'           => ['field IS' => 'NULL'],
                'expectedSqlPart' => '`field` IS NULL'
            ],
            'IS NOT null' => [
                'where'           => ['field IS NOT' => 'NULL'],
                'expectedSqlPart' => '`field` IS NOT NULL'
            ],
        ];
    }

    /**
     * @dataProvider dpOperators
     *
     * @param string $operator
     */
    public function testGenerateSqlForWhereWithSpecialOperator(string $operator)
    {
        $this->out->from('Test')
            ->where(['field1 ' . $operator => 5]);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE `field1` ' . $operator . ' "5"';

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * Operator data provider
     * @return array
     */
    public function dpOperators(): array
    {
        return [['='], ['<='], ['>='], ['<'], ['>'], ['!='], ['<>'], ['LIKE'], ['NOT LIKE']];
    }

    /**
     * @dataProvider dpForOrWhere
     *
     * @param array|bool $where
     * @param string|bool $type
     * @param array $orWhere
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlForOrWhere($where, $type, array $orWhere, string $expectedSqlPart)
    {
        $this->out->from('Test');
        if (!empty($where)) {
            $this->out->where($where, $type);
        }
        $this->out->orWhere($orWhere);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE ' . $expectedSqlPart;

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForOrWhere(): array
    {
        return [
            'with empty where and single condition' => [
                'where' => false,
                'type'  => false,
                'orWhere' => ['field1' => '2'],
                'expectedSqlPart' => '`field1` = "2"'
            ],
            'with empty where and multiple condition' => [
                'where' => false,
                'type'  => false,
                'orWhere' => ['field1' => '2', 'field1 >' => 5],
                'expectedSqlPart' => '(`field1` = "2" OR `field1` > "5")'
            ],
            'with or where and multiple condition' => [
                'where' => ['field1 <' => '0'],
                'type'  => 'or',
                'orWhere' => ['field1' => '2', 'field1 >' => 5],
                'expectedSqlPart' => '(`field1` < "0" OR `field1` = "2" OR `field1` > "5")'
            ],
            'with and where and multiple condition' => [
                'where' => ['field1 <' => '0', 'field2' => 3],
                'type'  => 'and',
                'orWhere' => ['field1' => '2', 'field1 >' => 5],
                'expectedSqlPart' => '((`field1` < "0" AND `field2` = "3") OR `field1` = "2" OR `field1` > "5")'
            ],
        ];
    }

    /**
     * @dataProvider dpForTestGenerateSqlForOrderBy
     *
     * @param array $order
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlForOrderBy(array $order, string $expectedSqlPart)
    {
        $this->out->from('Test')
            ->order($order);

        $expected = 'SELECT * FROM `[prefix]_Test` ' . $expectedSqlPart;

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForTestGenerateSqlForOrderBy(): array
    {
        return [
            'field without table' => [
                'order' => ['field1' => 'ASC'],
                'expectedSqlPart' => 'ORDER BY `field1` ASC'
            ],
            'fields with table' => [
                'order' => ['table1.id' => 'DESC', 'table2.sort' => 'ASC'],
                'expectedSqlPart' => 'ORDER BY `table1`.`id` DESC,`table2`.`sort` ASC'
            ]
        ];
    }

    /**
     * @dataProvider dpForLimit
     *
     * @param int|array $limit
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlForLimitWithInteger($limit, string $expectedSqlPart)
    {
        $this->out->from('Test')
            ->limit($limit);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT ' . $expectedSqlPart;

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForLimit(): array
    {
        return [
            'with integer'  => [
                'limit'           => 5,
                'expectedSqlPart' => '5'
            ],
            'with array'    => [
                'where'           => [5, 10],
                'expectedSqlPart' => '5, 10'
            ]
        ];
    }

    public function testGenerateSqlForLimitWithOffset()
    {
        $this->out->from('Test')
            ->limit(10)
            ->offset(5);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT 5, 10';

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @dataProvider dpForLimitWithPage
     *
     * @param int $page
     * @param int $expectedOffset
     */
    public function testGenerateSqlForLimitWithPage(int $page, int $expectedOffset)
    {
        $this->out->from('Test')
            ->limit(10)
            ->page($page);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT ' . $expectedOffset . ', 10';

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * Data provider for testGenerateSqlForLimitWithPage
     * @return array
     */
    public function dpForLimitWithPage(): array
    {
        //expected offset for limit 10
        return [
            ['page' => 1, 'expectedOffset' => 0],
            ['page' => 2, 'expectedOffset' => 10],
            ['page' => 5, 'expectedOffset' => 40],
            //invalid pages
            ['page' => 0, 'expectedOffset' => 0],
            ['page' => -3, 'expectedOffset' => 0],
        ];
    }

    /**
     * @dataProvider dpForTestGenerateSqlWithGroup
     *
     * @param array $groupByFields
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlWithGroup(array $groupByFields, string $expectedSqlPart)
    {
        $this->out->from('Test')
            ->group($groupByFields);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' GROUP BY ' . $expectedSqlPart;

        self::assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForTestGenerateSqlWithGroup(): array
    {
        return [
            'one field' => [
                'groupByFields' => ['field1'],
                'expectedSqlPart' => '`field1`'
            ],
            'multiple fields with table' => [
                'groupByFields' => ['field1', 'table.field2'],
                'expectedSqlPart' => '`field1`,`table`.`field2`'
            ],
            'one field with aggregate function in group by' => [
                'groupByFields' => ['GROUP_CONCAT(field1)'],
                'expectedSqlPart' => 'GROUP_CONCAT(field1)'
            ],
            'multiple fields with table and aggregate function in group by' => [
                'groupByFields' => ['GROUP_CONCAT(field1)', 'GROUP_CONCAT(table.field2)'],
                'expectedSqlPart' => 'GROUP_CONCAT(field1),GROUP_CONCAT(table.field2)'
            ],
            'one field with COUNT(DISTINCT) aggregate function in group by' => [
                'groupByFields' => ['COUNT(DISTINCT field1)'],
                'expectedSqlPart' => 'COUNT(DISTINCT field1)'
            ],
            'multiple fields with table and COUNT(DISTINCT) aggregate function in group by' => [
                'groupByFields' => ['COUNT(DISTINCT field1)', 'COUNT(DISTINCT table.field2)'],
                'expectedSqlPart' => 'COUNT(DISTINCT field1),COUNT(DISTINCT table.field2)'
            ]
        ];
    }

    /**
     * @dataProvider dpForJoinConditions
     *
     * @param string|string[] $conditions
     * @param string $expectedSqlPart
     */
    public function testGenerateSqlForJoinConditions($conditions, string $expectedSqlPart)
    {
        $this->out->from(['a' => 'Test']);
        $this->out->join(['b' => 'Table2'], $conditions);

        $expectedSql = 'SELECT * FROM `[prefix]_Test` AS `a`'
            . ' INNER JOIN `[prefix]_Table2` AS `b` ON ' . $expectedSqlPart;

        self::assertEquals($expectedSql, $this->out->generateSql());
    }

    /**
     * @return array
     */
    public function dpForJoinConditions(): array
    {
        return [
            'single field comparison' => [
                'conditions' => 'a.field1 = b.field2',
                'expectedSqlPart' => '`a`.`field1` = `b`.`field2`'
            ],
            'single value comparison' => [
                'conditions' => ['b.field2 >' => '3'],
                'expectedSqlPart' => '`b`.`field2` > "3"'
            ],
            'complex comparison' => [
                'conditions' => ['a.field1 != b.field3', 'b.field2 >' => '3'],
                'expectedSqlPart' => '(`a`.`field1` != `b`.`field3` AND `b`.`field2` > "3")'
            ]
        ];
    }

    /**
     * Test if sql query is correct with two joins.
     * There was an issue where the second (or more) join started with two spaces.
     */
    public function testGenerateSqlForMoreThanOneJoin()
    {
        $this->out->from(['a' => 'Test']);
        $this->out->join(['b' => 'Table2'], 'a.field1 = b.field2');
        $this->out->join(['c' => 'Table3'], 'a.field1 = c.field2');

        $expectedSql = 'SELECT * FROM `[prefix]_Test` AS `a`'
            . ' INNER JOIN `[prefix]_Table2` AS `b` ON `a`.`field1` = `b`.`field2`'
            . ' INNER JOIN `[prefix]_Table3` AS `c` ON `a`.`field1` = `c`.`field2`';

        self::assertEquals($expectedSql, $this->out->generateSql());
    }

    public function testExpectedExceptionGroupBySortOrder()
    {
        $this->out->from('Test')
            ->group(['table.field1' => 'DESC']);
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid GROUP BY option: DESC');
        $this->out->generateSql();
    }
}
