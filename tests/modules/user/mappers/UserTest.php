<?php
/**
 * Holds class Modules_User_Mappers_UserTest.
 *
 * @author Jainta Martin
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

use User\Mappers\User as UserMapper;
use User\Models\User as UserModel;
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
	 * Filling the timezone which the Ilch_Date object will use.
	 *
	 * @var Array
	 */
	protected $_configData = array
	(
		'timezone' => 'Europe/Berlin'
	);

	/**
	 * Groups which will be returned by the mock object when the user mapper tries to get its groups.
	 *
	 * @var mixed[]
	 */
	protected $_groupRows = array
	(
		array
		(
			'id' => 1,
			'name' => 'Administrator',
		),
		array
		(
			'id' => 2,
			'name' => 'Member',
		),
	);

	/**
	 * A part of the sql which checks if groups for a user exist.
	 *
	 * @var string
	 */
	protected $_groupSqlPart = 'SELECT g.*
						FROM [prefix]_groups AS g
						INNER JOIN [prefix]_users_groups AS ug ON ug.user_id = ';

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
		$dbMock = $this->getMock('Ilch_Database', array('selectArray', 'queryArray'));
		$dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'users',
					   $where)
				->will($this->returnValue($userRows));
		$dbMock->expects($this->once())
				->method('queryArray')
				->with($this->stringContains($this->_groupSqlPart))
				->will($this->returnValue($this->_groupRows));
		$mapper = new UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->getUserById(2);

		$this->assertTrue($user !== false);
		$this->assertEquals(2, $user->getId());
		$this->assertEquals('testmail2@test.de', $user->getEmail());
		$this->assertEquals('testUsername2', $user->getName());
		$this->assertEquals(1378160032, $user->getDateCreated()->getTimestamp());
		$this->assertEquals(1378160145, $user->getDateConfirmed()->getTimestamp());
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
		$dbMock = $this->getMock('Ilch_Database', array('selectArray', 'queryArray'));
		$dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'users',
					   $where)
				->will($this->returnValue($userRows));
		$dbMock->expects($this->once())
				->method('queryArray')
				->with($this->stringContains($this->_groupSqlPart))
				->will($this->returnValue($this->_groupRows));
		$mapper = new UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->getUserByName('testUsername2');

		$this->assertTrue($user !== false);
		$this->assertEquals(2, $user->getId());
		$this->assertEquals('testmail2@test.de', $user->getEmail());
		$this->assertEquals('testUsername2', $user->getName());
		$this->assertEquals(1378160032, $user->getDateCreated()->getTimestamp());
		$this->assertEquals(1378160145, $user->getDateConfirmed()->getTimestamp());
	}

	/**
	 * Tests if the user mapper returns the right user model using an email for the search.
	 */
	public function testGetUserByEmail()
	{
		$passwordHash = crypt('password');
		$userRows = array
		(
			array
			(
				'id' => 2,
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'password' => $passwordHash,
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			),
		);
		$where = array
		(
			'email' => 'testmail2@test.de',
		);
		$dbMock = $this->getMock('Ilch_Database', array('selectArray', 'queryArray'));
		$dbMock->expects($this->once())
				->method('selectArray')
				->with('*',
					   'users',
					   $where)
				->will($this->returnValue($userRows));
		$dbMock->expects($this->once())
				->method('queryArray')
				->with($this->stringContains($this->_groupSqlPart))
				->will($this->returnValue($this->_groupRows));
		$mapper = new UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->getUserByEmail('testmail2@test.de');

		$this->assertTrue($user !== false);
		$this->assertEquals(2, $user->getId());
		$this->assertEquals('testmail2@test.de', $user->getEmail());
		$this->assertEquals('testUsername2', $user->getName());
		$this->assertEquals($passwordHash, $user->getPassword());
		$this->assertEquals(1378160032, $user->getDateCreated()->getTimestamp());
		$this->assertEquals(1378160145, $user->getDateConfirmed()->getTimestamp());
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
			'password' => 'testPassword2Modified',
			'date_created' => '2013-08-20 22:20:20',
			'date_confirmed' => '2013-08-20 22:20:30',
		);
		$dbMock = $this->getMock('Ilch_Database', array('insert', 'selectCell'));
		$dbMock->expects($this->once())
				->method('insert')
				->with($newUser,
					   'users')
				->will($this->returnValue(null));
		$dbMock->expects($this->once())
				->method('selectCell')
				->with('id',
					   'users',
					   array('id' => null))
				->will($this->returnValue(0));
		$mapper = new UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->loadFromArray
		(
			array
			(
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'password' => 'testPassword2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			)
		);
		$user->setName('testUsername2Modified');
		$user->setPassword('testPassword2Modified');
		$user->setEmail('testmail2@test.deModified');
		$user->setDateCreated(1377037220);
		$user->setDateConfirmed(1377037230);

		$mapper->save($user);
	}

	/**
	 * Tests if a user gets updated if the user already exists.
	 */
	public function testSaveUpdateUser()
	{
		$modifiedUser = array
		(
			'email' => 'testmail2@test.deModified',
			'name' => 'testUsername2Modified',
			'password' => 'testPassword2Modified',
			'date_created' => '2013-08-20 22:20:20',
			'date_confirmed' => '2013-08-20 22:20:30',
		);

		$dbMock = $this->getMock('Ilch_Database', array('selectArray', 'update', 'selectCell'));
		$dbMock->expects($this->once())
				->method('update')
				->with($modifiedUser,
					   'users',
					   array('id' => 2))
				->will($this->returnValue(null));
		$dbMock->expects($this->once())
				->method('selectCell')
				->with('id',
					   'users',
					   array('id' => 2))
				->will($this->returnValue(2));
		$mapper = new UserMapper();
		$mapper->setDatabase($dbMock);
		$user = $mapper->loadFromArray
		(
			array
			(
				'id' => 2,
				'email' => 'testmail2@test.de',
				'name' => 'testUsername2',
				'password' => 'testPassword2',
				'date_created' => '2013-09-02 22:13:52',
				'date_confirmed' => '2013-09-02 22:15:45',
			)
		);
		$user->setName('testUsername2Modified');
		$user->setPassword('testPassword2Modified');
		$user->setEmail('testmail2@test.deModified');
		$user->setDateCreated(1377037220);
		$user->setDateConfirmed(1377037230);

		$mapper->save($user);
	}

	/**
	 * Tests if a user can be modified by creating an empty user model with its id
	 * and modifying only one attribute.
	 */
	public function testExistingUserWithEmptyModel()
	{
		$userUpdateArr = array
		(
			'name' => 'newName',
		);

		$dbMock = $this->getMock('Ilch_Database', array('selectArray', 'update', 'selectCell'));
		$dbMock->expects($this->once())
				->method('update')
				->with($userUpdateArr,
					   'users',
					   array('id' => 1))
				->will($this->returnValue(null));
		$dbMock->expects($this->once())
				->method('selectCell')
				->with('id',
					   'users',
					   array('id' => 1))
				->will($this->returnValue(1));

		$user = new UserModel();
		$user->setId(1);
		$user->setName('newName');

		$mapper = new UserMapper();
		$mapper->setDatabase($dbMock);
		$mapper->save($user);
	}
}