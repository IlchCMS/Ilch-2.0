<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Ilch\Registry;
use Ilch\Translator;
use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\AuthProvider as AuthProviderMapper;
use Modules\User\Models\AuthProviderModule;
use Modules\User\Models\AuthProviderUser;
use Modules\User\Models\Provider;

class AuthProviderTest extends DatabaseTestCase
{
    /**
     * @var AuthProviderMapper
     */
    protected AuthProvider $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database_auth_providers.yml');
        $this->out = new AuthProviderMapper();

        if (Registry::get('translator') === null) {
            Registry::set('translator', new Translator());
        }
    }

    /**
     * Tests that getProviders() only returns active providers (module not empty), ordered by name.
     */
    public function testGetProvidersActive()
    {
        $providers = $this->out->getProviders();

        self::assertIsArray($providers);
        self::assertCount(2, $providers);
        self::assertInstanceOf(Provider::class, $providers[0]);
        self::assertEquals('Facebook', $providers[0]->getName());
        self::assertEquals('Google', $providers[1]->getName());
    }

    /**
     * Tests that getProviders(false) also returns providers without a module.
     */
    public function testGetProvidersAll()
    {
        $providers = $this->out->getProviders(false);

        self::assertCount(3, $providers);
        self::assertEquals('Discord', $providers[0]->getName());
        self::assertEquals('', $providers[0]->getModule());
        self::assertEquals('Facebook', $providers[1]->getName());
        self::assertEquals('Google', $providers[2]->getName());
    }

    /**
     * Tests that active providers are enriched with the module link data and the module name.
     *
     * The fixture ships modules_content rows for the two common test locales,
     * so the expected module name depends on the test environment locale.
     */
    public function testGetProvidersFields()
    {
        $providers = $this->out->getProviders();
        $facebook = $providers[0];

        self::assertEquals('facebook', $facebook->getKey());
        self::assertEquals('Facebook', $facebook->getName());
        self::assertEquals('fa-brands fa-facebook', $facebook->getIcon());
        self::assertEquals('login', $facebook->getModule());
        self::assertEquals('auth', $facebook->getAuthController());
        self::assertEquals('facebook', $facebook->getAuthAction());
        self::assertEquals('settings', $facebook->getUnlinkController());
        self::assertEquals('unlinkFacebook', $facebook->getUnlinkAction());

        $locale = \Ilch\Registry::get('translator')->getLocale();
        $expectedModuleName = $locale === 'de_DE' ? 'Anmelden' : 'Login';
        self::assertEquals($expectedModuleName, $facebook->getModuleName());
    }

    /**
     * Tests that getProviders() returns an empty array when no providers exist.
     */
    public function testGetProvidersEmpty()
    {
        $this->out->deleteProvider('facebook');
        $this->out->deleteProvider('google');
        $this->out->deleteProvider('discord');

        $providers = $this->out->getProviders();

        self::assertIsArray($providers);
        self::assertCount(0, $providers);
    }

    /**
     * Tests that getProvider() returns the correct provider.
     */
    public function testGetProvider()
    {
        $provider = $this->out->getProvider('facebook');

        self::assertInstanceOf(Provider::class, $provider);
        self::assertEquals('facebook', $provider->getKey());
        self::assertEquals('Facebook', $provider->getName());
        self::assertEquals('fa-brands fa-facebook', $provider->getIcon());
        self::assertEquals('login', $provider->getModule());
    }

    /**
     * Tests that getProvider() returns null for a non-existent key.
     */
    public function testGetProviderNotFound()
    {
        self::assertNull($this->out->getProvider('nonexistent'));
    }

    /**
     * Tests that getProviderModulesByProvider() returns the module rows of the provider.
     */
    public function testGetProviderModulesByProvider()
    {
        $modules = $this->out->getProviderModulesByProvider('facebook');

        self::assertCount(1, $modules);
        self::assertInstanceOf(AuthProviderModule::class, $modules[0]);
        self::assertEquals('facebook', $modules[0]->getProvider());
        self::assertEquals('login', $modules[0]->getModule());
        self::assertEquals('auth', $modules[0]->getAuthController());
        self::assertEquals('facebook', $modules[0]->getAuthAction());
        self::assertEquals('settings', $modules[0]->getUnlinkController());
        self::assertEquals('unlinkFacebook', $modules[0]->getUnlinkAction());
    }

    /**
     * Tests that getProviderModulesByProvider() returns an empty array for unknown providers.
     */
    public function testGetProviderModulesByProviderNone()
    {
        $modules = $this->out->getProviderModulesByProvider('discord');

        self::assertIsArray($modules);
        self::assertCount(0, $modules);
    }

    /**
     * Tests that updateModule() changes the module of an existing provider.
     */
    public function testUpdateModule()
    {
        self::assertTrue($this->out->updateModule('facebook', 'admin'));

        $provider = $this->out->getProvider('facebook');
        self::assertEquals('admin', $provider->getModule());
    }

    /**
     * Tests that updateModule() returns true when the provider already has the given module
     * (MySQL reports 0 affected rows for no-op updates).
     */
    public function testUpdateModuleSameValue()
    {
        self::assertTrue($this->out->updateModule('facebook', 'login'));

        $provider = $this->out->getProvider('facebook');
        self::assertEquals('login', $provider->getModule());
    }

    /**
     * Tests that updateModule() returns false for a non-existent provider.
     */
    public function testUpdateModuleNotFound()
    {
        self::assertFalse($this->out->updateModule('nonexistent', 'admin'));
    }

    /**
     * Tests that providerAccountIsLinked() detects existing links only.
     */
    public function testProviderAccountIsLinked()
    {
        self::assertTrue($this->out->providerAccountIsLinked('facebook', 'fb-1234'));
        self::assertFalse($this->out->providerAccountIsLinked('facebook', 'unknown-identifier'));
        self::assertFalse($this->out->providerAccountIsLinked('discord', 'fb-1234'));
    }

    /**
     * Tests that hasProviderLinked() detects existing user links only.
     */
    public function testHasProviderLinked()
    {
        self::assertTrue($this->out->hasProviderLinked('facebook', 1));
        self::assertFalse($this->out->hasProviderLinked('google', 1));
        self::assertFalse($this->out->hasProviderLinked('facebook', 9999));
    }

    /**
     * Tests that linkProviderWithUser() inserts a new link.
     */
    public function testLinkProviderWithUser()
    {
        $user = new AuthProviderUser();
        $user->setUserId(3)
            ->setProvider('facebook')
            ->setIdentifier('fb-9999')
            ->setOauthToken('new-token')
            ->setOauthTokenSecret('new-secret')
            ->setScreenName('Testuser3');

        self::assertTrue($this->out->linkProviderWithUser($user));

        self::assertTrue($this->out->hasProviderLinked('facebook', 3));

        $details = $this->out->getLinkedProviderDetails('facebook', 3);
        self::assertInstanceOf(AuthProviderUser::class, $details);
        self::assertEquals(3, $details->getUserId());
        self::assertEquals('facebook', $details->getProvider());
        self::assertEquals('fb-9999', $details->getIdentifier());
        self::assertEquals('new-token', $details->getOauthToken());
        self::assertEquals('new-secret', $details->getOauthTokenSecret());
        self::assertEquals('Testuser3', $details->getScreenName());
        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $details->getCreatedAt());
    }

    /**
     * Tests that linkProviderWithUser() returns false when the link already exists and keeps the old data.
     */
    public function testLinkProviderWithUserDuplicateFails()
    {
        $user = new AuthProviderUser();
        $user->setUserId(1)
            ->setProvider('facebook')
            ->setIdentifier('fb-duplicate');

        self::assertFalse($this->out->linkProviderWithUser($user));

        // The original link must be untouched.
        self::assertEquals('fb-1234', $this->out->getLinkedProviderDetails('facebook', 1)->getIdentifier());
    }

    /**
     * Tests that linkProviderWithUser() refuses to link an identifier that is already
     * linked to a different user, keeping the original link intact.
     */
    public function testLinkProviderWithUserIdentifierTakenByOtherUser()
    {
        $user = new AuthProviderUser();
        $user->setUserId(2)
            ->setProvider('facebook')
            ->setIdentifier('fb-1234');

        self::assertFalse($this->out->linkProviderWithUser($user));

        // The original link must still resolve to user 1.
        self::assertEquals(1, $this->out->getUserIdByProvider('facebook', 'fb-1234'));
        self::assertFalse($this->out->hasProviderLinked('facebook', 2));
    }

    /**
     * Tests that getUserIdByProvider() returns the linked user id.
     */
    public function testGetUserIdByProvider()
    {
        self::assertEquals(1, $this->out->getUserIdByProvider('facebook', 'fb-1234'));
        self::assertNull($this->out->getUserIdByProvider('facebook', 'unknown'));
        self::assertNull($this->out->getUserIdByProvider('discord', 'fb-1234'));
    }

    /**
     * Tests that getLinkedProviderDetails() returns the stored link data.
     */
    public function testGetLinkedProviderDetails()
    {
        $details = $this->out->getLinkedProviderDetails('facebook', 1);

        self::assertInstanceOf(AuthProviderUser::class, $details);
        self::assertEquals(1, $details->getUserId());
        self::assertEquals('facebook', $details->getProvider());
        self::assertEquals('fb-1234', $details->getIdentifier());
        self::assertEquals('Testuser1', $details->getScreenName());
        self::assertEquals('token1', $details->getOauthToken());
        self::assertEquals('secret1', $details->getOauthTokenSecret());
        self::assertEquals('2020-02-01 15:00:00', $details->getCreatedAt());
    }

    /**
     * Tests that getLinkedProviderDetails() returns null for unknown combinations.
     */
    public function testGetLinkedProviderDetailsNotFound()
    {
        self::assertNull($this->out->getLinkedProviderDetails('facebook', 2));
        self::assertNull($this->out->getLinkedProviderDetails('google', 9999));
    }

    /**
     * Tests that unlinkUser() removes the link of one user only.
     */
    public function testUnlinkUser()
    {
        $this->out->unlinkUser('facebook', 1);

        self::assertFalse($this->out->hasProviderLinked('facebook', 1));
        self::assertNull($this->out->getLinkedProviderDetails('facebook', 1));

        // Links of other users remain.
        self::assertTrue($this->out->hasProviderLinked('google', 2));
        self::assertTrue($this->out->hasProviderLinked('discord', 4));
    }

    /**
     * Tests that unlinkUser() with unknown combinations does not affect existing links.
     */
    public function testUnlinkUserNotFound()
    {
        $this->out->unlinkUser('facebook', 2);

        self::assertTrue($this->out->hasProviderLinked('facebook', 1));
        self::assertTrue($this->out->hasProviderLinked('google', 2));
    }

    /**
     * Tests that deleteUser() removes all provider links of a user.
     */
    public function testDeleteUser()
    {
        $this->out->deleteUser(4);

        self::assertFalse($this->out->hasProviderLinked('discord', 4));
        self::assertTrue($this->out->hasProviderLinked('facebook', 1));
    }

    /**
     * Tests that authProvidersModuleExistsForProvider() detects module rows.
     */
    public function testAuthProvidersModuleExistsForProvider()
    {
        self::assertTrue($this->out->authProvidersModuleExistsForProvider('facebook', 'login'));
        self::assertFalse($this->out->authProvidersModuleExistsForProvider('facebook', 'admin'));
    }

    /**
     * Tests that authProvidersModuleExistsForProvider() returns true for an empty module.
     */
    public function testAuthProvidersModuleExistsForProviderEmptyModule()
    {
        self::assertTrue($this->out->authProvidersModuleExistsForProvider('facebook', ''));
    }

    /**
     * Tests that deleteProvider() removes the provider and its linked accounts.
     */
    public function testDeleteProvider()
    {
        self::assertTrue($this->out->deleteProvider('discord'));

        self::assertNull($this->out->getProvider('discord'));
        self::assertFalse($this->out->hasProviderLinked('discord', 4));
    }

    /**
     * Tests that deleteProvider() returns false for a non-existent key.
     */
    public function testDeleteProviderNotFound()
    {
        self::assertFalse($this->out->deleteProvider('nonexistent'));
    }

    /**
     * Returns database schema sql statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}
