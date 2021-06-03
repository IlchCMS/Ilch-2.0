<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\TestCase;
use Modules\User\Mappers\Group as GroupMapper;

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
        $groupRow =
            [
            'id'   => 1,
            'name' => 'Administrator',
            ];

        $mapper = new GroupMapper();
        $group = $mapper->loadFromArray($groupRow);

        self::assertNotFalse($group);
        self::assertEquals(1, $group->getId());
        self::assertEquals('Administrator', $group->getName());
    }
}
