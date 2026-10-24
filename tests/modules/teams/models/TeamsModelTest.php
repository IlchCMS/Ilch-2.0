<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Teams\Models;

use PHPUnit\Framework\TestCase;

class TeamsModelTest extends TestCase
{
    /**
     * Tests that default values are 0 for int fields, false for bool fields and '' for string fields.
     */
    public function testDefaultValues()
    {
        $model = new Teams();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getPosition());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getImg());
        self::assertSame('', $model->getLeader());
        self::assertSame('', $model->getCoLeader());
        self::assertSame(0, $model->getGroupId());
        self::assertFalse($model->getOptShow());
        self::assertFalse($model->getOptIn());
        self::assertFalse($model->getNotifyLeader());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Teams();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new Teams();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new Teams();
        $model->setName('Team 1');

        self::assertSame('Team 1', $model->getName());
    }

    /**
     * Tests that setImg() sets and returns the img.
     */
    public function testSetImg()
    {
        $model = new Teams();
        $model->setImg('path/to/image.png');

        self::assertSame('path/to/image.png', $model->getImg());
    }

    /**
     * Tests that setLeader() sets and returns the leader.
     */
    public function testSetLeader()
    {
        $model = new Teams();
        $model->setLeader('1,3');

        self::assertSame('1,3', $model->getLeader());
    }

    /**
     * Tests that setCoLeader() sets and returns the coLeader.
     */
    public function testSetCoLeader()
    {
        $model = new Teams();
        $model->setCoLeader('4');

        self::assertSame('4', $model->getCoLeader());
    }

    /**
     * Tests that setGroupId() sets and returns the groupId.
     */
    public function testSetGroupId()
    {
        $model = new Teams();
        $model->setGroupId(2);

        self::assertSame(2, $model->getGroupId());
    }

    /**
     * Tests that setOptShow() sets and returns the optShow flag.
     */
    public function testSetOptShow()
    {
        $model = new Teams();
        $model->setOptShow(true);

        self::assertTrue($model->getOptShow());
    }

    /**
     * Tests that setOptIn() sets and returns the optIn flag.
     */
    public function testSetOptIn()
    {
        $model = new Teams();
        $model->setOptIn(false);

        self::assertFalse($model->getOptIn());
    }

    /**
     * Tests that setNotifyLeader() sets and returns the notifyLeader flag.
     */
    public function testSetNotifyLeader()
    {
        $model = new Teams();
        $model->setNotifyLeader(true);

        self::assertTrue($model->getNotifyLeader());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Teams();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setPosition(1));
        self::assertSame($model, $model->setName('Team 1'));
        self::assertSame($model, $model->setImg(''));
        self::assertSame($model, $model->setLeader('1,3'));
        self::assertSame($model, $model->setCoLeader('4'));
        self::assertSame($model, $model->setGroupId(1));
        self::assertSame($model, $model->setOptShow(true));
        self::assertSame($model, $model->setOptIn(false));
        self::assertSame($model, $model->setNotifyLeader(false));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Teams())
            ->setId(1)
            ->setPosition(1)
            ->setName('Team 1')
            ->setImg('')
            ->setLeader('1,3')
            ->setCoLeader('4')
            ->setGroupId(1)
            ->setOptShow(true)
            ->setOptIn(false)
            ->setNotifyLeader(false);

        self::assertSame(1, $model->getId());
        self::assertSame(1, $model->getPosition());
        self::assertSame('Team 1', $model->getName());
        self::assertSame('', $model->getImg());
        self::assertSame('1,3', $model->getLeader());
        self::assertSame('4', $model->getCoLeader());
        self::assertSame(1, $model->getGroupId());
        self::assertTrue($model->getOptShow());
        self::assertFalse($model->getOptIn());
        self::assertFalse($model->getNotifyLeader());
    }

    /**
     * Tests that getArray() returns all fields including the id.
     */
    public function testGetArrayWithId()
    {
        $model = new Teams();
        $model->setId(1);
        $model->setPosition(1);
        $model->setName('Team 1');
        $model->setImg('');
        $model->setLeader('1,3');
        $model->setCoLeader('4');
        $model->setGroupId(1);
        $model->setOptShow(true);
        $model->setOptIn(false);
        $model->setNotifyLeader(false);

        self::assertSame([
            'id' => 1,
            'name' => 'Team 1',
            'position' => 1,
            'img' => '',
            'leader' => '1,3',
            'coLeader' => '4',
            'groupId' => 1,
            'optShow' => true,
            'optIn' => false,
            'notifyLeader' => false,
        ], $model->getArray());
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new Teams();
        $model->setId(1);
        $model->setGroupId(2);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(2, $array['groupId']);
    }

    /**
     * Tests that setByArray() fills the model from a complete row, converting int flags to bool.
     */
    public function testSetByArrayFull()
    {
        $model = new Teams();
        $result = $model->setByArray([
            'id' => 1,
            'position' => 1,
            'name' => 'Team 1',
            'img' => '',
            'leader' => '1,3',
            'coLeader' => '4',
            'groupId' => 1,
            'optShow' => 1,
            'optIn' => 0,
            'notifyLeader' => 0,
        ]);

        self::assertSame($model, $result);
        self::assertSame(1, $model->getId());
        self::assertSame(1, $model->getPosition());
        self::assertSame('Team 1', $model->getName());
        self::assertSame('', $model->getImg());
        self::assertSame('1,3', $model->getLeader());
        self::assertSame('4', $model->getCoLeader());
        self::assertSame(1, $model->getGroupId());
        self::assertTrue($model->getOptShow());
        self::assertFalse($model->getOptIn());
        self::assertFalse($model->getNotifyLeader());
    }

    /**
     * Tests that setByArray() only changes the keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new Teams();
        $model->setByArray(['name' => 'Partial', 'groupId' => 4]);

        self::assertSame('Partial', $model->getName());
        self::assertSame(4, $model->getGroupId());
        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getLeader());
        self::assertFalse($model->getOptShow());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Teams();
        $model->setName('Old')->setGroupId(1)->setOptShow(false);

        $model->setName('New')->setGroupId(2)->setOptShow(true);

        self::assertSame('New', $model->getName());
        self::assertSame(2, $model->getGroupId());
        self::assertTrue($model->getOptShow());
    }
}
