<?php
/**
 * Holds class Modules_User_Models_GroupTest.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch_phpunit
 */

use User\Models\Group as GroupModel;
defined('ACCESS') or die('no direct access');

/**
 * Tests the user group model class.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Modules_User_Models_GroupTest extends PHPUnit_Ilch_TestCase
{
    /**
     * Tests if the user group can save and return a name.
     */
    public function testName()
    {
        $group = new GroupModel();
        $group->setName('newGroup');
throw new Exception('test');
        $this->assertEquals('newGroup', $group->getName(), 'The group name did not save correctly.');
    }

    /**
     * Tests if the user group can save and return a id.
     */
    public function testId()
    {
        $group = new GroupModel();
        $group->setId(3);

        $this->assertEquals(3, $group->getId(), 'The group id did not save correctly.');
        $this->assertTrue(is_int($group->getId()), 'The group id was not returned as an Integer.');
    }
}
