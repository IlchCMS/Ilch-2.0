<?php
/**
 * Holds class Modules_User_Mappers_GroupTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\TestCase;
use \Modules\User\Mappers\Group as GroupMapper;

/**
 * Tests the user group mapper class.
 *
 * @package ilch_phpunit
 */
class GroupTest extends TestCase
{
    /**
     * Tests if the user group mapper returns the right user group model using an
     * data array.
     */
    public function testLoadFromArray()
    {
        $groupRow = array
        (
            'id'   => 1,
            'name' => 'Administrator',
        );

        $mapper = new GroupMapper();
        $group = $mapper->loadFromArray($groupRow);

        $this->assertTrue($group !== false);
        $this->assertEquals(1, $group->getId());
        $this->assertEquals('Administrator', $group->getName());
    }
}
