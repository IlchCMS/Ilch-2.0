<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database\Mysql;

class UpdateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Object under test
     *
     * @var Update
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

        $this->out = new Update($db);
    }

    public function testGenerateSqlWithoutTable()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('table must be set');

        $this->out->generateSql();
    }

    public function testGenerateSqlWithoutValues()
    {
        $this->out->table('Test');

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('no valid values for update');
        $this->out->generateSql();
    }

    public function testGenerateSqlForValues()
    {
        $this->out->table('Test')
                  ->values(['super' => 'data', 'next' => 'fieldData']);

        $expected = 'UPDATE `[prefix]_Test` SET '
            . '`super` = "data",`next` = "fieldData"';

        self::assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlForValuesAndSimpleWhere()
    {
        $this->out->table('Test')
            ->values(['super' => 'data', 'next' => 'fieldData'])
            ->where(['id' => 5]);

        $expected = 'UPDATE `[prefix]_Test` SET '
            . '`super` = "data",`next` = "fieldData"'
            . ' WHERE `id` = "5"';

        self::assertEquals($expected, $this->out->generateSql());
    }
}
