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
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Tests if an auth token model is returned with that selector and the expected content.
     */
    public function testGetAuthToken()
    {
        $mapper = new AuthTokenMapper();

        $authToken = $mapper->getAuthToken('selector');
        self::assertInstanceOf(AuthTokenModel::class, $authToken);
        self::assertEquals('token', $authToken->getToken());
        self::assertEquals(1, $authToken->getUserid());
        self::assertEquals('2014-01-01 12:12:12', $authToken->getExpires());
    }

    /**
     * Tests if null is returned in case there is no auth token with that selector.
     */
    public function testGetAuthTokenNotExisting()
    {
        $mapper = new AuthTokenMapper();

        $authToken = $mapper->getAuthToken('invalid');
        self::assertNull($authToken);
    }

    /**
     * Tests if an adding an auth token works and that it is returned with that selector and the expected content.
     */
    public function testAddAuthToken()
    {
        $mapper = new AuthTokenMapper();
        $model = new AuthTokenModel();

        $model->setSelector('selector3');
        $model->setToken('token3');
        $model->setUserid(3);
        $model->setExpires('2014-01-02 10:10:10');

        self::assertEquals(3, $mapper->AddAuthToken($model));
        $authToken = $mapper->getAuthToken('selector3');
        self::assertInstanceOf(AuthTokenModel::class, $authToken);
        self::assertEquals('token3', $authToken->getToken());
        self::assertEquals(3, $authToken->getUserid());
        self::assertEquals('2014-01-02 10:10:10', $authToken->getExpires());
    }

    /**
     * Tests if updating an auth token works.
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
     * Tests if trying to update an non-existing auth token returns the expected value.
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
    }

    /**
     * Tests deleting an non-existing auth token - invalid selector.
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
    }

    /**
     * Tests deleting auth tokens with a non-existing userid.
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
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql().$config->getInstallSql();
    }
}
