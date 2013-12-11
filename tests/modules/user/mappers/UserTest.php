<?php
/**
 * Holds class Modules_User_Mappers_UserTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

use User\Mappers\User as UserMapper;
use User\Models\User as UserModel;

defined('ACCESS') or die('no direct access');

/**
 * Tests the user mapper class.
 *
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
                        INNER JOIN [prefix]_users_groups AS ug ON g.id = ug.group_id
                        WHERE ug.user_id = ';

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
                'date_last_activity' => '2013-10-22 14:23:54',
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
        $this->assertEquals(1382451834, $user->getDateLastActivity()->getTimestamp());
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
                'date_last_activity' => '2013-10-22 14:23:54',
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
        $this->assertEquals(1382451834, $user->getDateLastActivity()->getTimestamp());
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
                'date_last_activity' => '2013-10-22 14:23:54',
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
        $this->assertEquals(1382451834, $user->getDateLastActivity()->getTimestamp());
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
            'date_last_activity' => '2013-10-14 13:13:13',
        );
        $dbMock = $this->getMock('Ilch_Database', array('insert', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('insert')
                ->with($newUser,
                       'users')
                ->will($this->returnValue(3));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('id',
                       'users',
                       array('id' => null))
                ->will($this->returnValue(0));
        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);
        $user = $mapper->loadFromArray($newUser);

        $insertId = $mapper->save($user);
        $this->assertEquals(3, $insertId, 'The wrong insert id was returned.');
    }

    /**
     * Tests if a user gets updated if the user already exists.
     */
    public function testSaveUpdateUser()
    {
        $modifyUser = array
        (
            'email' => 'testmail2@test.deModified',
            'name' => 'testUsername2Modified',
            'password' => 'testPassword2Modified',
            'date_created' => '2013-08-20 22:20:20',
            'date_confirmed' => '2013-08-20 22:20:30',
            'date_last_activity' => '2013-10-14 13:13:13',
        );
        $modifiedUser = $modifyUser;
        $modifiedUser['id'] = 2;

        $dbMock = $this->getMock('Ilch_Database', array('selectArray', 'update', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('update')
                ->with($modifyUser,
                       'users',
                       array('id' => 2))
                ->will($this->returnValue(2));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('id',
                       'users',
                       array('id' => 2))
                ->will($this->returnValue(2));
        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);
        $user = $mapper->loadFromArray($modifiedUser);

        $updateId = $mapper->save($user);
        $this->assertEquals(2, $updateId, 'The wrong update id was returned.');
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

    /**
     * Tests if a userlist can be returned correctly.
     */
    public function testGetUserList()
    {
        $userRows = array
        (
            array
            (
                'id' => 1,
                'email' => 'testmail1@test.de',
                'name' => 'testUsername1',
                'date_created' => '2013-09-01 22:13:52',
                'date_confirmed' => '2013-09-01 22:15:45',
                'date_last_activity' => '2013-10-14 13:13:13',
            ),
            array
            (
                'id' => 2,
                'email' => 'testmail2@test.de',
                'name' => 'testUsername2',
                'date_created' => '2013-09-02 22:13:52',
                'date_confirmed' => '2013-09-02 22:15:45',
                'date_last_activity' => '2013-10-22 14:23:54',
            ),
            array
            (
                'id' => 3,
                'email' => 'testmail3@test.de',
                'name' => 'testUsername3',
                'date_created' => '2013-09-03 22:13:52',
                'date_confirmed' => '2013-09-03 22:15:45',
                'date_last_activity' => '2013-10-24 20:18:36',
            ),
        );

        $dbMock = $this->getMock('Ilch_Database', array('selectArray', 'queryArray'));
        $dbMock->expects($this->once())
                ->method('selectArray')
                ->with('*',
                    'users')
                ->will($this->returnValue($userRows));
        $dbMock->expects($this->at(1))
                ->method('queryArray')
                ->with('SELECT g.*
                        FROM [prefix]_groups AS g
                        INNER JOIN [prefix]_users_groups AS ug ON g.id = ug.group_id
                        WHERE ug.user_id = 1')
                ->will($this->returnValue(array(
                    'id' => 1,)));
        $dbMock->expects($this->at(2))
                ->method('queryArray')
                ->with('SELECT g.*
                        FROM [prefix]_groups AS g
                        INNER JOIN [prefix]_users_groups AS ug ON g.id = ug.group_id
                        WHERE ug.user_id = 2')
                ->will($this->returnValue(array(
                    'id' => 2,
                    'id' => 3,)));
        $dbMock->expects($this->at(3))
                ->method('queryArray')
                ->with('SELECT g.*
                        FROM [prefix]_groups AS g
                        INNER JOIN [prefix]_users_groups AS ug ON g.id = ug.group_id
                        WHERE ug.user_id = 3')
                ->will($this->returnValue(array(
                    'id' => 2,
                    'id' => 3,
                    'id' => 4,)));
        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);
        $userList = $mapper->getUserList();

        $this->assertCount(3, $userList, 'It was not created exactly one user object for each user.');

        foreach ($userList as $key => $user) {
            $this->assertInstanceOf('User\Models\User', $user, 'The user with array key "'.$key.'" was not saved as a user model.');
        }
    }

    /**
     * Tests if an existing user with a specific id can be found.
     */
    public function testUserWithIdExistsDoesExist()
    {
        $dbMock = $this->getMock('Ilch_Database', array('selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('id',
                       'users',
                       array('id' => 3))
                ->will($this->returnValue(3));
        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);

        $this->assertTrue($mapper->userWithIdExists(3), 'The user was not found but it does exist.');
    }

    /**
     * Tests if a not existing user with a specific id can be found.
     */
    public function testUserWithIdExistsDoesNotExist()
    {
        $dbMock = $this->getMock('Ilch_Database', array('selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('id',
                       'users',
                       array('id' => 3))
                ->will($this->returnValue(null));
        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);

        $this->assertFalse($mapper->userWithIdExists(3), 'The user was found but it does not exist.');
    }

    /**
     * Tests if a user can be deleted using a user object.
     */
    public function testDeleteUserByObject()
    {
        $dbMock = $this->getMock('Ilch_Database', array('delete'));
        $dbMock->expects($this->at(0))
                ->method('delete')
                ->with('users_groups',
                       array('user_id' => 3))
                ->will($this->returnValue(true));

        $dbMock->expects($this->at(1))
                ->method('delete')
                ->with('users',
                       array('id' => 3))
                ->will($this->returnValue(true));
        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);

        $user = new UserModel();
        $user->setId(3);

        $this->assertTrue($mapper->delete($user), 'The user does not got deleted successfully.');
    }

    /**
     * Tests if a user can be deleted using a integer.
     */
    public function testDeleteUserById()
    {
        $dbMock = $this->getMock('Ilch_Database', array('delete'));
        $dbMock->expects($this->at(0))
                ->method('delete')
                ->with('users_groups',
                       array('user_id' => 3))
                ->will($this->returnValue(true));

        $dbMock->expects($this->at(1))
                ->method('delete')
                ->with('users',
                       array('id' => 3))
                ->will($this->returnValue(true));

        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);

        $this->assertTrue($mapper->delete(3), 'The user does not got deleted successfully.');
    }
}
