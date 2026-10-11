<?php

/**
 * @copyright Ilch 2
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

        self::assertEquals('newGroup', $group->getName(), 'The group name did not save correctly.');
    }

    /**
     * Tests if the user group can save and return an id.
     */
    public function testId()
    {
        $group = new Group();
        $group->setId(3);

        self::assertEquals(3, $group->getId(), 'The group id did not save correctly.');
    }

    /**
     * Tests if the default values are set correctly.
     */
    public function testDefaultValues()
    {
        $group = new Group();

        self::assertEquals(0, $group->getId(), 'The default group id is not 0.');
        self::assertEquals('', $group->getName(), 'The default group name is not an empty string.');
    }

    /**
     * Tests if the setters are chainable and return the group.
     */
    public function testSettersReturnSelf()
    {
        $group = new Group();

        self::assertSame($group, $group->setId(1), 'setId() does not return the group.');
        self::assertSame($group, $group->setName('Admin'), 'setName() does not return the group.');
    }

    /**
     * Tests if chaining setters builds a complete group.
     */
    public function testChainedSetters()
    {
        $group = (new Group())
            ->setId(2)
            ->setName('Member');

        self::assertEquals(2, $group->getId(), 'The group id was not saved correctly.');
        self::assertEquals('Member', $group->getName(), 'The group name was not saved correctly.');
    }

    /**
     * Tests if a previously set value can be overwritten.
     */
    public function testOverwriteValues()
    {
        $group = new Group();
        $group->setId(1)->setName('Old');

        $group->setId(2)->setName('New');

        self::assertEquals(2, $group->getId(), 'The group id was not overwritten correctly.');
        self::assertEquals('New', $group->getName(), 'The group name was not overwritten correctly.');
    }

    /**
     * Tests if the group can be set by an array.
     */
    public function testSetByArray()
    {
        $group = new Group();
        $group->setByArray(['id' => 3, 'name' => 'Moderator']);

        self::assertEquals(3, $group->getId(), 'The group id was not saved correctly.');
        self::assertEquals('Moderator', $group->getName(), 'The group name was not saved correctly.');
    }

    /**
     * Tests if setByArray() only applies the keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $group = new Group();
        $group->setByArray(['name' => 'Guest']);

        self::assertEquals(0, $group->getId(), 'The group id was changed unexpectedly.');
        self::assertEquals('Guest', $group->getName(), 'The group name was not saved correctly.');
    }

    /**
     * Tests if setByArray() returns the group and ignores unknown keys.
     */
    public function testSetByArrayReturnsSelfAndIgnoresUnknownKeys()
    {
        $group = new Group();

        self::assertSame($group, $group->setByArray(['id' => 5, 'unknown' => 'foo']), 'setByArray() does not return the group.');
        self::assertEquals(5, $group->getId(), 'The group id was not saved correctly.');
        self::assertEquals('', $group->getName(), 'An unknown key changed the group name.');
    }

    /**
     * Tests if the group returns the correct array with the id.
     */
    public function testGetArrayWithId()
    {
        $group = new Group();
        $group->setId(1)->setName('Admin');

        self::assertEquals(['id' => 1, 'name' => 'Admin'], $group->getArray(), 'The group array with id is not correct.');
    }

    /**
     * Tests if the group returns the correct array without the id.
     */
    public function testGetArrayWithoutId()
    {
        $group = new Group();
        $group->setId(1)->setName('Admin');

        self::assertEquals(['name' => 'Admin'], $group->getArray(false), 'The group array without id is not correct.');
    }
}
