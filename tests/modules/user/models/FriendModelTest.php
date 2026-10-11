<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\Friend as FriendModel;

class FriendModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new FriendModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new FriendModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new FriendModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new FriendModel();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new FriendModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setFriendUserId() sets and returns the friend user id.
     */
    public function testSetFriendUserId()
    {
        $model = new FriendModel();
        $model->setFriendUserId(7);

        self::assertSame(7, $model->getFriendUserId());
    }

    /**
     * Tests that setFriendUserId() casts to int.
     */
    public function testSetFriendUserIdCastsToInt()
    {
        $model = new FriendModel();
        $model->setFriendUserId('42');

        self::assertSame(42, $model->getFriendUserId());
        self::assertIsInt($model->getFriendUserId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new FriendModel();
        $model->setName('John Doe');

        self::assertSame('John Doe', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new FriendModel();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setAvatar() sets and returns the avatar.
     */
    public function testSetAvatar()
    {
        $model = new FriendModel();
        $model->setAvatar('avatar_120.png');

        self::assertSame('avatar_120.png', $model->getAvatar());
    }

    /**
     * Tests that setApproved() sets and returns the approval state.
     */
    public function testSetApproved()
    {
        $model = new FriendModel();
        $model->setApproved(2);

        self::assertSame(2, $model->getApproved());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new FriendModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setFriendUserId(3));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setAvatar('avatar.png'));
        self::assertSame($model, $model->setApproved(2));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new FriendModel())
            ->setId(1)
            ->setUserId(1)
            ->setFriendUserId(2)
            ->setName('Friend')
            ->setAvatar('avatar.png')
            ->setApproved(2);

        self::assertSame(1, $model->getId());
        self::assertSame(1, $model->getUserId());
        self::assertSame(2, $model->getFriendUserId());
        self::assertSame('Friend', $model->getName());
        self::assertSame('avatar.png', $model->getAvatar());
        self::assertSame(2, $model->getApproved());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new FriendModel();

        self::assertNull($model->getId());
        self::assertNull($model->getUserId());
        self::assertNull($model->getFriendUserId());
        self::assertNull($model->getName());
        self::assertNull($model->getAvatar());
        self::assertNull($model->getApproved());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new FriendModel();
        $model->setId(1)->setUserId(1)->setFriendUserId(2)->setName('Old')->setApproved(2);

        $model->setId(2)->setUserId(3)->setFriendUserId(4)->setName('New')->setApproved(1);

        self::assertSame(2, $model->getId());
        self::assertSame(3, $model->getUserId());
        self::assertSame(4, $model->getFriendUserId());
        self::assertSame('New', $model->getName());
        self::assertSame(1, $model->getApproved());
    }
}
