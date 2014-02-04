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
     * Tests if a user can be deleted using a user object.
     */
    public function testDeleteUserByObject()
    {
        $dbMock = $this->getMock('Ilch_Database', array('delete', 'where', 'execute'));
        $dbMock->expects($this->at(0))
                ->method('delete')
                ->with('users_groups')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(1))
                ->method('where')
                ->with(array('user_id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(2))
                ->method('execute')
                ->will($this->returnValue(true));

        $dbMock->expects($this->at(3))
                ->method('delete')
                ->with('users')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(4))
                ->method('where')
                ->with(array('id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(5))
                ->method('execute')
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
        $dbMock = $this->getMock('Ilch_Database', array('delete', 'where', 'execute'));
        $dbMock->expects($this->at(0))
                ->method('delete')
                ->with('users_groups')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(1))
                ->method('where')
                ->with(array('user_id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(2))
                ->method('execute')
                ->will($this->returnValue(true));

        $dbMock->expects($this->at(3))
                ->method('delete')
                ->with('users')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(4))
                ->method('where')
                ->with(array('id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(5))
                ->method('execute')
                ->will($this->returnValue(true));

        $mapper = new UserMapper();
        $mapper->setDatabase($dbMock);

        $this->assertTrue($mapper->delete(3), 'The user does not got deleted successfully.');
    }
}
