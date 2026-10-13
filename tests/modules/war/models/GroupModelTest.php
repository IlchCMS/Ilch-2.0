<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\Group as GroupModel;

class GroupModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new GroupModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new GroupModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId() rejects non-numeric strings.
     */
    public function testSetIdRejectsNonNumericString()
    {
        $this->expectException(\TypeError::class);

        (new GroupModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GroupModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setGroupName() sets and returns the name.
     */
    public function testSetGroupName()
    {
        $model = new GroupModel();
        $model->setGroupName('Friendly Fire');

        self::assertSame('Friendly Fire', $model->getGroupName());
    }

    /**
     * Tests that setGroupName() casts to string.
     */
    public function testSetGroupNameCastsToString()
    {
        $model = new GroupModel();
        $model->setGroupName(123);

        self::assertSame('123', $model->getGroupName());
        self::assertIsString($model->getGroupName());
    }

    /**
     * Tests that setGroupTag() sets and returns the tag.
     */
    public function testSetGroupTag()
    {
        $model = new GroupModel();
        $model->setGroupTag('FF');

        self::assertSame('FF', $model->getGroupTag());
    }

    /**
     * Tests that setGroupImage() sets and returns the image.
     */
    public function testSetGroupImage()
    {
        $model = new GroupModel();
        $model->setGroupImage('friendlyfire.png');

        self::assertSame('friendlyfire.png', $model->getGroupImage());
    }

    /**
     * Tests that setGroupImageThumb() sets and returns the image thumb.
     */
    public function testSetGroupImageThumb()
    {
        $model = new GroupModel();
        $model->setGroupImageThumb('friendlyfire_thumb.png');

        self::assertSame('friendlyfire_thumb.png', $model->getGroupImageThumb());
    }

    /**
     * Tests that setGroupMember() sets and returns the member count.
     */
    public function testSetGroupMember()
    {
        $model = new GroupModel();
        $model->setGroupMember('25');

        self::assertSame('25', $model->getGroupMember());
    }

    /**
     * Documents current behavior: member is coerced to string (schema column is INT).
     */
    public function testSetGroupMemberCastsToString()
    {
        $model = new GroupModel();
        $model->setGroupMember(25);

        self::assertSame('25', $model->getGroupMember());
        self::assertIsString($model->getGroupMember());
    }

    /**
     * Tests that setGroupDesc() sets and returns the description.
     */
    public function testSetGroupDesc()
    {
        $model = new GroupModel();
        $model->setGroupDesc('A friendly team');

        self::assertSame('A friendly team', $model->getGroupDesc());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new GroupModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setGroupName('Test'));
        self::assertSame($model, $model->setGroupTag('TST'));
        self::assertSame($model, $model->setGroupImage('test.png'));
        self::assertSame($model, $model->setGroupImageThumb('test_thumb.png'));
        self::assertSame($model, $model->setGroupMember('5'));
        self::assertSame($model, $model->setGroupDesc('Test group'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new GroupModel())
            ->setId(3)
            ->setGroupName('Friendly Fire')
            ->setGroupTag('FF')
            ->setGroupImage('friendlyfire.png')
            ->setGroupMember('25')
            ->setGroupDesc('A friendly team');

        self::assertSame(3, $model->getId());
        self::assertSame('Friendly Fire', $model->getGroupName());
        self::assertSame('FF', $model->getGroupTag());
        self::assertSame('friendlyfire.png', $model->getGroupImage());
        self::assertSame('25', $model->getGroupMember());
        self::assertSame('A friendly team', $model->getGroupDesc());
    }

    /**
     * Tests that default values are 0 for id and empty string for all text fields.
     */
    public function testDefaultValues()
    {
        $model = new GroupModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getGroupName());
        self::assertSame('', $model->getGroupTag());
        self::assertSame('', $model->getGroupImage());
        self::assertSame('', $model->getGroupImageThumb());
        self::assertSame('', $model->getGroupMember());
        self::assertSame('', $model->getGroupDesc());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GroupModel();
        $model->setId(1)->setGroupName('Old')->setGroupTag('OLD')->setGroupMember('10');

        $model->setId(2)->setGroupName('New')->setGroupTag('NEW')->setGroupMember('20');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getGroupName());
        self::assertSame('NEW', $model->getGroupTag());
        self::assertSame('20', $model->getGroupMember());
    }

    /**
     * Tests setByArray() with all supported keys.
     */
    public function testSetByArrayFull()
    {
        $model = new GroupModel();
        $model->setByArray([
            'id' => 1,
            'name' => 'Friendly Fire',
            'tag' => 'FF',
            'image' => 'friendlyfire.png',
            'member' => '25',
            'desc' => 'A friendly team',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Friendly Fire', $model->getGroupName());
        self::assertSame('FF', $model->getGroupTag());
        self::assertSame('friendlyfire.png', $model->getGroupImage());
        self::assertSame('25', $model->getGroupMember());
        self::assertSame('A friendly team', $model->getGroupDesc());
    }

    /**
     * Documents current behavior: an int member value is coerced to string.
     */
    public function testSetByArrayCoercesMemberToString()
    {
        $model = (new GroupModel())->setByArray(['member' => 25]);

        self::assertSame('25', $model->getGroupMember());
        self::assertIsString($model->getGroupMember());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new GroupModel())->setByArray(['name' => 'New Group']);

        self::assertSame(0, $model->getId());
        self::assertSame('New Group', $model->getGroupName());
        self::assertSame('', $model->getGroupTag());
        self::assertSame('', $model->getGroupDesc());
    }

    /**
     * Tests that setByArray() ignores unknown keys (including image_thumb).
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new GroupModel())->setByArray(['id' => 9, 'image_thumb' => 'thumb.png']);

        self::assertSame(9, $model->getId());
        self::assertSame('', $model->getGroupImageThumb());
    }

    /**
     * Tests that getArray() returns id and all persisted fields.
     */
    public function testGetArrayWithId()
    {
        $model = new GroupModel();
        $model->setId(3)
            ->setGroupName('Friendly Fire')
            ->setGroupTag('FF')
            ->setGroupImage('friendlyfire.png')
            ->setGroupMember('25')
            ->setGroupDesc('A friendly team');

        self::assertSame(
            [
                'id' => 3,
                'name' => 'Friendly Fire',
                'tag' => 'FF',
                'image' => 'friendlyfire.png',
                'member' => '25',
                'desc' => 'A friendly team',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new GroupModel();
        $model->setGroupName('Friendly Fire')
            ->setGroupTag('FF')
            ->setGroupImage('friendlyfire.png')
            ->setGroupMember('25')
            ->setGroupDesc('A friendly team');

        self::assertSame(
            [
                'name' => 'Friendly Fire',
                'tag' => 'FF',
                'image' => 'friendlyfire.png',
                'member' => '25',
                'desc' => 'A friendly team',
            ],
            $model->getArray(false)
        );
    }

    /**
     * Documents current behavior: image_thumb is not part of getArray().
     */
    public function testGetArrayExcludesImageThumb()
    {
        $model = new GroupModel();
        $model->setGroupImageThumb('thumb.png');

        self::assertSame('thumb.png', $model->getGroupImageThumb());

        self::assertArrayNotHasKey('image_thumb', $model->getArray());
    }

    /**
     * Tests that setByArray() and getArray() are inverse operations.
     */
    public function testSetByArrayGetArrayRoundTrip()
    {
        $source = [
            'id' => 1,
            'name' => 'Friendly Fire',
            'tag' => 'FF',
            'image' => 'friendlyfire.png',
            'member' => '25',
            'desc' => 'A friendly team',
        ];

        $model = (new GroupModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
