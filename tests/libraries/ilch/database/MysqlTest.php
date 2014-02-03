<?php
/**
 * Holds class Libraries_Ilch_DatabaseMysqlTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

use Ilch\Database\Mysql as MySQL;

defined('ACCESS') or die('no direct access');

/**
 * Tests the MySQL database object.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */
class Libraries_Ilch_DatabaseMysqlTest extends PHPUnit_Ilch_DatabaseTestCase
{
    /**
     * Returns the initial dataset for the db.
     *
     * @return PHPUnit_Extensions_Database_DataSet_YamlDataSet
     */
    protected function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet
        (
            __DIR__.'/../_files/mysql_database.yml'
        );
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching by a normal fieldname.
     */
    public function testSelectCellNormalField()
    {
        $result = $this->db->selectCell('id')
                ->from('groups')
                ->where(array('id' => 2))
                ->execute();

        $this->assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching with a MySQL function.
     */
    public function testSelectCellWithCount()
    {
        $result = $this->db->selectCell('COUNT(*)')
            ->from('groups')
            ->where(array('name' => 'Clanleader'))
            ->execute();

        $this->assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if an update with an empty value can be done.
     */
    public function testUpdateWithEmptyValue()
    {
        $this->db->update('groups')
            ->fields(array('name' => ''))
            ->where(array('id' => 2));

        $result = $this->db->selectCell('name')
            ->from('groups')
            ->where(array('id' => 2))
            ->execute();

        $this->assertEquals('', $result, 'The db entry has not being updated with an empty string.');
    }

    /**
     * Tests if an insertion with an empty value can be done.
     */
    public function testInsertWithEmptyValue()
    {
        $this->db->insert('groups')->fields(array('name' => ''))->execute();

        $result = $this->db->selectCell('COUNT(*)')
            ->from('groups')
            ->where(array('name' => ''))
            ->execute();

        $this->assertEquals(1, $result, 'The db entry has not being inserted with an empty string.');
    }
}
