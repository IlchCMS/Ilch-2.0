<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\Usermenusettings as UsermenusettingsModel;

class UsermenusettingsModelTest extends TestCase
{
    /**
     * Tests that setKey() sets the key.
     */
    public function testSetKey()
    {
        $model = new UsermenusettingsModel();
        $model->setKey('user/panel/index');

        self::assertSame('user/panel/index', $model->getKey());
    }

    /**
     * Tests that setKey() casts to string.
     */
    public function testSetKeyCastsToString()
    {
        $model = new UsermenusettingsModel();
        $model->setKey(123);

        self::assertSame('123', $model->getKey());
        self::assertIsString($model->getKey());
    }

    /**
     * Tests that setLocale() sets the locale.
     */
    public function testSetLocale()
    {
        $model = new UsermenusettingsModel();
        $model->setLocale('en_EN');

        self::assertSame('en_EN', $model->getLocale());
    }

    /**
     * Tests that setLocale() casts to string.
     */
    public function testSetLocaleCastsToString()
    {
        $model = new UsermenusettingsModel();
        $model->setLocale(456);

        self::assertSame('456', $model->getLocale());
        self::assertIsString($model->getLocale());
    }

    /**
     * Tests that setDescription() sets the description.
     */
    public function testsetDescription()
    {
        $model = new UsermenusettingsModel();
        $model->setDescription('Dashboard');

        self::assertSame('Dashboard', $model->getDescription());
    }

    /**
     * Tests that setDescription() casts to string.
     */
    public function testSetDescriptionCastsToString()
    {
        $model = new UsermenusettingsModel();
        $model->setDescription(789);

        self::assertSame('789', $model->getDescription());
        self::assertIsString($model->getDescription());
    }

    /**
     * Tests that setName() sets the name.
     */
    public function testSetName()
    {
        $model = new UsermenusettingsModel();
        $model->setName('Panel');

        self::assertSame('Panel', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new UsermenusettingsModel();
        $model->setName(1011);

        self::assertSame('1011', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setIcon() sets the icon.
     */
    public function testSetIcon()
    {
        $model = new UsermenusettingsModel();
        $model->setIcon('fa-solid fa-house');

        self::assertSame('fa-solid fa-house', $model->getIcon());
    }

    /**
     * Tests that setIcon() casts to string.
     */
    public function testSetIconCastsToString()
    {
        $model = new UsermenusettingsModel();
        $model->setIcon(1213);

        self::assertSame('1213', $model->getIcon());
        self::assertIsString($model->getIcon());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new UsermenusettingsModel();

        self::assertSame($model, $model->setKey('user/panel/index'));
        self::assertSame($model, $model->setLocale('en_EN'));
        self::assertSame($model, $model->setDescription('Dashboard'));
        self::assertSame($model, $model->setName('Panel'));
        self::assertSame($model, $model->setIcon('fa-solid fa-house'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new UsermenusettingsModel())
            ->setKey('user/panel/index')
            ->setLocale('en_EN')
            ->setDescription('Dashboard')
            ->setName('Panel')
            ->setIcon('fa-solid fa-house');

        self::assertSame('user/panel/index', $model->getKey());
        self::assertSame('en_EN', $model->getLocale());
        self::assertSame('Dashboard', $model->getDescription());
        self::assertSame('Panel', $model->getName());
        self::assertSame('fa-solid fa-house', $model->getIcon());
    }

    /**
     * Tests that default values are null for all properties.
     */
    public function testDefaultValues()
    {
        $model = new UsermenusettingsModel();

        self::assertNull($model->getKey());
        self::assertNull($model->getLocale());
        self::assertNull($model->getDescription());
        self::assertNull($model->getName());
        self::assertNull($model->getIcon());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new UsermenusettingsModel();
        $model->setKey('user/panel/index')->setName('Panel');

        $model->setKey('user/panel/settings')->setName('Settings');

        self::assertSame('user/panel/settings', $model->getKey());
        self::assertSame('Settings', $model->getName());
    }
}
