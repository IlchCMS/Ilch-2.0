<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the user group model class.
 *
 * @package ilch_phpunit
 */
class GroupTest extends TestCase
{
    /**
     * Tests if the user group can save and return a name.
     */
    public function testName()
    {
        $group = new Group();
        $group->setName('newGroup');

        $this->assertEquals('newGroup', $group->getName(), 'The group name did not save correctly.');
    }

    /**
     * Tests if the user group can save and return a id.
     */
    public function testId()
    {
        $group = new Group();
        $group->setId(3);

        $this->assertEquals(3, $group->getId(), 'The group id did not save correctly.');
        $this->assertTrue(is_int($group->getId()), 'The group id was not returned as an Integer.');
    }
}
