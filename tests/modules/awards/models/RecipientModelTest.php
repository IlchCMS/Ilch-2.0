<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Awards\Models;

use PHPUnit\Framework\TestCase;
use Modules\Awards\Models\Recipient as RecipientModel;

class RecipientModelTest extends TestCase
{
    /**
     * Tests that default property values are all zero.
     */
    public function testDefaults()
    {
        $model = new RecipientModel();

        self::assertEquals(0, $model->getAwardId());
        self::assertEquals(0, $model->getUtId());
        self::assertEquals(0, $model->getTyp());
    }

    /**
     * Tests that setters are chainable and values are stored correctly.
     */
    public function testSettersAreChainable()
    {
        $model = new RecipientModel();
        $result = $model->setAwardId(5)->setUtId(42)->setTyp(1);

        self::assertSame($model, $result);
        self::assertEquals(5, $model->getAwardId());
        self::assertEquals(42, $model->getUtId());
        self::assertEquals(1, $model->getTyp());
    }

    /**
     * Tests that getArray() returns the correct keys and values.
     *
     * The key mapping (especially utId → ut_id) must match the
     * awards_recipients column names used by both mappers.
     */
    public function testGetArray()
    {
        $model = new RecipientModel();
        $model->setAwardId(3)
            ->setUtId(112)
            ->setTyp(1);

        $array = $model->getArray();

        self::assertIsArray($array);
        self::assertCount(3, $array);
        self::assertArrayHasKey('award_id', $array);
        self::assertArrayHasKey('ut_id', $array);
        self::assertArrayHasKey('typ', $array);

        self::assertEquals(3, $array['award_id']);
        self::assertEquals(112, $array['ut_id']);
        self::assertEquals(1, $array['typ']);
    }

    /**
     * Tests that getArray() with defaults returns zeros for all keys.
     */
    public function testGetArrayDefaults()
    {
        $model = new RecipientModel();

        self::assertEquals([
            'award_id' => 0,
            'ut_id'    => 0,
            'typ'      => 0,
        ], $model->getArray());
    }
}
