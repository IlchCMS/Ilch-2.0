<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

class Libraries_Ilch_RouterTest extends PHPUnit_Ilch_TestCase
{

	protected $request = null;

	/**
	 * @var \Ilch\Router
	 */
	protected $router = null;

	public function setUp()
	{
		parent::setUp();
		$this->request = new \Ilch\Request();
		$this->router = new \Ilch\Router($this->request);

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
		$router = $this->router;

		$params = $router->convertParamStringIntoArray('param1/value1/param2/value2');

		$this->assertEquals($params, array('param1' => 'value1', 'param2' => 'value2'));
	}

	public function testMatchModuleController()
	{
		$router = $this->router;

		$expectedResult = array(
			'page/index',
			'module' => 'page',
			'page',
			'controller' => 'index',
			'index',
		);

		$match = $router->matchByRegexp($expectedResult[0]);

		$this->assertTrue(is_array($match), $match, 'Expected match result need to be an array!');
		$this->assertEquals($match, $expectedResult);

	}

	public function testMatchModuleControllerAction()
	{
		$router = $this->router;

		$expectedResult = array(
			'page/index/show',
			'module' => 'page',
			'page',
			'controller' => 'index',
			'index',
			'/show',
			'action' => 'show',
			'show',
		);

		$match = $router->matchByRegexp($expectedResult[0]);

		$this->assertTrue(is_array($match), $match, 'Expected route does not match!');
		$this->assertEquals($match, $expectedResult);

	}

	public function testMatchModuleControllerActionParams()
	{
		$router = $this->router;

		$expectedResult = array(
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

		$match = $router->matchByRegexp($expectedResult[0]);

		var_dump($match);

		$this->assertTrue(is_array($match), $match, 'Expected route does not match!');
		$this->assertEquals($match, $expectedResult);

		$params = $router->convertParamStringIntoArray($match['params']);

		$this->assertEquals($params, array('param1' => 'value1', 'param2' => 'value2'));

	}

}
