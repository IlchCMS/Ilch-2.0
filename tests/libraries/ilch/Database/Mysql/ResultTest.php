<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database\Mysql;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;

class ResultTest extends DatabaseTestCase
{
    protected static $fillDbOnSetUp = self::PROVISION_ON_SETUP_BEFORE_CLASS;

    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../../_files/mysql_database.yml');
    }

    protected static function getSchemaSQLQueries(): string
    {
        $configAdmin = new AdminConfig();
        $configUser = new UserConfig();
        return $configAdmin->getInstallSql() . $configUser->getInstallSql();
    }

    /**
     * @dataProvider dpForFetchCell
     *
     * @param integer|string $cellParam
     * @param string $expected
     */
    public function testFetchCell($cellParam, string $expected)
    {
        $result = $this->db->select('*', 'groups')->execute();
        self::assertEquals($expected, $result->fetchCell($cellParam));
    }

    /**
     * @return array
     */
    public function dpForFetchCell(): array
    {
        return [
            'null' => [
                'param' => null,
                'expected' => '1'
            ],
            'integer -> 0' => [
                'param' => 0,
                'expected' => '1'
            ],
            'integer -> 1' => [
                'param' => 1,
                'expected' => 'Admin'
            ],
            'string id' => [
                'param' => 'id',
                'expected' => '1'
            ],
            'string name' => [
                'param' => 'name',
                'expected' => 'Admin'
            ]
        ];
    }

    /**
     * @dataProvider dpForFetchArray
     *
     * @param int $type
     * @param array $expected
     */
    public function testFetchArray(int $type, array $expected)
    {
        $result = $this->db->select('*', 'groups')->execute();
        self::assertEquals($expected, $result->fetchArray($type));
    }

    /**
     * @return array
     */
    public function dpForFetchArray(): array
    {
        return [
            'both' => [
                'type' => Result::BOTH,
                'expected' => [0 => '1', 1 => 'Admin', 'id' => '1', 'name' => 'Admin']
            ],
            'num' => [
                'type' => Result::NUM,
                'expected' => [0 => '1', 1 => 'Admin']
            ],
            'assoc' => [
                'type' => Result::ASSOC,
                'expected' => ['id' => '1', 'name' => 'Admin']
            ],
        ];
    }

    public function testFetchRow()
    {
        $expectedRows = [
            [0 => '1', 1 => 'Admin'],
            [0 => '2', 1 => 'Guest'],
            null
        ];

        $result = $this->db->select('*', 'groups')
            ->limit(2)
            ->execute();

        foreach ($expectedRows as $expectedRow) {
            self::assertSame($expectedRow, $result->fetchRow());
        }
    }

    public function testFetchAssoc()
    {
        $expectedRows = [
            ['id' => '1', 'name' => 'Admin'],
            ['id' => '2', 'name' => 'Guest'],
            null
        ];

        $result = $this->db->select('*', 'groups')
            ->limit(2)
            ->execute();

        foreach ($expectedRows as $expectedRow) {
            self::assertSame($expectedRow, $result->fetchAssoc());
        }
    }

    /**
     * @dataProvider dpForFetchRows
     *
     * @param int|string $keyField
     * @param int $type
     * @param array $expectedRows
     */
    public function testFetchRows($keyField, int $type, array $expectedRows)
    {
        $result = $this->db->select('*', 'groups')
            ->limit(2)
            ->execute();

        self::assertEquals($expectedRows, $result->fetchRows($keyField, $type));
    }

    /**
     * @return array
     */
    public function dpForFetchRows(): array
    {
        return [
            'simple assoc' => [
                'keyField' => null,
                'type' => Result::ASSOC,
                'expectedRows' =>  [
                    ['id' => '1', 'name' => 'Admin'],
                    ['id' => '2', 'name' => 'Guest'],
                ]
            ],
            'assoc with key -> id' => [
                'keyField' => 'id',
                'type' => Result::ASSOC,
                'expectedRows' =>  [
                    '1' => ['id' => '1', 'name' => 'Admin'],
                    '2' => ['id' => '2', 'name' => 'Guest'],
                ]
            ],
            'assoc with key -> name' => [
                'keyField' => 'name',
                'type' => Result::ASSOC,
                'expectedRows' =>  [
                    'Admin' => ['id' => '1', 'name' => 'Admin'],
                    'Guest' => ['id' => '2', 'name' => 'Guest'],
                ]
            ],
            'numerical with key -> 0' => [
                'keyField' => 0,
                'type' => Result::NUM,
                'expectedRows' =>  [
                    '1' => [0 => '1', 1 => 'Admin'],
                    '2' => [0 => '2', 1 => 'Guest'],
                ]
            ],
            'numerical with key -> 1' => [
                'keyField' => 1,
                'type' => Result::NUM,
                'expectedRows' =>  [
                    'Admin' => [0 => '1', 1 => 'Admin'],
                    'Guest' => [0 => '2', 1 => 'Guest'],
                ]
            ]
        ];
    }

    /**
     * @dataProvider dpForFetchList
     *
     * @param string[]|null $selectFields
     * @param int|string $field
     * @param int|string $keyField
     * @param $expectedList
     */
    public function testFetchList(?array $selectFields, $field, $keyField, $expectedList)
    {
        $result = $this->db->select($selectFields, 'groups')
            ->limit(2)
            ->execute();

        self::assertEquals($expectedList, $result->fetchList($field, $keyField));
    }

    /**
     * @return array
     */
    public function dpForFetchList(): array
    {
        return [
            'without args' => [
                'selectFields' => ['name'],
                'field' => null,
                'keyField' => null,
                'expectedList' => ['Admin', 'Guest']
            ],
            'with field -> name' => [
                'selectFields' => null,
                'field' => 'name',
                'keyField' => null,
                'expectedList' => ['Admin', 'Guest']
            ],
            'with field -> id' => [
                'selectFields' => null,
                'field' => 'id',
                'keyField' => null,
                'expectedList' => ['1', '2']
            ],
            'with field and key' => [
                'selectFields' => null,
                'field' => 'name',
                'keyField' => 'id',
                'expectedList' => ['1' => 'Admin', '2' => 'Guest']
            ]
        ];
    }

    /**
     * @dataProvider dpForTestGetNumRows
     *
     * @param int $limit
     */
    public function testGetNumRows(int $limit)
    {
        $result = $this->db->select(null, 'groups')
            ->limit($limit)
            ->execute();

        self::assertSame($limit, $result->getNumRows());
    }

    /**
     * @return array
     */
    public function dpForTestGetNumRows(): array
    {
        return [[1], [4]];
    }

    /**
     * @dataProvider dpForGetFieldCount
     *
     * @param string[] $selectFields
     * @param int $expected
     */
    public function testGetFieldCount(array $selectFields, int $expected)
    {
        $result = $this->db->select($selectFields, 'groups')
            ->limit(1)
            ->execute();

        self::assertSame($expected, $result->getFieldCount());
    }

    /**
     * @return array
     */
    public function dpForGetFieldCount(): array
    {
        return [
            [
                'selectFields' => ['id'],
                'expected' => 1
            ],
            [
                'selectFields' => ['id', 'name'],
                'expected' => 2
            ],
        ];
    }

    public function testGetFoundRows()
    {
        $expected = 5; //rows in dataset

        $result = $this->db->select(null, 'groups')
            ->limit(2)
            ->useFoundRows()
            ->execute();

        self::assertSame($expected, $result->getFoundRows());
    }

    /**
     * Make sure the SQL_CALC_FOUND_ROWS query modifier and accompanying FOUND_ROWS() function
     * replacement returns the same result when dealing with a query with a 'GROUP BY' clause.
     */
    public function testGetFoundRowsWithGroupBy()
    {
        // 5 rows in dataset, but 4 different values for name. 'Clanleader' exists two times.
        $expected = 4;

        $result = $this->db->select(null, 'groups')
            ->group(['name'])
            ->limit(2)
            ->useFoundRows()
            ->execute();

        self::assertSame($expected, $result->getFoundRows());
    }

    public function testSetCurrentRow()
    {
        $result = $this->db->select(['name'], 'groups')
            ->limit(2)
            ->execute();

        $result->setCurrentRow(1);

        self::assertSame('Guest', $result->fetchCell());
    }

    public function testGetMysqliResult()
    {
        $result = $this->db->select(['name'], 'groups')
            ->limit(2)
            ->execute();

        $mysqliResult = $result->getMysqliResult();

        $expectedRows = [
            ['name' => 'Admin'],
            ['name' => 'Guest'],
        ];

        foreach ($mysqliResult as $key => $row) {
            self::assertSame($expectedRows[$key], $row);
        }
    }
}
