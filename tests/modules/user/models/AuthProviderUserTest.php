<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the auth provider user model class.
 *
 * @package ilch_phpunit
 */
class AuthProviderUserTest extends TestCase
{
    /**
     * Tests if the default values are null.
     */
    public function testDefaultValues()
    {
        $providerUser = new AuthProviderUser();

        self::assertNull($providerUser->getUserId(), 'The default user id is not null.');
        self::assertNull($providerUser->getProvider(), 'The default provider is not null.');
        self::assertNull($providerUser->getIdentifier(), 'The default identifier is not null.');
        self::assertNull($providerUser->getOauthToken(), 'The default oauth token is not null.');
        self::assertNull($providerUser->getOauthTokenSecret(), 'The default oauth token secret is not null.');
        self::assertNull($providerUser->getScreenName(), 'The default screen name is not null.');
        self::assertNull($providerUser->getCreatedAt(), 'The default created at is not null.');
    }

    /**
     * Tests if the user id can be set and returned again.
     */
    public function testSetGetUserId()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setUserId(1);

        self::assertEquals(1, $providerUser->getUserId(), 'The user id was not saved or returned correctly.');
    }

    /**
     * Tests if the provider can be set and returned again.
     */
    public function testSetGetProvider()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setProvider('google');

        self::assertEquals('google', $providerUser->getProvider(), 'The provider was not saved or returned correctly.');
    }

    /**
     * Tests if the identifier can be set and returned again.
     */
    public function testSetGetIdentifier()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setIdentifier('123456789');

        self::assertEquals('123456789', $providerUser->getIdentifier(), 'The identifier was not saved or returned correctly.');
    }

    /**
     * Tests if the oauth token can be set and returned again.
     */
    public function testSetGetOauthToken()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setOauthToken('token');

        self::assertEquals('token', $providerUser->getOauthToken(), 'The oauth token was not saved or returned correctly.');
    }

    /**
     * Tests if the oauth token secret can be set and returned again.
     */
    public function testSetGetOauthTokenSecret()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setOauthTokenSecret('secret');

        self::assertEquals('secret', $providerUser->getOauthTokenSecret(), 'The oauth token secret was not saved or returned correctly.');
    }

    /**
     * Tests if the screen name can be set and returned again.
     */
    public function testSetGetScreenName()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setScreenName('john_doe');

        self::assertEquals('john_doe', $providerUser->getScreenName(), 'The screen name was not saved or returned correctly.');
    }

    /**
     * Tests if the created at value can be set and returned again.
     */
    public function testSetGetCreatedAt()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setCreatedAt('2024-01-01 12:12:12');

        self::assertEquals('2024-01-01 12:12:12', $providerUser->getCreatedAt(), 'The created at value was not saved or returned correctly.');
    }

    /**
     * Tests if the setters are chainable and return the provider user.
     */
    public function testSettersReturnSelf()
    {
        $providerUser = new AuthProviderUser();

        self::assertSame($providerUser, $providerUser->setUserId(1), 'setUserId() does not return the provider user.');
        self::assertSame($providerUser, $providerUser->setProvider('google'), 'setProvider() does not return the provider user.');
        self::assertSame($providerUser, $providerUser->setIdentifier('123456789'), 'setIdentifier() does not return the provider user.');
        self::assertSame($providerUser, $providerUser->setOauthToken('token'), 'setOauthToken() does not return the provider user.');
        self::assertSame($providerUser, $providerUser->setOauthTokenSecret('secret'), 'setOauthTokenSecret() does not return the provider user.');
        self::assertSame($providerUser, $providerUser->setScreenName('john_doe'), 'setScreenName() does not return the provider user.');
        self::assertSame($providerUser, $providerUser->setCreatedAt('2024-01-01 12:12:12'), 'setCreatedAt() does not return the provider user.');
    }

    /**
     * Tests if chaining setters builds a complete provider user.
     */
    public function testChainedSetters()
    {
        $providerUser = (new AuthProviderUser())
            ->setUserId(1)
            ->setProvider('google')
            ->setIdentifier('123456789')
            ->setOauthToken('token')
            ->setOauthTokenSecret('secret')
            ->setScreenName('john_doe')
            ->setCreatedAt('2024-01-01 12:12:12');

        self::assertEquals(1, $providerUser->getUserId(), 'The user id was not saved correctly.');
        self::assertEquals('google', $providerUser->getProvider(), 'The provider was not saved correctly.');
        self::assertEquals('123456789', $providerUser->getIdentifier(), 'The identifier was not saved correctly.');
        self::assertEquals('token', $providerUser->getOauthToken(), 'The oauth token was not saved correctly.');
        self::assertEquals('secret', $providerUser->getOauthTokenSecret(), 'The oauth token secret was not saved correctly.');
        self::assertEquals('john_doe', $providerUser->getScreenName(), 'The screen name was not saved correctly.');
        self::assertEquals('2024-01-01 12:12:12', $providerUser->getCreatedAt(), 'The created at value was not saved correctly.');
    }

    /**
     * Tests if a previously set value can be overwritten.
     */
    public function testOverwriteValues()
    {
        $providerUser = new AuthProviderUser();
        $providerUser->setUserId(1)->setProvider('facebook')->setScreenName('old_name');

        $providerUser->setUserId(2)->setProvider('google')->setScreenName('new_name');

        self::assertEquals(2, $providerUser->getUserId(), 'The user id was not overwritten correctly.');
        self::assertEquals('google', $providerUser->getProvider(), 'The provider was not overwritten correctly.');
        self::assertEquals('new_name', $providerUser->getScreenName(), 'The screen name was not overwritten correctly.');
    }
}
