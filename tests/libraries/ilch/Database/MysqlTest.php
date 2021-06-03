<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database;

use Ilch\Database\Mysql as MySQL;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the MySQL database object.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */
class MysqlTest extends \PHPUnit\Ilch\DatabaseTestCase
{
    protected $phpunitDataset;

    public function setUp()
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching by a normal fieldname.
     */
    public function testSelectCellNormalField()
    {
        $result = $this->db->select('id')
            ->from('groups')
            ->where(['id' => 2])
            ->execute()
            ->fetchCell();

        self::assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching with a MySQL function.
     */
    public function testSelectCellWithCount()
    {
        $result = $this->db->select('COUNT(*)')
            ->from('groups')
            ->where(['name' => 'Clanleader'])
            ->execute()
            ->fetchCell();

        self::assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if an update with an empty value can be done.
     */
    public function testUpdateWithEmptyValue()
    {
        $this->db->update('groups')
            ->values(['name' => ''])
            ->where(['id' => 2])
            ->execute();

        $result = $this->db->select('name')
            ->from('groups')
            ->where(['id' => 2])
            ->execute()
            ->fetchCell();

        self::assertEquals('', $result, 'The db entry has not being updated with an empty string.');
    }

    /**
     * Tests if an insertion with an empty value can be done.
     */
    public function testInsertWithEmptyValue()
    {
        $this->db->insert('groups')->values(['name' => ''])->execute();

        $result = $this->db->select('COUNT(*)')
            ->from('groups')
            ->where(['name' => ''])
            ->execute()
            ->fetchCell();

        self::assertEquals(1, $result, 'The db entry has not being inserted with an empty string.');
    }
}
