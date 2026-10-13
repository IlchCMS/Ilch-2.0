<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\Games as GamesModel;

class GamesModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new GamesModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new GamesModel();
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

        (new GamesModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GamesModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setWarId() sets and returns the warId.
     */
    public function testSetWarId()
    {
        $model = new GamesModel();
        $model->setWarId(7);

        self::assertSame(7, $model->getWarId());
    }

    /**
     * Tests that setMap() sets and returns the map.
     */
    public function testSetMap()
    {
        $model = new GamesModel();
        $model->setMap(3);

        self::assertSame(3, $model->getMap());
    }

    /**
     * Tests that setGroupPoints() sets and returns the group points.
     */
    public function testSetGroupPoints()
    {
        $model = new GamesModel();
        $model->setGroupPoints(12);

        self::assertSame(12, $model->getGroupPoints());
    }

    /**
     * Tests that setGroupPoints(0) stores zero (a valid score).
     */
    public function testSetGroupPointsZero()
    {
        $model = new GamesModel();
        $model->setGroupPoints(0);

        self::assertSame(0, $model->getGroupPoints());
    }

    /**
     * Tests that setEnemyPoints() sets and returns the enemy points.
     */
    public function testSetEnemyPoints()
    {
        $model = new GamesModel();
        $model->setEnemyPoints(9);

        self::assertSame(9, $model->getEnemyPoints());
    }

    /**
     * Tests that setEnemyPoints(0) stores zero (a valid score).
     */
    public function testSetEnemyPointsZero()
    {
        $model = new GamesModel();
        $model->setEnemyPoints(0);

        self::assertSame(0, $model->getEnemyPoints());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new GamesModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setWarId(2));
        self::assertSame($model, $model->setMap(3));
        self::assertSame($model, $model->setGroupPoints(4));
        self::assertSame($model, $model->setEnemyPoints(5));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new GamesModel())
            ->setId(11)
            ->setWarId(2)
            ->setMap(3)
            ->setGroupPoints(12)
            ->setEnemyPoints(9);

        self::assertSame(11, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(3, $model->getMap());
        self::assertSame(12, $model->getGroupPoints());
        self::assertSame(9, $model->getEnemyPoints());
    }

    /**
     * Tests that default values are all 0.
     */
    public function testDefaultValues()
    {
        $model = new GamesModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getWarId());
        self::assertSame(0, $model->getMap());
        self::assertSame(0, $model->getGroupPoints());
        self::assertSame(0, $model->getEnemyPoints());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GamesModel();
        $model->setId(1)->setWarId(1)->setMap(1)->setGroupPoints(1)->setEnemyPoints(1);

        $model->setId(2)->setWarId(2)->setMap(2)->setGroupPoints(2)->setEnemyPoints(2);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(2, $model->getMap());
        self::assertSame(2, $model->getGroupPoints());
        self::assertSame(2, $model->getEnemyPoints());
    }

    /**
     * Tests setByArray() with all keys.
     */
    public function testSetByArrayFull()
    {
        $model = new GamesModel();
        $model->setByArray([
            'id' => 11,
            'war_id' => 2,
            'map' => 3,
            'group_points' => 12,
            'enemy_points' => 9,
        ]);

        self::assertSame(11, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(3, $model->getMap());
        self::assertSame(12, $model->getGroupPoints());
        self::assertSame(9, $model->getEnemyPoints());
    }

    /**
     * Tests that setByArray() casts numeric string values (as returned by the DB).
     */
    public function testSetByArrayCastsValues()
    {
        $model = (new GamesModel())->setByArray([
            'id' => '11',
            'war_id' => '2',
            'map' => '3',
            'group_points' => '12',
            'enemy_points' => '9',
        ]);

        self::assertSame(11, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(3, $model->getMap());
        self::assertSame(12, $model->getGroupPoints());
        self::assertSame(9, $model->getEnemyPoints());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new GamesModel())->setByArray(['map' => 3]);

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getWarId());
        self::assertSame(3, $model->getMap());
        self::assertSame(0, $model->getGroupPoints());
        self::assertSame(0, $model->getEnemyPoints());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new GamesModel())->setByArray(['id' => 9, 'foo' => 'bar']);

        self::assertSame(9, $model->getId());
        self::assertSame(0, $model->getWarId());
    }

    /**
     * Tests that getArray() returns id and all fields.
     */
    public function testGetArrayWithId()
    {
        $model = new GamesModel();
        $model->setId(11)
            ->setWarId(2)
            ->setMap(3)
            ->setGroupPoints(12)
            ->setEnemyPoints(9);

        self::assertSame(
            [
                'id' => 11,
                'war_id' => 2,
                'map' => 3,
                'group_points' => 12,
                'enemy_points' => 9,
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new GamesModel();
        $model->setWarId(2)
            ->setMap(3)
            ->setGroupPoints(0)
            ->setEnemyPoints(0);

        self::assertSame(
            [
                'war_id' => 2,
                'map' => 3,
                'group_points' => 0,
                'enemy_points' => 0,
            ],
            $model->getArray(false)
        );
    }

    /**
     * Tests that setByArray() and getArray() are inverse operations.
     */
    public function testSetByArrayGetArrayRoundTrip()
    {
        $source = [
            'id' => 11,
            'war_id' => 2,
            'map' => 3,
            'group_points' => 12,
            'enemy_points' => 9,
        ];

        $model = (new GamesModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
