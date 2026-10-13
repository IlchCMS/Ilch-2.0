<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\GameIcon as GameIconModel;

class GameIconModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new GameIconModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new GameIconModel();
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

        (new GameIconModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GameIconModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new GameIconModel();
        $model->setTitle('Counter-Strike 2');

        self::assertSame('Counter-Strike 2', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new GameIconModel();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setIcon() sets and returns the icon.
     */
    public function testSetIcon()
    {
        $model = new GameIconModel();
        $model->setIcon('cs2.png');

        self::assertSame('cs2.png', $model->getIcon());
    }

    /**
     * Tests that setIcon() casts to string.
     */
    public function testSetIconCastsToString()
    {
        $model = new GameIconModel();
        $model->setIcon(456);

        self::assertSame('456', $model->getIcon());
        self::assertIsString($model->getIcon());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new GameIconModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setIcon('test.png'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new GameIconModel())
            ->setId(3)
            ->setTitle('Warcraft 3')
            ->setIcon('wc3.png');

        self::assertSame(3, $model->getId());
        self::assertSame('Warcraft 3', $model->getTitle());
        self::assertSame('wc3.png', $model->getIcon());
    }

    /**
     * Tests that default values are 0 for id and empty string for title/icon.
     */
    public function testDefaultValues()
    {
        $model = new GameIconModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getIcon());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GameIconModel();
        $model->setId(1)->setTitle('Old')->setIcon('old.png');

        $model->setId(2)->setTitle('New')->setIcon('new.png');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getTitle());
        self::assertSame('new.png', $model->getIcon());
    }

    /**
     * Tests setByArray() with all keys.
     */
    public function testSetByArrayFull()
    {
        $model = new GameIconModel();
        $model->setByArray([
            'id' => 4,
            'title' => 'League of Legends',
            'icon' => 'lol.png',
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame('League of Legends', $model->getTitle());
        self::assertSame('lol.png', $model->getIcon());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new GameIconModel())->setByArray(['title' => 'Doom']);

        self::assertSame(0, $model->getId());
        self::assertSame('Doom', $model->getTitle());
        self::assertSame('', $model->getIcon());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new GameIconModel())->setByArray(['id' => 9, 'foo' => 'bar']);

        self::assertSame(9, $model->getId());
        self::assertSame('', $model->getTitle());
    }

    /**
     * Tests that getArray() returns id and all fields.
     */
    public function testGetArrayWithId()
    {
        $model = new GameIconModel();
        $model->setId(3)->setTitle('StarCraft 2')->setIcon('sc2.png');

        self::assertSame(
            [
                'id' => 3,
                'title' => 'StarCraft 2',
                'icon' => 'sc2.png',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new GameIconModel();
        $model->setTitle('StarCraft 2')->setIcon('sc2.png');

        self::assertSame(
            [
                'title' => 'StarCraft 2',
                'icon' => 'sc2.png',
            ],
            $model->getArray(false)
        );
    }

    /**
     * Documents current behavior: a fresh model's getArray() contains a zero id.
     */
    public function testGetArrayFreshModelContainsZeroId()
    {
        $model = new GameIconModel();

        self::assertSame(
            [
                'id' => 0,
                'title' => '',
                'icon' => '',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that setByArray() and getArray() are inverse operations.
     */
    public function testSetByArrayGetArrayRoundTrip()
    {
        $source = [
            'id' => 4,
            'title' => 'League of Legends',
            'icon' => 'lol.png',
        ];

        $model = (new GameIconModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
