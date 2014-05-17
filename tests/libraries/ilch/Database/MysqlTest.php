<?php
/**
 * Holds class Ilch\Database\MysqlTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Ilch\Database;

use Ilch\Database\Mysql as MySQL;

defined('ACCESS') or die('no direct access');

/**
 * Tests the MySQL database object.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */
class MysqlTest extends \PHPUnit_Ilch_DatabaseTestCase
{
    /**
     * Returns the initial dataset for the db.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_YamlDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            __DIR__ . '/../_files/mysql_database.yml'
        );
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching by a normal fieldname.
     */
    public function testSelectCellNormalField()
    {
        $result = $this->db->select('id')
            ->from('groups')
            ->where(array('id' => 2))
            ->execute()
            ->fetchCell();

        $this->assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching with a MySQL function.
     */
    public function testSelectCellWithCount()
    {
        $result = $this->db->select('COUNT(*)')
            ->from('groups')
            ->where(array('name' => 'Clanleader'))
            ->execute()
            ->fetchCell();

        $this->assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if an update with an empty value can be done.
     */
    public function testUpdateWithEmptyValue()
    {
        $this->db->update('groups')
            ->fields(array('name' => ''))
            ->where(array('id' => 2))
            ->execute();

        $result = $this->db->select('name')
            ->from('groups')
            ->where(array('id' => 2))
            ->execute()
            ->fetchCell();

        $this->assertEquals('', $result, 'The db entry has not being updated with an empty string.');
    }

    /**
     * Tests if an insertion with an empty value can be done.
     */
    public function testInsertWithEmptyValue()
    {
        $this->db->insert('groups')->fields(array('name' => ''))->execute();

        $result = $this->db->select('COUNT(*)')
            ->from('groups')
            ->where(array('name' => ''))
            ->execute()
            ->fetchCell();

        $this->assertEquals(1, $result, 'The db entry has not being inserted with an empty string.');
    }
}
