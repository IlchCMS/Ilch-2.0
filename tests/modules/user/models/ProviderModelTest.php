<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\Provider as ProviderModel;

class ProviderModelTest extends TestCase
{
    /**
     * Documents that the constructor ignores its $params argument:
     * a model created with params still has no values set.
     */
    public function testConstructorIgnoresParams()
    {
        $model = new ProviderModel(['key' => 'google']);

        self::assertNull($model->getKey());
    }

    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new ProviderModel();
        $model->setKey('google');

        self::assertSame('google', $model->getKey());
    }

    /**
     * Tests that setKey() casts to string.
     */
    public function testSetKeyCastsToString()
    {
        $model = new ProviderModel();
        $model->setKey(123);

        self::assertSame('123', $model->getKey());
        self::assertIsString($model->getKey());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new ProviderModel();
        $model->setName('Google');

        self::assertSame('Google', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new ProviderModel();
        $model->setName(456);

        self::assertSame('456', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setIcon() sets and returns the icon.
     */
    public function testSetIcon()
    {
        $model = new ProviderModel();
        $model->setIcon('fa-brands fa-google');

        self::assertSame('fa-brands fa-google', $model->getIcon());
    }

    /**
     * Tests that setModule() sets and returns the module.
     */
    public function testSetModule()
    {
        $model = new ProviderModel();
        $model->setModule('user');

        self::assertSame('user', $model->getModule());
    }

    /**
     * Tests that setModule() casts to string.
     */
    public function testSetModuleCastsToString()
    {
        $model = new ProviderModel();
        $model->setModule(456);

        self::assertSame('456', $model->getModule());
        self::assertIsString($model->getModule());
    }

    /**
     * Tests that setAuthController() sets and returns the value.
     */
    public function testSetAuthController()
    {
        $model = new ProviderModel();
        $model->setAuthController('Auth');

        self::assertSame('Auth', $model->getAuthController());
    }

    /**
     * Tests that setAuthAction() sets and returns the value.
     */
    public function testSetAuthAction()
    {
        $model = new ProviderModel();
        $model->setAuthAction('login');

        self::assertSame('login', $model->getAuthAction());
    }

    /**
     * Tests that setUnlinkController() sets and returns the value.
     */
    public function testSetUnlinkController()
    {
        $model = new ProviderModel();
        $model->setUnlinkController('Auth');

        self::assertSame('Auth', $model->getUnlinkController());
    }

    /**
     * Tests that setUnlinkAction() sets and returns the value.
     */
    public function testSetUnlinkAction()
    {
        $model = new ProviderModel();
        $model->setUnlinkAction('unlink');

        self::assertSame('unlink', $model->getUnlinkAction());
    }

    /**
     * Tests that setModuleName() sets and returns the value.
     */
    public function testSetModuleName()
    {
        $model = new ProviderModel();
        $model->setModuleName('Benutzer');

        self::assertSame('Benutzer', $model->getModuleName());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ProviderModel();

        self::assertSame($model, $model->setKey('google'));
        self::assertSame($model, $model->setName('Google'));
        self::assertSame($model, $model->setIcon('fa-brands fa-google'));
        self::assertSame($model, $model->setModule('user'));
        self::assertSame($model, $model->setAuthController('Auth'));
        self::assertSame($model, $model->setAuthAction('login'));
        self::assertSame($model, $model->setUnlinkController('Auth'));
        self::assertSame($model, $model->setUnlinkAction('unlink'));
        self::assertSame($model, $model->setModuleName('Benutzer'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ProviderModel())
            ->setKey('google')
            ->setName('Google')
            ->setIcon('fa-brands fa-google')
            ->setModule('user')
            ->setAuthController('Auth')
            ->setAuthAction('login')
            ->setUnlinkController('Auth')
            ->setUnlinkAction('unlink')
            ->setModuleName('Benutzer');

        self::assertSame('google', $model->getKey());
        self::assertSame('Google', $model->getName());
        self::assertSame('fa-brands fa-google', $model->getIcon());
        self::assertSame('user', $model->getModule());
        self::assertSame('Auth', $model->getAuthController());
        self::assertSame('login', $model->getAuthAction());
        self::assertSame('Auth', $model->getUnlinkController());
        self::assertSame('unlink', $model->getUnlinkAction());
        self::assertSame('Benutzer', $model->getModuleName());
    }

    /**
     * Tests that default values are null for all properties.
     */
    public function testDefaultValues()
    {
        $model = new ProviderModel();

        self::assertNull($model->getKey());
        self::assertNull($model->getName());
        self::assertNull($model->getIcon());
        self::assertNull($model->getModule());
        self::assertNull($model->getAuthController());
        self::assertNull($model->getAuthAction());
        self::assertNull($model->getUnlinkController());
        self::assertNull($model->getUnlinkAction());
        self::assertNull($model->getModuleName());
    }

    /**
     * Tests that the setters accept null, matching the nullable
     * database columns (icon, module, auth/unlink controller/action).
     */
    public function testSettersAcceptNull()
    {
        $model = (new ProviderModel())
            ->setKey(null)
            ->setName(null)
            ->setIcon(null)
            ->setModule(null)
            ->setAuthController(null)
            ->setAuthAction(null)
            ->setUnlinkController(null)
            ->setUnlinkAction(null)
            ->setModuleName(null);

        self::assertNull($model->getKey());
        self::assertNull($model->getName());
        self::assertNull($model->getIcon());
        self::assertNull($model->getModule());
        self::assertNull($model->getAuthController());
        self::assertNull($model->getAuthAction());
        self::assertNull($model->getUnlinkController());
        self::assertNull($model->getUnlinkAction());
        self::assertNull($model->getModuleName());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ProviderModel();
        $model->setKey('old')->setName('Old');

        $model->setKey('new')->setName('New');

        self::assertSame('new', $model->getKey());
        self::assertSame('New', $model->getName());
    }
}
