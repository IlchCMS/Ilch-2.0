<?php
/**
 * Holds class Modules_User_Models_UserTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

use User\Models\User as UserModel;
use User\Models\Group as GroupModel;

defined('ACCESS') or die('no direct access');

/**
 * Tests the user model class.
 *
 * @package ilch_phpunit
 */
class Modules_User_Models_UserTest extends PHPUnit_Ilch_TestCase
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
     * Tests if the user id can be set and returned again.
     */
    public function testSetGetId()
    {
        $user = new UserModel();
        $user->setId(123);
        $this->assertEquals(123, $user->getId(), 'The id wasnt saved or returned correctly.');
    }

    /**
     * Tests if the username can be set and returned again.
     */
    public function testSetGetUsername()
    {
        $user = new UserModel();
        $user->setName('username');
        $this->assertEquals('username', $user->getName(), 'The username wasnt saved or returned correctly.');
    }

    /**
     * Tests if the email address can be set and returned again.
     */
    public function testSetGetEmail()
    {
        $user = new UserModel();
        $user->setEmail('email');
        $this->assertEquals('email', $user->getEmail(), 'The email wasnt saved or returned correctly.');
    }

    /**
     * Tests if the user groups can be set and returned again.
     */
    public function testSetGetGroups()
    {
        $group1 = new GroupModel();
        $group1->setId(1);
        $group1->setName('Admin');
        $group2 = new GroupModel();
        $group2->setId(2);
        $group2->setName('Member');
        $group3 = new GroupModel();
        $group3->setId(3);
        $group3->setName('Clanleader');
        $user = new UserModel();
        $user->setGroups(array($group1, $group2, $group3));
        $this->assertEquals(array($group1, $group2, $group3), $user->getGroups(), 'The user groups wasnt saved or returned correctly.');
    }

    /**
     * Tests if its possible to add groups.
     */
    public function testAddGroup()
    {
        $group1 = new GroupModel();
        $group1->setId(1);
        $group1->setName('Admin');
        $group2 = new GroupModel();
        $group2->setId(2);
        $group2->setName('Member');
        $group3 = new GroupModel();
        $group3->setId(3);
        $group3->setName('Clanleader');
        $user = new UserModel();
        $user->setGroups(array($group1, $group3));
        $user->addGroup($group2);
        $this->assertEquals(array($group1, $group3, $group2), $user->getGroups(), 'The user groups wasnt added or returned correctly.');
    }

    /**
     * Tests if user has the given groups.
     */
    public function testHasGroup()
    {
        $user = new UserModel();

        $group1 = new GroupModel();
        $group1->setId(1);
        $group1->setName('Admin');

        $group2 = new GroupModel();
        $group2->setId(2);
        $group2->setName('Member');

        $group3 = new GroupModel();
        $group3->setId(3);
        $group3->setName('Clanleader');

        $user->addGroup($group1)->addGroup($group2)->addGroup($group3);

        $this->assertTrue($user->hasGroup(2), 'The user has not the group with id "2".');
        $this->assertFalse($user->hasGroup(4), 'The user has the group with id "4".');

        $user->setGroups(array());
        $this->assertFalse($user->hasGroup(2), 'The user has the group with id "2".');
    }

    /**
     * Tests if the access for a user can be returned.
     */
    public function testHasAccess()
    {
        $group = new GroupModel();
        $group->setId(3);
        $group->setName('Testgroup');
        $user = new UserModel();
        $user->setId(123);
        $user->addGroup($group);

        $dbMock = $this->getMock('Ilch_Database', array('queryCell'));
        $dbMock->expects($this->once())
                ->method('queryCell')
                ->with($this->logicalAnd($this->stringContains('FROM [prefix]_groups_access'), $this->stringContains('INNER JOIN [prefix]_modules'), $this->stringContains('user')))
                ->will($this->returnValue('0'));
        \Ilch\Registry::set('db', $dbMock);

        $this->assertEquals(0, $user->hasAccess('module_user'));
    }
}
