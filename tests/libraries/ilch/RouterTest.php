<?php
/**
 * Holds class Libraries_Ilch_RouterTest.
 *
 * @author Marco Bunge
 * @package ilch_phpunit
 */

/**
 * Tests the router object.
 *
 * @author Marco Bunge
 * @package ilch_phpunit
 */
class Libraries_Ilch_RouterTest extends PHPUnit_Ilch_TestCase
{
    /**
	 * @var \Ilch\Request
	 */
	protected $_request;

	/**
	 * @var \Ilch\Router
	 */
	protected $_router;

	public function setUp()
	{
		parent::setUp();

		$this->_request = new \Ilch\Request();
		$this->_router = new \Ilch\Router($this->_request);
	}

	public function testDefaultRegexpPattern()
	{
		$pattern = \Ilch\Router::DEFAULT_REGEX_PATTERN;
		$pattern = '#^' . $pattern . '$#i';

		$this->assertRegexp($pattern, 'module/controller');
		$this->assertRegexp($pattern, 'module/controller/action');
		$this->assertRegexp($pattern, 'module/controller/action/param1/value1/param2/value2');
	}

	public function testParamConvertingIntoArray()
	{
		$params = $this->_router->convertParamStringIntoArray('param1/value1/param2/value2');
		$this->assertEquals($params, array('param1' => 'value1', 'param2' => 'value2'));
	}

	public function testMatchModuleController()
	{
		$expectedResult = array
        (
			'page/index',
			'module' => 'page',
			'page',
			'controller' => 'index',
			'index',
		);

		$match = $this->_router->matchByRegexp($expectedResult[0]);
		$this->assertTrue(is_array($match), $match, 'Expected match result need to be an array!');
	}

	public function testMatchModuleControllerAction()
	{
		$expectedResult = array
        (
			'page/index/show',
			'module' => 'page',
			'page',
			'controller' => 'index',
			'index',
			'/show',
			'action' => 'show',
			'show',
		);

		$match = $this->_router->matchByRegexp($expectedResult[0]);
		$this->assertTrue(is_array($match), $match, 'Expected route does not match!');
	}

	public function testMatchModuleControllerActionParams()
	{
		$expectedResult = array
        (
			'page/index/show/param1/value1/param2/value2',
			'module' => 'page',
			'page',
			'controller' => 'index',
			'index',
			'/show',
			'action' => 'show',
			'show',
			'/param1/value1/param2/value2',
			'params' => 'param1/value1/param2/value2',
			'param1/value1/param2/value2',
		);

		$match = $this->_router->matchByRegexp($expectedResult[0]);
		$this->assertTrue(is_array($match), $match, 'Expected route does not match!');
		$params = $this->_router->convertParamStringIntoArray($match['params']);
		$this->assertEquals($params, array('param1' => 'value1', 'param2' => 'value2'));
	}

    public function testMatchByQuery()
    {
        $route = 'page/index/show/param1/value1/param2/value2';
        $match = $this->_router->matchByQuery($route);
        $this->assertTrue(is_array($match), $match, 'Expected route does not match!');
    }

    public function testUpdateRequestByQuery()
    {
        $route = 'admin/page/index/show/param1/value1/param2/value2';
        $result = $this->_router->matchByQuery($route);
        $this->_router->updateRequest($result);

        $route = 'admin/page/index/show/param1/value1/param2/value2';
        $result = $this->_router->matchByQuery($route);
        $this->_router->updateRequest($result);
    }

    public function testUpdateRequestByRegexp()
    {
        $route = 'page/index/show/param1/value1/param2/value2';
        $result = $this->_router->matchByRegexp($route);
        $this->_router->updateRequest($result);

        $route = 'admin/page/index/show/param1/value1/param2/value2';
        $result = $this->_router->matchByRegexp($route);
        $this->_router->updateRequest($result);
    }
}
