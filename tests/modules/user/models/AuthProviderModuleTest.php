<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the auth provider module model class.
 *
 * @package ilch_phpunit
 */
class AuthProviderModuleTest extends TestCase
{
    /**
     * Tests if the default values are null.
     */
    public function testDefaultValues()
    {
        $module = new AuthProviderModule();

        self::assertNull($module->getName(), 'The default name is not null.');
        self::assertNull($module->getProvider(), 'The default provider is not null.');
        self::assertNull($module->getModule(), 'The default module is not null.');
        self::assertNull($module->getAuthController(), 'The default auth controller is not null.');
        self::assertNull($module->getAuthAction(), 'The default auth action is not null.');
        self::assertNull($module->getUnlinkController(), 'The default unlink controller is not null.');
        self::assertNull($module->getUnlinkAction(), 'The default unlink action is not null.');
    }

    /**
     * Tests if the name can be set and returned again.
     */
    public function testSetGetName()
    {
        $module = new AuthProviderModule();
        $module->setName('User');

        self::assertEquals('User', $module->getName(), 'The name was not saved or returned correctly.');
    }

    /**
     * Tests if the provider can be set and returned again.
     */
    public function testSetGetProvider()
    {
        $module = new AuthProviderModule();
        $module->setProvider('google');

        self::assertEquals('google', $module->getProvider(), 'The provider was not saved or returned correctly.');
    }

    /**
     * Tests if the module can be set and returned again.
     */
    public function testSetGetModule()
    {
        $module = new AuthProviderModule();
        $module->setModule('user');

        self::assertEquals('user', $module->getModule(), 'The module was not saved or returned correctly.');
    }

    /**
     * Tests if the auth controller can be set and returned again.
     */
    public function testSetGetAuthController()
    {
        $module = new AuthProviderModule();
        $module->setAuthController('auth');

        self::assertEquals('auth', $module->getAuthController(), 'The auth controller was not saved or returned correctly.');
    }

    /**
     * Tests if the auth action can be set and returned again.
     */
    public function testSetGetAuthAction()
    {
        $module = new AuthProviderModule();
        $module->setAuthAction('login');

        self::assertEquals('login', $module->getAuthAction(), 'The auth action was not saved or returned correctly.');
    }

    /**
     * Tests if the unlink controller can be set and returned again.
     */
    public function testSetGetUnlinkController()
    {
        $module = new AuthProviderModule();
        $module->setUnlinkController('auth');

        self::assertEquals('auth', $module->getUnlinkController(), 'The unlink controller was not saved or returned correctly.');
    }

    /**
     * Tests if the unlink action can be set and returned again.
     */
    public function testSetGetUnlinkAction()
    {
        $module = new AuthProviderModule();
        $module->setUnlinkAction('unlink');

        self::assertEquals('unlink', $module->getUnlinkAction(), 'The unlink action was not saved or returned correctly.');
    }

    /**
     * Tests if the setters are chainable and return the module.
     */
    public function testSettersReturnSelf()
    {
        $module = new AuthProviderModule();

        self::assertSame($module, $module->setName('User'), 'setName() does not return the module.');
        self::assertSame($module, $module->setProvider('google'), 'setProvider() does not return the module.');
        self::assertSame($module, $module->setModule('user'), 'setModule() does not return the module.');
        self::assertSame($module, $module->setAuthController('auth'), 'setAuthController() does not return the module.');
        self::assertSame($module, $module->setAuthAction('login'), 'setAuthAction() does not return the module.');
        self::assertSame($module, $module->setUnlinkController('auth'), 'setUnlinkController() does not return the module.');
        self::assertSame($module, $module->setUnlinkAction('unlink'), 'setUnlinkAction() does not return the module.');
    }

    /**
     * Tests if chaining setters builds a complete module.
     */
    public function testChainedSetters()
    {
        $module = (new AuthProviderModule())
            ->setName('User')
            ->setProvider('google')
            ->setModule('user')
            ->setAuthController('auth')
            ->setAuthAction('login')
            ->setUnlinkController('auth')
            ->setUnlinkAction('unlink');

        self::assertEquals('User', $module->getName(), 'The name was not saved correctly.');
        self::assertEquals('google', $module->getProvider(), 'The provider was not saved correctly.');
        self::assertEquals('user', $module->getModule(), 'The module was not saved correctly.');
        self::assertEquals('auth', $module->getAuthController(), 'The auth controller was not saved correctly.');
        self::assertEquals('login', $module->getAuthAction(), 'The auth action was not saved correctly.');
        self::assertEquals('auth', $module->getUnlinkController(), 'The unlink controller was not saved correctly.');
        self::assertEquals('unlink', $module->getUnlinkAction(), 'The unlink action was not saved correctly.');
    }

    /**
     * Tests if a previously set value can be overwritten.
     */
    public function testOverwriteValues()
    {
        $module = new AuthProviderModule();
        $module->setName('Old')->setProvider('facebook')->setModule('forum');

        $module->setName('New')->setProvider('google')->setModule('user');

        self::assertEquals('New', $module->getName(), 'The name was not overwritten correctly.');
        self::assertEquals('google', $module->getProvider(), 'The provider was not overwritten correctly.');
        self::assertEquals('user', $module->getModule(), 'The module was not overwritten correctly.');
    }
}
