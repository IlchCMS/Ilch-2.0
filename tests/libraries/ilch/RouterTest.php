<?php
/**
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the router object.
 *
 * @package ilch_phpunit
 */
class RouterTest extends TestCase
{
    /**
     * @var \Ilch\Request
     */
    protected $request;

    /**
     * @var \Ilch\Router
     */
    protected $router;

    public function setUp()
    {
        parent::setUp();

        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    public function testDefaultRegexpPattern()
    {
        $pattern = Router::DEFAULT_REGEX_PATTERN;
        $pattern = '#^' . $pattern . '$#i';

        $this->assertRegexp($pattern, 'module/controller');
        $this->assertRegexp($pattern, 'module/controller/action');
        $this->assertRegexp($pattern, 'module/controller/action/param1/value1/param2/value2');
    }

    public function testParamConvertingIntoArray()
    {
        $params = $this->router->convertParamStringIntoArray('param1/value1/param2/value2');
        $this->assertEquals($params, array('param1' => 'value1', 'param2' => 'value2'));
    }

    public function testMatchModuleController()
    {
        $expectedResult = array
        (
            'page/index',
            'module'     => 'page',
            'page',
            'controller' => 'index',
            'index',
        );

        $match = $this->router->matchByRegexp($expectedResult[0]);
        $this->assertTrue(is_array($match), $match, 'Expected match result need to be an array!');
    }

    public function testMatchModuleControllerAction()
    {
        $expectedResult = array
        (
            'page/index/show',
            'module'     => 'page',
            'page',
            'controller' => 'index',
            'index',
            '/show',
            'action'     => 'show',
            'show',
        );

        $match = $this->router->matchByRegexp($expectedResult[0]);
        $this->assertTrue(is_array($match), $match, 'Expected route does not match!');
    }

    public function testMatchModuleControllerActionParams()
    {
        $expectedResult = array
        (
            'page/index/show/param1/value1/param2/value2',
            'module'     => 'page',
            'page',
            'controller' => 'index',
            'index',
            '/show',
            'action'     => 'show',
            'show',
            '/param1/value1/param2/value2',
            'params'     => 'param1/value1/param2/value2',
            'param1/value1/param2/value2',
        );

        $match = $this->router->matchByRegexp($expectedResult[0]);
        $this->assertTrue(is_array($match), $match, 'Expected route does not match!');
        $params = $this->router->convertParamStringIntoArray($match['params']);
        $this->assertEquals($params, array('param1' => 'value1', 'param2' => 'value2'));
    }

    public function testMatchByQuery()
    {
        $route = 'page/index/show/param1/value1/param2/value2';
        $match = $this->router->matchByQuery($route);
        $this->assertTrue(is_array($match), $match, 'Expected route does not match!');
    }

    public function testUpdateRequestByQuery()
    {
        $route = 'admin/page/index/show/param1/value1/param2/value2';
        $result = $this->router->matchByQuery($route);
        $this->router->updateRequest($result);

        $route = 'admin/page/index/show/param1/value1/param2/value2';
        $result = $this->router->matchByQuery($route);
        $this->router->updateRequest($result);
    }

    public function testUpdateRequestByRegexp()
    {
        $route = 'page/index/show/param1/value1/param2/value2';
        $result = $this->router->matchByRegexp($route);
        $this->router->updateRequest($result);

        $route = 'admin/page/index/show/param1/value1/param2/value2';
        $result = $this->router->matchByRegexp($route);
        $this->router->updateRequest($result);
    }
}
