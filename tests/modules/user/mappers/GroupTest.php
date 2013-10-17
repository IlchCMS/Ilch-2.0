<?php
/**
 * Holds class Modules_User_Mappers_GroupTest.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch_phpunit
 */

use \User\Mappers\Group as GroupMapper;
defined('ACCESS') or die('no direct access');

/**
 * Tests the user group mapper class.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Modules_User_Mappers_GroupTest extends PHPUnit_Ilch_TestCase
{
	/**
	 * A mock for the Ilch_Database.
	 *
	 * @var Ilch_Database
	 */
	private $_dbMock;

	/**
	 * Sets the db mock.
	 */
	public function setUp()
	{
		$this->_dbMock = $this->getMock('Ilch_Database', array('selectArray', 'selectList'));
	}

	/**
	 * Tests if the user group mapper returns the right user group model using an
	 * id for the search.
	 */
	public function testGetGroupById()
	{
		$groupRow = array
		(
			array
			(
				'id' => 2,
				'name' => 'Guest',
			),
		);

		$where = array
		(
			'id' => 2,
		);

		$this->_dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'groups',
					   $where)
				->will($this->returnValue($groupRow));
		$mapper = new GroupMapper();
		$mapper->setDatabase($this->_dbMock);
		$group = $mapper->getById(2);

		$this->assertTrue($group !== false);
		$this->assertEquals(2, $group->getId());
		$this->assertEquals('Guest', $group->getName());
	}

	/**
	 * Tests if the user group mapper returns the right user group model using an
	 * name for the search.
	 */
	public function testGetGroupByName()
	{
		$groupRow = array
		(
			array
			(
				'id' => 1,
				'name' => 'Administrator',
			),
		);

		$where = array
		(
			'name' => 'Administrator',
		);

		$this->_dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'groups',
					   $where)
				->will($this->returnValue($groupRow));
		$mapper = new GroupMapper();
		$mapper->setDatabase($this->_dbMock);
		$group = $mapper->getByName('Administrator');

		$this->assertTrue($group !== false);
		$this->assertEquals(1, $group->getId());
		$this->assertEquals('Administrator', $group->getName());
	}

	/**
	 * Tests if the user group mapper returns the right user group model using an
	 * data array.
	 */
	public function testLoadFromArray()
	{
		$groupRow = array
		(
			'id' => 1,
			'name' => 'Administrator',
		);

		$mapper = new GroupMapper();
		$group = $mapper->loadFromArray($groupRow);

		$this->assertTrue($group !== false);
		$this->assertEquals(1, $group->getId());
		$this->assertEquals('Administrator', $group->getName());
	}

	/**
	 * Tests if the user associations of the group can be loaded.
	 */
	public function testGetUsersForGroup()
	{
		$this->_dbMock->expects($this->once())
				->method('selectList')
				->with('user_id',
						'users_groups',
						array('group_id' => 1))
				->will($this->returnValue(array(1, 3 ,4)));
		$mapper = new GroupMapper();
		$mapper->setDatabase($this->_dbMock);
		$groupIds = $mapper->getUsersForGroup(1);

		$this->assertEquals(array(1, 3, 4), $groupIds);
	}
}