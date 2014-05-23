<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 04.05.14
 * Time: 08:14
 */

namespace Ilch\Database\Mysql;


class InsertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object under test
     *
     * @var Insert
     */
    protected $out;

    protected $lastInsetId = 5;

    protected function setUp()
    {
        parent::setUp();

        $db = $this->getMockBuilder('\Ilch\Database\Mysql')
            ->disableOriginalConstructor()
            ->setMethods(['escape', 'getLastInsertId'])
            ->getMockForAbstractClass();
        $db->expects($this->any())
            ->method('escape')
            ->will($this->returnCallback(function ($value) {
                return '"' . $value . '"';
            }));
        $db->expects($this->any())
            ->method('getLastInsertId')
            ->will($this->returnValue($this->lastInsetId));

        $this->out = new Insert($db);
    }

    public function testGenerateSqlWithoutTable()
    {
        $this->setExpectedException('RuntimeException', 'table must be set');

        $this->out->generateSql();
    }

    public function testGenerateSqlWithoutValues()
    {
        $this->out->into('Test');

        $this->setExpectedException('RuntimeException', 'no valid values for insert');

        $this->out->generateSql();
    }

    public function testGenerateSqlForValues()
    {
        $this->out->into('Test')
                  ->values(['super' => 'data', 'next' => 'fieldData']);

        $expected = 'INSERT INTO `[prefix]_Test` '
            . '(`super`,`next`) VALUES ("data","fieldData")';

        $this->assertEquals($expected, $this->out->generateSql());
    }
}
