<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Framework\TestCase;

class GroupRankTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new GroupRank();
        $model->setId(1);

        self::assertSame(1, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new GroupRank();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GroupRank();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setGroupId() sets and returns the group id.
     */
    public function testSetGroupId()
    {
        $model = new GroupRank();
        $model->setGroupId(1);

        self::assertSame(1, $model->getGroupId());
    }

    /**
     * Tests that setRank() sets and returns the rank.
     */
    public function testSetRank()
    {
        $model = new GroupRank();
        $model->setRank(0);

        self::assertSame(0, $model->getRank());
    }

    /**
     * Tests that setRank() casts to int.
     */
    public function testSetRankCastsToInt()
    {
        $model = new GroupRank();
        $model->setRank('5');

        self::assertSame(5, $model->getRank());
        self::assertIsInt($model->getRank());
    }

    /**
     * Tests that chainable setters return $this.
     * Note: setId() does not return $this.
     */
    public function testSettersReturnSelf()
    {
        $model = new GroupRank();

        self::assertSame($model, $model->setGroupId(1));
        self::assertSame($model, $model->setRank(0));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new GroupRank())
            ->setGroupId(1)
            ->setRank(0);

        self::assertSame(1, $model->getGroupId());
        self::assertSame(0, $model->getRank());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GroupRank();
        $model->setGroupId(1)->setRank(0);

        $model->setGroupId(2)->setRank(5);

        self::assertSame(2, $model->getGroupId());
        self::assertSame(5, $model->getRank());
    }
}
