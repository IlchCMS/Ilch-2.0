<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;
use \Modules\User\Mappers\AuthToken as AuthTokenMapper;
use \Modules\User\Models\AuthToken as AuthTokenModel;

/**
 * Tests the auth token mapper class.
 *
 * @package ilch_phpunit
 */
class AuthTokenTest extends DatabaseTestCase
{
    /**
     * Tests if an auth token model is returned with that selector and the expected content.
     */
    public function testGetAuthToken()
    {
        $mapper = new AuthTokenMapper();

        $authToken = $mapper->getAuthToken('selector');
        $this->assertInstanceOf(AuthTokenModel::class, $authToken);
        $this->assertEquals('token', $authToken->getToken());
        $this->assertEquals(1, $authToken->getUserid());
        $this->assertEquals('2014-01-01 12:12:12', $authToken->getExpires());
    }

    /**
     * Tests if null is returned in case there is no auth token with that selector.
     */
    public function testGetAuthTokenNotExisting()
    {
        $mapper = new AuthTokenMapper();

        $authToken = $mapper->getAuthToken('invalid');
        $this->assertNull($authToken);
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

        $this->assertEquals(3, $mapper->AddAuthToken($model));
        $authToken = $mapper->getAuthToken('selector3');
        $this->assertInstanceOf(AuthTokenModel::class, $authToken);
        $this->assertEquals('token3', $authToken->getToken());
        $this->assertEquals(3, $authToken->getUserid());
        $this->assertEquals('2014-01-02 10:10:10', $authToken->getExpires());
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

        $this->assertEquals(1, $mapper->updateAuthToken($model));
        $authToken = $mapper->getAuthToken('selector2');
        $this->assertInstanceOf(AuthTokenModel::class, $authToken);
        $this->assertEquals('token2', $authToken->getToken());
        $this->assertEquals(4, $authToken->getUserid());
        $this->assertEquals('2014-01-02 10:10:10', $authToken->getExpires());
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

        $this->assertEquals(0, $mapper->updateAuthToken($model));
    }

    /**
     * Tests deleting an auth token.
     */
    public function testDeleteAuthToken()
    {
        $mapper = new AuthTokenMapper();

        $this->assertEquals(1, $mapper->deleteAuthToken('selector2'));
    }

    /**
     * Tests deleting an non-existing auth token - invalid selector.
     */
    public function testDeleteAuthTokenNotExisting()
    {
        $mapper = new AuthTokenMapper();

        $this->assertEquals(0, $mapper->deleteAuthToken('invalid'));
    }

    /**
     * Tests deleting all auth tokens of a user.
     */
    public function testDeleteAllAuthTokenOfUser()
    {
        $mapper = new AuthTokenMapper();

        $this->assertEquals(1, $mapper->deleteAllAuthTokenOfUser(1));
    }

    /**
     * Tests deleting auth tokens with a non-existing userid.
     */
    public function testDeleteAllAuthTokenOfUserNonExisting()
    {
        $mapper = new AuthTokenMapper();

        $this->assertEquals(0, $mapper->deleteAllAuthTokenOfUser(-1));
    }

    /**
     * Tests deleting expired auth tokens.
     */
    public function testDeleteExpiredAuthTokens()
    {
        $mapper = new AuthTokenMapper();

        $this->assertEquals(2, $mapper->deleteExpiredAuthTokens());
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet(__DIR__ . '/../_files/mysql_database.yml');
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
