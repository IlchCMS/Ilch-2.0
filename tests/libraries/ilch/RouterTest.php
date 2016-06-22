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

        $this->assertRegExp($pattern, 'module/controller');
        $this->assertRegExp($pattern, 'module/controller/action');
        $this->assertRegExp($pattern, 'module/controller/action/param1/value1/param2/value2');
    }

    public function testParamConvertingIntoArray()
    {
        $params = $this->router->convertParamStringIntoArray('param1/value1/param2/value2');
        $this->assertEquals($params, ['param1' => 'value1', 'param2' => 'value2']);
    }

    public function testMatchModuleController()
    {
        $expectedResult = [
            'page/index',
            'module'     => 'page',
            'page',
            'controller' => 'index',
            'index',
        ];

        $match = $this->router->matchByRegexp($expectedResult[0]);
        $this->assertTrue(is_array($match), 'Expected match result need to be an array!');
    }

    public function testMatchModuleControllerAction()
    {
        $expectedResult = [
            'page/index/show',
            'module'     => 'page',
            'page',
            'controller' => 'index',
            'index',
            '/show',
            'action'     => 'show',
            'show',
        ];

        $match = $this->router->matchByRegexp($expectedResult[0]);
        $this->assertTrue(is_array($match), 'Expected route does not match!');
    }

    public function testMatchModuleControllerActionParams()
    {
        $expectedResult = [
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
        ];

        $match = $this->router->matchByRegexp($expectedResult[0]);
        $this->assertTrue(is_array($match), 'Expected route does not match!');
        $params = $this->router->convertParamStringIntoArray($match['params']);
        $this->assertEquals($params, ['param1' => 'value1', 'param2' => 'value2']);
    }

    public function testMatchByQuery()
    {
        $route = 'page/index/show/param1/value1/param2/value2';
        $match = $this->router->matchByQuery($route);
        $this->assertTrue(is_array($match), 'Expected route does not match!');
    }

    /**
     * @dataProvider dpForTestUpdateRequestByQuery
     *
     * @param string $route
     * @param bool $expectIsAdmin
     * @param string $expectedModule
     * @param string $expectedController
     * @param string $expectedAction
     * @param array $expectedParams
     */
    public function testUpdateRequestByQuery(
        $route,
        $expectIsAdmin,
        $expectedModule,
        $expectedController,
        $expectedAction,
        $expectedParams
    ) {
        $result = $this->router->matchByQuery($route);
        $this->router->updateRequest($result);

        $this->assertSame($expectIsAdmin, $this->request->isAdmin());
        $this->assertSame($expectedModule, $this->request->getModuleName());
        $this->assertSame($expectedController, $this->request->getControllerName());
        $this->assertSame($expectedAction, $this->request->getActionName());
        $this->assertSame($expectedParams, $this->request->getParams());
    }

    /**
     * @return array
     */
    public function dpForTestUpdateRequestByQuery()
    {
        return [
            'route without params' => [
                'route'              => 'page/index/show',
                'expectIsAdmin'      => false,
                'expectedModule'     => 'page',
                'expectedController' => 'index',
                'expectedAction'     => 'show',
                'expectedParams'     => []
            ],
            'route with params' => [
                'route'              => 'page/index/show/param1/value1/param2/value2',
                'expectIsAdmin'      => false,
                'expectedModule'     => 'page',
                'expectedController' => 'index',
                'expectedAction'     => 'show',
                'expectedParams'     => ['param1' => 'value1', 'param2' => 'value2']
            ],
            'admin route with params' => [
                'route'              => 'admin/page/index/show/param1/value1/param2/value2',
                'expectIsAdmin'      => true,
                'expectedModule'     => 'page',
                'expectedController' => 'index',
                'expectedAction'     => 'show',
                'expectedParams'     => ['param1' => 'value1', 'param2' => 'value2']
            ],
        ];
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
