<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database\Mysql;

class DeleteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Object under test
     *
     * @var Delete
     */
    protected $out;

    protected function setUp(): void
    {
        parent::setUp();

        $db = $this->getMockBuilder('\Ilch\Database\Mysql')
            ->disableOriginalConstructor()
            ->onlyMethods(['escape'])
            ->getMock();
        $db->method('escape')
            ->willReturnCallback(function ($value, $addQuotes = false) {
                if ($addQuotes) {
                    $value = '"' . $value . '"';
                }
                return $value;
            });

        $this->out = new Delete($db);
    }

    public function testGenerateSql()
    {
        $this->out->from('Test')
            ->where(['id' => 5]);

        $expected = 'DELETE FROM `[prefix]_Test`'
            . ' WHERE `id` = "5"';

        self::assertEquals($expected, $this->out->generateSql());
    }

    public function testGenerateSqlWithoutWhere()
    {
        $this->out->from('Test');

        self::assertEquals('DELETE FROM `[prefix]_Test`', $this->out->generateSql());
    }

    /**
     * HAVING is only valid for SELECT queries and must not be silently
     * ignored in DELETE statements.
     */
    public function testGenerateSqlWithHavingThrows()
    {
        $this->out->from('Test')
            ->having($this->out->expr('COUNT(*) > 1'));

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('HAVING is only supported for SELECT queries');

        $this->out->generateSql();
    }
}
