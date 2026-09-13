<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Kvteam\Models;

use PHPUnit\Framework\TestCase;
use Modules\Kvteam\Models\Team as TeamModel;

class TeamModelTest extends TestCase
{
    /**
     * Tests default values of a new Team model.
     */
    public function testDefaultValues()
    {
        $model = new TeamModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getUserIds());
        self::assertSame(0, $model->getPosition());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new TeamModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero()
    {
        $model = new TeamModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new TeamModel();
        $model->setTitle('Board Members');

        self::assertSame('Board Members', $model->getTitle());
    }

    /**
     * Tests that setUserIds() sets and returns the userIds.
     */
    public function testSetUserIds()
    {
        $model = new TeamModel();
        $model->setUserIds('1,2,3');

        self::assertSame('1,2,3', $model->getUserIds());
    }

    /**
     * Tests that setUserIds() stores a single id.
     */
    public function testSetUserIdsSingle()
    {
        $model = new TeamModel();
        $model->setUserIds('42');

        self::assertSame('42', $model->getUserIds());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new TeamModel();
        $model->setPosition(7);

        self::assertSame(7, $model->getPosition());
    }

    /**
     * Tests that setPosition(0) stores zero.
     */
    public function testSetPositionZero()
    {
        $model = new TeamModel();
        $model->setPosition(0);

        self::assertSame(0, $model->getPosition());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new TeamModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setUserIds('1,2'));
        self::assertSame($model, $model->setPosition(3));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new TeamModel())
            ->setId(3)
            ->setTitle('Support Team')
            ->setUserIds('6,7,8,9')
            ->setPosition(3);

        self::assertSame(3, $model->getId());
        self::assertSame('Support Team', $model->getTitle());
        self::assertSame('6,7,8,9', $model->getUserIds());
        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new TeamModel();
        $model->setId(1)
            ->setTitle('Old Title')
            ->setUserIds('1,2')
            ->setPosition(1);

        $model->setId(2)
            ->setTitle('New Title')
            ->setUserIds('5,6,7')
            ->setPosition(4);

        self::assertSame(2, $model->getId());
        self::assertSame('New Title', $model->getTitle());
        self::assertSame('5,6,7', $model->getUserIds());
        self::assertSame(4, $model->getPosition());
    }

    /**
     * Tests that empty userIds string is valid.
     */
    public function testSetUserIdsEmpty()
    {
        $model = new TeamModel();
        $model->setUserIds('');

        self::assertSame('', $model->getUserIds());
    }

    /**
     * Tests that userIds with spaces is preserved.
     */
    public function testSetUserIdsWithSpaces()
    {
        $model = new TeamModel();
        $model->setUserIds('1, 2, 3');

        self::assertSame('1, 2, 3', $model->getUserIds());
    }

    /**
     * Tests that title with special characters is preserved.
     */
    public function testSetTitleSpecialCharacters()
    {
        $model = new TeamModel();
        $model->setTitle('Team – Section "A"');

        self::assertSame('Team – Section "A"', $model->getTitle());
    }
}
