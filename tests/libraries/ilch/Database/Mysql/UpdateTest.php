<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 04.05.14
 * Time: 08:14
 */

namespace Ilch\Database\Mysql;


class UpdateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object under test
     *
     * @var Update
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
                return '"' . $value . '"';
            }));

        $this->out = new Update($db);
    }

    public function testGenerateSqlWithoutTable()
    {
        $this->setExpectedException('RuntimeException', 'table must be set');

        $this->out->generateSql();
    }

    public function testGenerateSqlWithoutValues()
    {
        $this->out->table('Test');

        $this->setExpectedException('RuntimeException', 'no valid values for update');

        $this->out->generateSql();
    }

    public function testGenerateSqlForValues()
    {
        $this->out->table('Test')
                  ->values(['super' => 'data', 'next' => 'fieldData']);

        $expected = 'UPDATE `[prefix]_Test` SET '
            . '`super` = "data",`next` = "fieldData"';

        $this->assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForValuesAndSimpleWhere()
    {
        $this->out->table('Test')
            ->values(['super' => 'data', 'next' => 'fieldData'])
            ->where(['id' => 5]);

        $expected = 'UPDATE `[prefix]_Test` SET '
            . '`super` = "data",`next` = "fieldData"'
            . ' WHERE `id` = "5"';

        $this->assertEquals($expected, $this->out->generateSql());
    }
}
