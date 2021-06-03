<?php
/**
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the request object.
 *
 * @package ilch_phpunit
 */
class RequestTest extends TestCase
{
    /**
     * The object to test with.
     *
     * @var Request
     */
    protected $request;

    /**
     * Initializes an empty request object.
     */
    public function setUp()
    {
        $this->request = new Request();
        $_REQUEST = [];
        $_GET = [];
        $_POST = [];
    }

    /**
     * Tests if a set params array can be given back without a manipulation of
     * the array.
     */
    public function testSetAndGetParams()
    {
        $params = [
            'name'  => 'testuser',
            'email' => 'testuser@testmail.com',
            'id'    => 123,
        ];
        $this->request->setParams($params);
        $actualParams = $this->request->getParams();

        self::assertEquals($params, $actualParams, 'Param array got manipulated unexpectedly.');
    }

    /**
     * Tests if a single param gets manipulated unexpectedly.
     */
    public function testGetSingleParam()
    {
        $params = [
            'name'  => 'testuser',
            'email' => 'testuser@testmail.com',
            'id'    => 123,
        ];
        $this->request->setParams($params);

        self::assertEquals(123, $actualParam = $this->request->getParam('id'), 'Param got manipulated unexpectedly.');
    }

    /**
     * Tests if a single param with value NULL gets manipulated unexpectedly.
     */
    public function testGetSingleParamNull()
    {
        $params = [
            'name'      => 'testuser',
            'email'     => 'testuser@testmail.com',
            'id'        => 123,
            'nullParam' => null,
        ];
        $this->request->setParams($params);

        self::assertEquals(
            null,
            $actualParam = $this->request->getParam('nullParam'),
            'Param got manipulated unexpectedly.'
        );
    }

    /**
     * Tests if a param in the given array with the value NULL gets deleted
     * unexpectedly.
     */
    public function testSaveParamsWithNullValue()
    {
        $params = [
            'name'      => 'testuser',
            'email'     => 'testuser@testmail.com',
            'id'        => 123,
            'nullParam' => null,
        ];
        $this->request->setParams($params);
        $actualParams = $this->request->getParams();

        self::assertArrayHasKey('nullParam', $actualParams, 'The param with value null got deleted.');
    }

    /**
     * Tests if a given param with the value NULL gets deleted unexpectedly.
     */
    public function testSaveSingleParamWithNullValue()
    {
        $this->request->setParam('nullParam', null);
        $actualParams = $this->request->getParams();

        self::assertArrayHasKey('nullParam', $actualParams, 'The param with value null got deleted.');
    }

    /**
     * Tests if a given param gets deleted unexpectedly.
     */
    public function testSaveSingleParam()
    {
        $this->request->setParam('username', 'testuser');
        $actualParams = $this->request->getParams();

        self::assertArrayHasKey('username', $actualParams, 'The saved param got deleted.');
    }

    /**
     * Tests if a set params array can be given back without a manipulation if
     * the input array gets manipulated from outside the class.
     */
    public function testSetAndManipulateSourceParams()
    {
        $params = [
            'name'  => 'testuser',
            'email' => 'testuser@testmail.com',
            'id'    => 123,
        ];
        $expectedParams = $params;
        $this->request->setParams($params);
        $params['testvar'] = false;
        $actualParams = $this->request->getParams();

        self::assertEquals($expectedParams, $actualParams, 'Param array got manipulated unexpectedly.');
    }

    /**
     * Tests if the module name changed after it got set.
     */
    public function testGetModuleName()
    {
        $this->request->setModuleName('moduleNameTest');
        self::assertEquals('moduleNameTest', $this->request->getModuleName(), 'Modulename changed.');
    }

    /**
     * Tests if the controller name changed after it got set.
     */
    public function testGetControllerName()
    {
        $this->request->setControllerName('controllerNameTest');
        self::assertEquals('controllerNameTest', $this->request->getControllerName(), 'Controllername changed.');
    }

    /**
     * Tests if the action name changed after it got set.
     */
    public function testGetActionName()
    {
        $this->request->setActionName('actionNameTest');
        self::assertEquals('actionNameTest', $this->request->getActionName(), 'Actionname changed.');
    }

    /**
     * Tests if the request object knows if the request was a post request.
     */
    public function testIsPost()
    {
        $params = [
            'name'  => 'testuser',
            'email' => 'testuser@testmail.com',
            'id'    => 123,
        ];
        $_POST = $params;
        $_REQUEST = $params;

        self::assertTrue($this->request->isPost());
    }

    /**
     * Tests if the right GET parameter gets given back.
     */
    public function testGetQuery()
    {
        $_GET['username'] = 'testuser';

        self::assertEquals(
            'testuser',
            $this->request->getQuery('username'),
            'The request object didnt returned the GET parameter.'
        );
    }

    /**
     * Tests if the right POST parameter gets given back.
     */
    public function testGetPost()
    {
        $_POST['username'] = 'testuser';

        self::assertEquals(
            'testuser',
            $this->request->getPost('username'),
            'The request object didnt returned the POST parameter.'
        );
    }
}
