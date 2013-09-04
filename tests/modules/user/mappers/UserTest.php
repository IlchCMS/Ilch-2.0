<?php
/**
 * Holds class Modules_User_Mappers_UserTest.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
 * @package ilch_phpunit
 */

defined('ACCESS') or die('no direct access');

/**
 * Tests the user mapper class.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Modules_User_Mappers_UserTest extends PHPUnit_Ilch_TestCase
{
	/**
	 * Tests if the user mapper returns the right user model using an id for the
	 * search.
	 */
	public function testGetUserById()
	{
		$userRows = array
		(
			array
			(
				'id' => 2,
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			),
		);
		$where = array
		(
			'id' => 2
		);
		$dbMock = $this->getMock('Ilch_Database', array('selectArray'));
		$dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'users',
					   $where)
				->will($this->returnValue($userRows));
		$mapper = new User_UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->getUserById(2);

		$this->assertTrue($user !== false);
		$this->assertEquals(2, $user->getId());
		$this->assertEquals('testmail2@test.de', $user->getEmail());
		$this->assertEquals('testUsername2', $user->getName());
		$this->assertEquals(1378152832, $user->getDateCreated());
		$this->assertEquals(1378152945, $user->getDateConfirmed());
	}

	/**
	 * Tests if the user mapper returns the right user model using a name for the
	 * search.
	 */
	public function testGetUserByName()
	{
		$userRows = array
		(
			array
			(
				'id' => 2,
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			),
		);
		$where = array
		(
			'name' => 'testUsername2'
		);
		$dbMock = $this->getMock('Ilch_Database', array('selectArray'));
		$dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'users',
					   $where)
				->will($this->returnValue($userRows));
		$mapper = new User_UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->getUserByName('testUsername2');

		$this->assertTrue($user !== false);
		$this->assertEquals(2, $user->getId());
		$this->assertEquals('testmail2@test.de', $user->getEmail());
		$this->assertEquals('testUsername2', $user->getName());
		$this->assertEquals(1378152832, $user->getDateCreated());
		$this->assertEquals(1378152945, $user->getDateConfirmed());
	}

	/**
	 * Tests if a user gets inserted if it is a new one.
	 */
	public function testSaveInsertUser()
	{
		$newUser = array
		(
			'email' => 'testmail2@test.deModified',
			'name' => 'testUsername2Modified',
			'date_created' => 1377037220,
			'date_confirmed' => 1377037230,
		);
		$dbMock = $this->getMock('Ilch_Database', array('insert'));
		$dbMock->expects($this->once())
				->method('insert')
				->with($newUser,
					   'users')
				->will($this->returnValue(null));
		$mapper = new User_UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->loadFromArray
		(
			array
			(
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			)
		);
		$user->setName('testUsername2Modified');
		$user->setEmail('testmail2@test.deModified');
		$user->setDateCreated(1377037220);
-		$user->setDateConfirmed(1377037230);

		$mapper->save($user);
	}

	/**
	 * Tests if a user gets updated if the user already exists.
	 */
	public function testSaveUpdateUser()
	{
		/*
		 * Current rows in the db.
		 */
		$userRows = array
		(
			array
			(
				'id' => 1,
				'email' => 'testmail1@test.de',
				'name' => 'testUsername1',
				'date_created' => '2013-08-02 20:12:42',
				'date_confirmed' => '2013-08-12 22:23:52',
			),
			array
			(
				'id' => 2,
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			),
		);
		$modifiedUser = array
		(
			'email' => 'testmail2@test.deModified',
			'name' => 'testUsername2Modified',
			'date_created' => 1377037220,
			'date_confirmed' => 1377037230,
		);

		$dbMock = $this->getMock('Ilch_Database', array('selectArray', 'update'));
		$dbMock->expects($this->once())
				->method('selectArray')
				->will($this->returnValue($userRows));
		$dbMock->expects($this->once())
				->method('update')
				->with($modifiedUser,
					   'users',
					   array('id' => 2))
				->will($this->returnValue(null));
		$mapper = new User_UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->loadFromArray
		(
			array
			(
				'id' => 2,
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			)
		);
		$user->setName('testUsername2Modified');
		$user->setEmail('testmail2@test.deModified');
		$user->setDateCreated(1377037220);
-		$user->setDateConfirmed(1377037230);

		$mapper->save($user);
	}
}