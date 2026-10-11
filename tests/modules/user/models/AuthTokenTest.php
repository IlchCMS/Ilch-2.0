<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the auth token model class.
 *
 * @package ilch_phpunit
 */
class AuthTokenTest extends TestCase
{
    /**
     * Tests if the default values are null.
     */
    public function testDefaultValues()
    {
        $token = new AuthToken();

        self::assertNull($token->getSelector(), 'The default selector is not null.');
        self::assertNull($token->getToken(), 'The default token is not null.');
        self::assertNull($token->getUserid(), 'The default userid is not null.');
        self::assertNull($token->getExpires(), 'The default expires is not null.');
    }

    /**
     * Tests if the selector can be set and returned again.
     */
    public function testSetGetSelector()
    {
        $token = new AuthToken();
        $token->setSelector('selector');

        self::assertEquals('selector', $token->getSelector(), 'The selector was not saved or returned correctly.');
    }

    /**
     * Tests if the token can be set and returned again.
     */
    public function testSetGetToken()
    {
        $token = new AuthToken();
        $token->setToken('token');

        self::assertEquals('token', $token->getToken(), 'The token was not saved or returned correctly.');
    }

    /**
     * Tests if the userid can be set and returned again.
     */
    public function testSetGetUserid()
    {
        $token = new AuthToken();
        $token->setUserid(1);

        self::assertEquals(1, $token->getUserid(), 'The userid was not saved or returned correctly.');
    }

    /**
     * Tests if the expires value can be set and returned again.
     */
    public function testSetGetExpires()
    {
        $token = new AuthToken();
        $token->setExpires('2024-01-01 12:12:12');

        self::assertEquals('2024-01-01 12:12:12', $token->getExpires(), 'The expires value was not saved or returned correctly.');
    }

    /**
     * Tests if setUserid() casts the value to int.
     */
    public function testSetUseridCastsToInt()
    {
        $token = new AuthToken();
        $token->setUserid('42');

        self::assertSame(42, $token->getUserid(), 'The userid was not cast to int.');
        self::assertIsInt($token->getUserid(), 'The userid is not an int.');
    }

    /**
     * Tests if the setters are chainable and return the token.
     */
    public function testSettersReturnSelf()
    {
        $token = new AuthToken();

        self::assertSame($token, $token->setSelector('selector'), 'setSelector() does not return the token.');
        self::assertSame($token, $token->setToken('token'), 'setToken() does not return the token.');
        self::assertSame($token, $token->setUserid(1), 'setUserid() does not return the token.');
        self::assertSame($token, $token->setExpires('2024-01-01 12:12:12'), 'setExpires() does not return the token.');
    }

    /**
     * Tests if chaining setters builds a complete token.
     */
    public function testChainedSetters()
    {
        $token = (new AuthToken())
            ->setSelector('selector')
            ->setToken('token')
            ->setUserid(1)
            ->setExpires('2024-01-01 12:12:12');

        self::assertEquals('selector', $token->getSelector(), 'The selector was not saved correctly.');
        self::assertEquals('token', $token->getToken(), 'The token was not saved correctly.');
        self::assertEquals(1, $token->getUserid(), 'The userid was not saved correctly.');
        self::assertEquals('2024-01-01 12:12:12', $token->getExpires(), 'The expires value was not saved correctly.');
    }

    /**
     * Tests if a previously set value can be overwritten.
     */
    public function testOverwriteValues()
    {
        $token = new AuthToken();
        $token->setSelector('old')->setUserid(1);

        $token->setSelector('new')->setUserid(2);

        self::assertEquals('new', $token->getSelector(), 'The selector was not overwritten correctly.');
        self::assertEquals(2, $token->getUserid(), 'The userid was not overwritten correctly.');
    }
}
