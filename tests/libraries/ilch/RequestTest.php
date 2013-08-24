<?php
/**
 * Holds class Libraries_Ilch_RequestTest.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Tests the request object.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Libraries_Ilch_RequestTest extends IlchTestCase
{
    /**
     * The object to test with.
     *
     * @var Ilch_Request
     */
    protected $_request;

    /**
     * Initializes an empty request object.
     */
    public function setUp()
    {
        $this->_request = new Ilch_Request();
        $_REQUEST = array();
        $_GET = array();
        $_POST = array();
    }

    /**
     * Tests if a set params array can be given back without a manipulation of
     * the array.
     */
    public function testSetAndGetParams()
    {
        $params = array(
            'name' => 'testuser',
            'email' => 'testuser@testmail.com',
            'id' => 123,
        );
        $this->_request->setParams($params);
        $actualParams = $this->_request->getParams();

        $this->assertEquals($params, $actualParams, 'Param array got manipulated unexpectedly.');
    }

    /**
     * Tests if a single param gets manipulated unexpectedly.
     */
    public function testGetSingleParam()
    {
        $params = array(
            'name' => 'testuser',
            'email' => 'testuser@testmail.com',
            'id' => 123,
        );
        $this->_request->setParams($params);

        $this->assertEquals(123, $actualParam = $this->_request->getParam('id'), 'Param got manipulated unexpectedly.');
    }

    /**
     * Tests if a single param with value NULL gets manipulated unexpectedly.
     */
    public function testGetSingleParamNull()
    {
        $params = array(
            'name' => 'testuser',
            'email' => 'testuser@testmail.com',
            'id' => 123,
            'nullParam' => null,
        );
        $this->_request->setParams($params);

        $this->assertEquals(null, $actualParam = $this->_request->getParam('nullParam'), 'Param got manipulated unexpectedly.');
    }

    /**
     * Tests if a param in the given array with the value NULL gets deleted
     * unexpectedly.
     */
    public function testSaveParamsWithNullValue()
    {
        $params = array(
            'name' => 'testuser',
            'email' => 'testuser@testmail.com',
            'id' => 123,
            'nullParam' => null,
        );
        $this->_request->setParams($params);
        $actualParams = $this->_request->getParams();

        $this->assertArrayHasKey('nullParam', $actualParams, 'The param with value null got deleted.');
    }

    /**
     * Tests if a given param with the value NULL gets deleted unexpectedly.
     */
    public function testSaveSingleParamWithNullValue()
    {
        $this->_request->setParam('nullParam', null);
        $actualParams = $this->_request->getParams();

        $this->assertArrayHasKey('nullParam', $actualParams, 'The param with value null got deleted.');
    }

    /**
     * Tests if a given param gets deleted unexpectedly.
     */
    public function testSaveSingleParam()
    {
        $this->_request->setParam('username', 'testuser');
        $actualParams = $this->_request->getParams();

        $this->assertArrayHasKey('username', $actualParams, 'The saved param got deleted.');
    }

    /**
     * Tests if a set params array can be given back without a manipulation if
     * the input array gets manipulated from outside the class.
     */
    public function testSetAndManipulateSourceParams()
    {
        $params = array(
            'name' => 'testuser',
            'email' => 'testuser@testmail.com',
            'id' => 123,
        );
        $expectedParams = $params;
        $this->_request->setParams($params);
        $params['testvar'] = false;
        $actualParams = $this->_request->getParams();

        $this->assertEquals($expectedParams, $actualParams, 'Param array got manipulated unexpectedly.');
    }

    /**
     * Tests if the module name changed after it got set.
     */
    public function testGetModuleName()
    {
        $this->_request->setModuleName('moduleNameTest');
        $this->assertEquals('moduleNameTest', $this->_request->getModuleName(), 'Modulename changed.');
    }

    /**
     * Tests if the controller name changed after it got set.
     */
    public function testGetControllerName()
    {
        $this->_request->setControllerName('controllerNameTest');
        $this->assertEquals('controllerNameTest', $this->_request->getControllerName(), 'Controllername changed.');
    }

    /**
     * Tests if the action name changed after it got set.
     */
    public function testGetActionName()
    {
        $this->_request->setActionName('actionNameTest');
        $this->assertEquals('actionNameTest', $this->_request->getActionName(), 'Actionname changed.');
    }

    /**
     * Tests if the request object knows if the request was a post request.
     */
    public function testIsPost()
    {
        $params = array(
            'name' => 'testuser',
            'email' => 'testuser@testmail.com',
            'id' => 123,
        );
        $_POST = $params;
        $_REQUEST = $params;

        $this->assertTrue($this->_request->isPost());
    }

    /**
     * Tests if the right GET parameter gets given back.
     */
    public function testGetQuery()
    {
        $_GET['username'] = 'testuser';
        
        $this->assertEquals('testuser', $this->_request->getQuery('username'), 'The request object didnt returned the GET parameter.');
    }

    /**
     * Tests if the right POST parameter gets given back.
     */
    public function testGetPost()
    {
        $_POST['username'] = 'testuser';
        
        $this->assertEquals('testuser', $this->_request->getPost('username'), 'The request object didnt returned the POST parameter.');
    }
}