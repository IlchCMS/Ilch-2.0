<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;

class LogsModelTest extends TestCase
{
    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new Logs();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new Logs();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setUserId() casts floats down to int.
     */
    public function testSetUserIdCastsFloatToInt()
    {
        $model = new Logs();
        $model->setUserId(7.9);

        self::assertSame(7, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setDate() stores the given DateTime instance.
     */
    public function testSetDate()
    {
        $model = new Logs();
        $date = new \DateTime('2024-05-01 12:00:00');
        $model->setDate($date);

        self::assertSame($date, $model->getDate());
        self::assertInstanceOf(\DateTime::class, $model->getDate());
    }

    /**
     * Tests that setInfo() sets and returns the info.
     */
    public function testSetInfo()
    {
        $model = new Logs();
        $model->setInfo('User logged in');

        self::assertSame('User logged in', $model->getInfo());
    }

    /**
     * Tests that setInfo() casts to string.
     */
    public function testSetInfoCastsToString()
    {
        $model = new Logs();
        $model->setInfo(123);

        self::assertSame('123', $model->getInfo());
        self::assertIsString($model->getInfo());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues()
    {
        $model = new Logs();
        $model->setUserId(1);
        $model->setDate(new \DateTime('2024-01-01 00:00:00'));
        $model->setInfo('Old info');

        $model->setUserId(2);
        $model->setDate(new \DateTime('2024-02-02 00:00:00'));
        $model->setInfo('New info');

        self::assertSame(2, $model->getUserId());
        self::assertSame('2024-02-02 00:00:00', $model->getDate()->format('Y-m-d H:i:s'));
        self::assertSame('New info', $model->getInfo());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new Logs();

        self::assertNull($model->getUserId());
        self::assertNull($model->getDate());
        self::assertNull($model->getInfo());
    }
}
