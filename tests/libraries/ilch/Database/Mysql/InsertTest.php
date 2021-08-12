<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database\Mysql;

class InsertTest extends \PHPUnit\Framework\TestCase
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
        $db->method('escape')
            ->willReturnCallback(function ($value, $addQuotes = false) {
                if ($addQuotes) {
                    $value = '"' . $value . '"';
                }
                return $value;
            });
        $db->method('getLastInsertId')
            ->willReturn($this->lastInsetId);

        $this->out = new Insert($db);
    }

    public function testGenerateSqlWithoutTable()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('table must be set');

        $this->out->generateSql();
    }

    public function testGenerateSqlWithoutValues()
    {
        $this->out->into('Test');

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('no valid values for insert');

        $this->out->generateSql();
    }

    public function testGenerateSqlForValues()
    {
        $this->out->into('Test')
                  ->values(['super' => 'data', 'next' => 'fieldData']);

        $expected = 'INSERT INTO `[prefix]_Test` '
            . '(`super`,`next`) VALUES ("data","fieldData")';

        self::assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForValuesMultipleRows()
    {
        $this->out->into('Test')
            ->columns(['super', 'next'])
            ->values([['data', 'fieldData'], ['data2', 'fieldData2'], ['data3', 'fieldData3']]);

        $expected = 'INSERT INTO `[prefix]_Test` '
            . '(`super`,`next`) VALUES ("data","fieldData"),("data2","fieldData2"),("data3","fieldData3")';

        self::assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForValuesMultipleRowsInvalidCount()
    {
        $this->out->into('Test')
            ->columns(['super', 'next'])
            ->values([['data'], ['data2', 'fieldData2']]);

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('count of values does not fit the count of columns');

        $this->out->generateSql();
    }

    public function testGenerateSqlForValuesMultipleRowsNotAnArray()
    {
        $this->out->into('Test')
            ->columns(['super', 'next'])
            ->values(['data', 'data2', 'fieldData2']);

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('no valid values for insert');

        $this->out->generateSql();
    }
}
