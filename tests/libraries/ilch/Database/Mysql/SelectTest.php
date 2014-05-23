<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 02.05.14
 * Time: 09:26
 */

namespace Ilch\Database\Mysql;


class SelectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object under test
     * @var Select
     */
    protected $out;

    protected function setUp()
    {
        parent::setUp();

        $db = $this->getMockBuilder('\Ilch\Database\Mysql')
            ->disableOriginalConstructor()
            ->setMethods(['escape'])
            ->getMockForAbstractClass();
        $db->expects($this->any())
            ->method('escape')
            ->will($this->returnCallback(function ($value) {
                return '"' . $value. '"';
            }));

        $this->out = new Select($db);
    }

    public function testGenerateSqlWithoutTable()
    {
        $this->setExpectedException('RuntimeException', 'table must be set');

        $this->out->generateSql();
    }

    public function testGenerateSqlWithoutSettingFields()
    {
        $this->out->from('Test');

        $expected = 'SELECT * FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithSingleField()
    {
        $this->out->from('Test')
            ->fields('field');

        $expected = 'SELECT `field` FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithMultipleFields()
    {
        $this->out->from('Test')
            ->fields(['field1', 'field2']);

        $expected = 'SELECT `field1`,`field2` FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithMultipleFieldsAndAlias()
    {
        $this->out->from('Test')
            ->fields(['name' => 'field1', 'super' => 'field2']);

        $expected = 'SELECT `field1` AS `name`,`field2` AS `super` FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithFieldStringExpression()
    {
        $this->out->from('Test')
            ->fields('COUNT(*)');

        $expected = 'SELECT COUNT(*) FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithFieldExpression()
    {
        $this->out->from('Test')
            ->fields($this->out->expr('SUM(`field`)'));

        $expected = 'SELECT SUM(`field`) FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithMultipleFieldExpressions()
    {
        $this->out->from('Test')
            ->fields([
                'fieldSum' => $this->out->expr('SUM(`field`)'),
                'count' => $this->out->expr('COUNT(*)')
            ]);

        $expected = 'SELECT SUM(`field`) AS `fieldSum`,COUNT(*) AS `count` FROM `[prefix]_Test`';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForWhereWithSingleValueArray()
    {
        $this->out->from('Test')
            ->where(['field1' => 5]);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE `field1` = "5"';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForWhereWithArray()
    {
        $this->out->from('Test')
            ->where(['field1' => 5, 'field2' => 'super']);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE (`field1` = "5" AND `field2` = "super")';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForWhereWithOrExpression()
    {
        $this->out->from('Test')
            ->where($this->out->orX(['field1' => 5, 'field2' => 'super']));

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE (`field1` = "5" OR `field2` = "super")';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForWhereWithMultipleCompositeExpressions()
    {
        $this->out->from('Test')
            ->where(([
                'field1' => 5,
                $this->out->orX([
                    $this->out->comp('field2', 'super'),
                    $this->out->comp('field2', 'geil')
                ])
            ]));

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE (`field1` = "5" AND (`field2` = "super" OR `field2` = "geil"))';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @dataProvider dpOperators
     *
     * @param string $operator
     */
    public function testGenerateSqlForWhereWithSpecialOperator($operator)
    {
        $this->out->from('Test')
            ->where(['field1 ' . $operator => 5]);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE `field1` ' . $operator . ' "5"';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    /**
     * Operator data provider
     * @return array
     */
    public function dpOperators()
    {
        return [['='], ['<='], ['=>'], ['<'], ['>'], ['!='], ['<>']];
    }

    /**
     * @dataProvider dpForMultipleValues
     *
     * @param array $where
     * @param string $expectedCondition
     */
    public function testGenerateSqlForWhereWithMultipleValue(array $where, $expectedCondition)
    {
        $this->out->from('Test')
            ->where($where);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' WHERE ' . $expectedCondition;

        $this->assertEquals($expected, $this->out->generateSql());
    }

    /**
     * Data provider for testGenerateSqlForWhereWithMultipleValue
     * @return array
     */
    public function dpForMultipleValues()
    {
        return [
            //positive
            [
                'where' => ['field' => [5, 6]],
                'expectedCondition' => '`field` IN ("5","6")'
            ],
            [
                'where' => ['field =' => [5, 6]],
                'expectedCondition' => '`field` IN ("5","6")'
            ],
            [
                'where' => ['field IN' => [5, 6]],
                'expectedCondition' => '`field` IN ("5","6")'
            ],
            // negative
            [
                'where' => ['field !=' => [5, 6]],
                'expectedCondition' => '`field` NOT IN ("5","6")'
            ],
            [
                'where' => ['field <>' => [5, 6]],
                'expectedCondition' => '`field` NOT IN ("5","6")'
            ],
            [
                'where' => ['field NOT IN' => [5, 6]],
                'expectedCondition' => '`field` NOT IN ("5","6")'
            ],
        ];
    }

    public function testGenerateSqlForOrderBy()
    {
        $this->out->from('Test')
            ->order(['field1' => 'ASC']);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' ORDER BY `field1` ASC';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForLimitWithInteger()
    {
        $this->out->from('Test')
            ->limit(5);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT 5';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForLimitWithArray()
    {
        $this->out->from('Test')
            ->limit([5, 10]);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT 5, 10';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForLimitWithOffset()
    {
        $this->out->from('Test')
            ->limit(10)
            ->offset(5);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT 5, 10';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    /**
     * @dataProvider dpForLimitWithPage
     *
     * @param integer $page
     * @param integer $expectedOffset
     */
    public function testGenerateSqlForLimitWithPage($page, $expectedOffset)
    {
        $this->out->from('Test')
            ->limit(10)
            ->page($page);

        $expected = 'SELECT * FROM `[prefix]_Test`'
            . ' LIMIT ' . $expectedOffset . ', 10';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    /**
     * Data provider for testGenerateSqlForLimitWithPage
     * @return array
     */
    public function dpForLimitWithPage()
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
}
