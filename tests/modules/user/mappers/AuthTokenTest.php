<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\User\Models\AuthToken as AuthTokenModel;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the auth token mapper class.
 *
 * @package ilch_phpunit
 */
class AuthTokenTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Tests that an auth token model is returned for an existing selector
     * with the expected content.
     */
    public function testGetAuthToken()
    {
        $mapper = new AuthTokenMapper();

        $authToken = $mapper->getAuthToken('selector');
        self::assertInstanceOf(AuthTokenModel::class, $authToken);
        self::assertEquals('selector', $authToken->getSelector());
        self::assertEquals('token', $authToken->getToken());
        self::assertEquals(1, $authToken->getUserid());
        self::assertEquals('2014-01-01 12:12:12', $authToken->getExpires());
    }

    /**
     * Tests that null is returned in case there is no auth token with that selector.
     */
    public function testGetAuthTokenNotExisting()
    {
        $mapper = new AuthTokenMapper();

        $authToken = $mapper->getAuthToken('invalid');
        self::assertNull($authToken);
    }

    /**
     * Tests that adding an auth token works and that it can be retrieved
     * with that selector and the expected content.
     */
    public function testAddAuthToken()
    {
        $mapper = new AuthTokenMapper();
        $model = new AuthTokenModel();

        $model->setSelector('selector3');
        $model->setToken('token3');
        $model->setUserid(3);
        $model->setExpires('2014-01-02 10:10:10');

        // The fixture contains 2 auth tokens (ids 1-2), so the new row gets id 3.
        self::assertEquals(3, $mapper->addAuthToken($model));
        $authToken = $mapper->getAuthToken('selector3');
        self::assertInstanceOf(AuthTokenModel::class, $authToken);
        self::assertEquals('selector3', $authToken->getSelector());
        self::assertEquals('token3', $authToken->getToken());
        self::assertEquals(3, $authToken->getUserid());
        self::assertEquals('2014-01-02 10:10:10', $authToken->getExpires());
    }

    /**
     * Tests that updating an auth token works.
     */
    public function testUpdateAuthToken()
    {
        $mapper = new AuthTokenMapper();
        $model = new AuthTokenModel();

        $model->setSelector('selector2');
        $model->setToken('token2');
        $model->setUserid(4);
        $model->setExpires('2014-01-02 10:10:10');

        self::assertEquals(1, $mapper->updateAuthToken($model));
        $authToken = $mapper->getAuthToken('selector2');
        self::assertInstanceOf(AuthTokenModel::class, $authToken);
        self::assertEquals('token2', $authToken->getToken());
        self::assertEquals(4, $authToken->getUserid());
        self::assertEquals('2014-01-02 10:10:10', $authToken->getExpires());
    }

    /**
     * Tests that updating an auth token does not affect the other auth tokens.
     */
    public function testUpdateAuthTokenDoesNotAffectOthers()
    {
        $mapper = new AuthTokenMapper();
        $model = new AuthTokenModel();

        $model->setSelector('selector');
        $model->setToken('tokenUpdated');
        $model->setUserid(4);
        $model->setExpires('2014-01-02 10:10:10');

        self::assertEquals(1, $mapper->updateAuthToken($model));

        $other = $mapper->getAuthToken('selector2');
        self::assertInstanceOf(AuthTokenModel::class, $other);
        self::assertEquals('token', $other->getToken());
        self::assertEquals(2, $other->getUserid());
        self::assertEquals('2014-01-01 12:12:12', $other->getExpires());
    }

    /**
     * Tests that trying to update a non-existing auth token returns the expected value.
     */
    public function testUpdateAuthTokenNotExisting()
    {
        $mapper = new AuthTokenMapper();
        $model = new AuthTokenModel();

        $model->setSelector('invalid');
        $model->setToken('token2');
        $model->setUserid(4);
        $model->setExpires('2014-01-02 10:10:10');

        self::assertEquals(0, $mapper->updateAuthToken($model));
    }

    /**
     * Tests deleting an auth token.
     */
    public function testDeleteAuthToken()
    {
        $mapper = new AuthTokenMapper();

        self::assertEquals(1, $mapper->deleteAuthToken('selector2'));
        self::assertNull($mapper->getAuthToken('selector2'));
    }

    /**
     * Tests deleting a non-existing auth token - invalid selector.
     */
    public function testDeleteAuthTokenNotExisting()
    {
        $mapper = new AuthTokenMapper();

        self::assertEquals(0, $mapper->deleteAuthToken('invalid'));
    }

    /**
     * Tests deleting all auth tokens of a user.
     */
    public function testDeleteAllAuthTokenOfUser()
    {
        $mapper = new AuthTokenMapper();

        self::assertEquals(1, $mapper->deleteAllAuthTokenOfUser(1));
        self::assertNull($mapper->getAuthToken('selector'));
    }

    /**
     * Tests deleting auth tokens of a non-existing user.
     */
    public function testDeleteAllAuthTokenOfUserNonExisting()
    {
        $mapper = new AuthTokenMapper();

        self::assertEquals(0, $mapper->deleteAllAuthTokenOfUser(-1));
    }

    /**
     * Tests deleting expired auth tokens.
     */
    public function testDeleteExpiredAuthTokens()
    {
        $mapper = new AuthTokenMapper();

        self::assertEquals(2, $mapper->deleteExpiredAuthTokens());
    }

    /**
     * Tests that only expired auth tokens are deleted and a not yet expired
     * auth token is kept.
     */
    public function testDeleteExpiredAuthTokensKeepsNotExpired()
    {
        $mapper = new AuthTokenMapper();
        $model = new AuthTokenModel();

        // Add an auth token that is not yet expired.
        $model->setSelector('selector4');
        $model->setToken('token4');
        $model->setUserid(3);
        $model->setExpires('2099-01-01 00:00:00');

        // The fixture contains 2 auth tokens (ids 1-2), so the new row gets id 3.
        self::assertEquals(3, $mapper->addAuthToken($model));

        // The new token is retrievable right after the insert.
        self::assertInstanceOf(AuthTokenModel::class, $mapper->getAuthToken('selector4'));

        // Only the two expired (2014) tokens are deleted ...
        self::assertEquals(2, $mapper->deleteExpiredAuthTokens());
        self::assertNull($mapper->getAuthToken('selector'));
        self::assertNull($mapper->getAuthToken('selector2'));

        // ... but the not yet expired one is still present.
        $remaining = $mapper->getAuthToken('selector4');
        self::assertInstanceOf(AuthTokenModel::class, $remaining);
        self::assertEquals('token4', $remaining->getToken());
        self::assertEquals(3, $remaining->getUserid());
        self::assertEquals('2099-01-01 00:00:00', $remaining->getExpires());

        // Exactly one token (the not yet expired one) remains in the table.
        $rows = $this->db->select('*')->from('auth_tokens')->execute()->fetchList();
        self::assertCount(1, $rows);
    }

    /**
     * Returns database schema sql statements to initialize database
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
