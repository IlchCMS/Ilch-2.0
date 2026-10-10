<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\Backup as BackupModel;

/**
 * Tests the Backup model class.
 *
 * @package ilch_phpunit
 */
class BackupModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new BackupModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new BackupModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new BackupModel();
        $model->setName('backup1');

        self::assertSame('backup1', $model->getName());
    }

    /**
     * Tests that setDate() sets and returns the date.
     */
    public function testSetDate()
    {
        $model = new BackupModel();
        $model->setDate('2014-01-01 12:12:12');

        self::assertSame('2014-01-01 12:12:12', $model->getDate());
    }

    /**
     * Tests that the default values of the properties are null.
     * The getters are typed and would throw a TypeError on a fresh model,
     * therefore the properties are checked via reflection.
     */
    public function testDefaultValues()
    {
        $model = new BackupModel();

        $idProperty = new \ReflectionProperty(BackupModel::class, 'id');
        $nameProperty = new \ReflectionProperty(BackupModel::class, 'name');
        $dateProperty = new \ReflectionProperty(BackupModel::class, 'date');

        self::assertNull($idProperty->getValue($model));
        self::assertNull($nameProperty->getValue($model));
        self::assertNull($dateProperty->getValue($model));
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new BackupModel();
        $model->setId(1);
        $model->setName('OldBackup');
        $model->setDate('2014-01-01 12:12:12');

        $model->setId(2);
        $model->setName('NewBackup');
        $model->setDate('2015-02-02 13:13:13');

        self::assertSame(2, $model->getId());
        self::assertSame('NewBackup', $model->getName());
        self::assertSame('2015-02-02 13:13:13', $model->getDate());
    }
}
