<?php
/**
 * Holds class Modules_User_Mappers_GroupTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

use \User\Mappers\Group as GroupMapper;
use \User\Models\Group as GroupModel;
defined('ACCESS') or die('no direct access');

/**
 * Tests the user group mapper class.
 *
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
        $group = $mapper->getGroupById(2);

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
        $group = $mapper->getGroupByName('Administrator');

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

    /**
     * Tests if a grouplist can be returned correctly.
     */
    public function testGetGroupList()
    {
        $groupRows = array
        (
            array
            (
                'id' => 1,
                'name' => 'Admin',
            ),
            array
            (
                'id' => 2,
                'name' => 'Moderator',
            ),
            array
            (
                'id' => 3,
                'name' => 'Member',
            ),
        );

        $dbMock = $this->getMock('Ilch_Database', array('selectArray', 'queryArray'));
        $dbMock->expects($this->once())
                ->method('selectArray')
                ->with('*',
                    'groups')
                ->will($this->returnValue($groupRows));
        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);
        $groupList = $mapper->getGroupList();

        $this->assertCount(3, $groupList, 'It was not created exactly one group object for each group.');

        foreach ($groupList as $key => $group) {
            $this->assertInstanceOf('User\Models\Group', $group, 'The group with array key "'.$key.'" was not saved as a group model.');
        }
    }

    /**
     * Tests if a group with a certain id exists in the db.
     */
    public function testGroupWithIdExists()
    {
        $dbMock = $this->getMock('Ilch_Database', array('selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('COUNT(*)',
                    'groups',
                    array('id' => 3))
                ->will($this->returnValue('1'));
        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);

        $this->assertTrue($mapper->groupWithIdExists(3), 'The existing group could not be found.');
    }

    /**
     * Tests if the save function returns the inserted group id.
     */
    public function testGroupSaveReturnsIdOnCreate()
    {
        $group = new GroupModel();
        $group->setName('New Group');
        $rec = array('name' => 'New Group');
        $dbMock = $this->getMock('Ilch_Database', array('insert', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('insert')
                ->with($rec, 'groups')
                ->will($this->returnValue(3));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('id', 'groups', array('id' => 0))
                ->will($this->returnValue(0));
        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);

        $this->assertEquals(3, $mapper->save($group), 'The save function does not return the newly inserted group id.');
    }

    /**
     * Tests if the save function returns an updated group´s id.
     */
    public function testGroupSaveReturnsIdOnUpdate()
    {
        $group = new GroupModel();
        $group->setId(3);
        $group->setName('Old Group');
        $rec = array('name' => 'Old Group');
        $dbMock = $this->getMock('Ilch_Database', array('update', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('update')
                ->with($rec, 'groups');
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('id', 'groups', array('id' => 3))
                ->will($this->returnValue(3));
        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);

        $this->assertEquals(3, $mapper->save($group), 'The save function does not return the group id of an updated group.');
    }

    /**
     * Tests if a group can be deleted using an object.
     */
    public function testDeleteGroupByObject()
    {
        $dbMock = $this->getMock('Ilch_Database', array('delete', 'where', 'execute'));
        $dbMock->expects($this->at(0))
                ->method('delete')
                ->with('users_groups')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(1))
                ->method('where')
                ->with(array('group_id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(2))
                ->method('execute')
                ->will($this->returnValue(true));

        $dbMock->expects($this->at(3))
                ->method('delete')
                ->with('groups')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(4))
                ->method('where')
                ->with(array('id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(5))
                ->method('execute')
                ->will($this->returnValue(true));
        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);

        $group = new GroupModel();
        $group->setId(3);

        $this->assertTrue($mapper->delete($group), 'The group does not got deleted successfully.');
    }

    /**
     * Tests if a group can be deleted using a integer.
     */
    public function testDeleteGroupById()
    {
        $dbMock = $this->getMock('Ilch_Database', array('delete', 'where', 'execute'));
        $dbMock->expects($this->at(0))
                ->method('delete')
                ->with('users_groups')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(1))
                ->method('where')
                ->with(array('group_id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(2))
                ->method('execute')
                ->will($this->returnValue(true));

        $dbMock->expects($this->at(3))
                ->method('delete')
                ->with('groups')
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(4))
                ->method('where')
                ->with(array('id' => 3))
                ->will($this->returnValue($dbMock));
        $dbMock->expects($this->at(5))
                ->method('execute')
                ->will($this->returnValue(true));;

        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);

        $this->assertTrue($mapper->delete(3), 'The group does not got deleted successfully.');
    }

    /*
     * Tests if the group access list will be returned correctly.
     */
    public function testGetGroupAccessList()
    {
        $accessDbList = array(
            array(
                'group_name' => 'Squad Leader',
                'group_id' => '4',
                'page_id' => '0',
                'module_id' => '11',
                'article_id' => '0',
                'box_id' => '0',
                'access_level' => '2',
            ),
            array(
                'group_name' => 'Squad Leader',
                'group_id' => '4',
                'page_id' => '0',
                'module_id' => '10',
                'article_id' => '0',
                'box_id' => '0',
                'access_level' => '1',
            ),
            array(
                'group_name' => 'Squad Leader',
                'group_id' => '4',
                'page_id' => '1',
                'module_id' => '0',
                'article_id' => '0',
                'box_id' => '0',
                'access_level' => '1',
            ),
            array(
                'group_name' => 'Squad Leader',
                'group_id' => '4',
                'page_id' => '0',
                'module_id' => '0',
                'article_id' => '1',
                'box_id' => '0',
                'access_level' => '1',
            ),
            array(
                'group_name' => 'Squad Leader',
                'group_id' => '4',
                'page_id' => '0',
                'module_id' => '0',
                'article_id' => '2',
                'box_id' => '0',
                'access_level' => '2',
            ),
            array(
                'group_name' => 'Squad Leader',
                'group_id' => '4',
                'page_id' => '0',
                'module_id' => '0',
                'article_id' => '0',
                'box_id' => '2',
                'access_level' => '2',
            ),
        );
        $dbMock = $this->getMock('Ilch_Database', array('queryArray'));
        $dbMock->expects($this->once())
                ->method('queryArray')
                ->with($this->logicalAnd($this->stringContains('FROM [prefix]_groups_access'), $this->stringContains('INNER JOIN [prefix]_groups'), $this->stringContains('4')))
                ->will($this->returnValue($accessDbList));

        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);
        $accessList = $mapper->getGroupAccessList(4);

        $expectedAccessList = array(
            'group_name' => 'Squad Leader',
            'entries' => array(
                'page' => array(
                    1 => 1,
                ),
                'module' => array(
                    11 => 2,
                    10 => 1,
                ),
                'article' => array(
                    1 => 1,
                    2 => 2,
                ),
                'box' => array(
                    2 => 2,
                ),
            ),
        );

        $this->assertEquals($expectedAccessList, $accessList, 'Group access list was wrong returned.');
    }

    /*
     * Tests if a new access data entry can be saved to db.
     */
    public function testSaveAccessData()
    {
        $expectedFields = array(
            'group_id' => 3,
            'module_id' => 4,
            'access_level' => 2,
        );
        $expectedRec = array(
            'group_id' => 3,
            'module_id' => 4,
        );
        $dbMock = $this->getMock('Ilch_Database', array('insert', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('COUNT(*)', 'groups_access', $expectedRec)
                ->will($this->returnValue(0));
        $dbMock->expects($this->once())
                ->method('insert')
                ->with($expectedFields, 'groups_access')
                ->will($this->returnValue(1));

        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);
        $mapper->saveAccessData(3, 4, 2, 'module');
    }

    /*
     * Tests if a new access data entry with access level 0 can be saved to db.
     */
    public function testSaveAccessDataZeroLevel()
    {
        $expectedFields = array(
            'group_id' => 3,
            'module_id' => 4,
            'access_level' => 0,
        );
        $expectedRec = array(
            'group_id' => 3,
            'module_id' => 4,
        );
        $dbMock = $this->getMock('Ilch_Database', array('insert', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('COUNT(*)', 'groups_access', $expectedRec)
                ->will($this->returnValue(0));
        $dbMock->expects($this->once())
                ->method('insert')
                ->with($expectedFields, 'groups_access')
                ->will($this->returnValue(1));

        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);
        $mapper->saveAccessData(3, 4, 0, 'module');
    }

    /*
     * Tests if a new access data entry can be saved to db.
     */
    public function testUpdateAccessData()
    {
        $expectedFields = array(
            'group_id' => 3,
            'module_id' => 4,
            'access_level' => 2,
        );
        $expectedRec = array(
            'group_id' => 3,
            'module_id' => 4,
        );
        $dbMock = $this->getMock('Ilch_Database', array('update', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('COUNT(*)', 'groups_access', $expectedRec)
                ->will($this->returnValue(1));
        $dbMock->expects($this->once())
                ->method('update')
                ->with($expectedFields, 'groups_access', $expectedRec)
                ->will($this->returnValue(1));

        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);
        $mapper->saveAccessData(3, 4, 2, 'module');
    }

    /*
     * Tests if a new access data entry with access level 0 can be saved to db.
     */
    public function testUpdateAccessDataZeroLevel()
    {
        $expectedFields = array(
            'group_id' => 3,
            'module_id' => 4,
            'access_level' => 0,
        );
        $expectedRec = array(
            'group_id' => 3,
            'module_id' => 4,
        );
        $dbMock = $this->getMock('Ilch_Database', array('update', 'selectCell'));
        $dbMock->expects($this->once())
                ->method('selectCell')
                ->with('COUNT(*)', 'groups_access', $expectedRec)
                ->will($this->returnValue(1));
        $dbMock->expects($this->once())
                ->method('update')
                ->with($expectedFields, 'groups_access', $expectedRec)
                ->will($this->returnValue(1));

        $mapper = new GroupMapper();
        $mapper->setDatabase($dbMock);
        $mapper->saveAccessData(3, 4, 0, 'module');
    }
}
