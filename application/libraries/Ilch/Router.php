<?php
/**
 * @author Meyer Dominik
 * @author Bunge Marco
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

/**
 * Class Router
 * @package Ilch
 */
class Router
{

    const DEFAULT_REGEX_PATTERN = '/?(?P<admin>admin)?/?(?P<module>\w+)/(?P<controller>\w+)(/(?P<action>\w+)?)?(/(?P<params>[a-zA-Z0-9_/]+)?)?';
    const DEFAULT_MATCH_STRATEGY = 'matchByQuery';

    private $_query = null;

    /**
     * @var \ArrayObject|null
     */
    private $_config = null;

    /**
     * @var Request|null
     */
    private $_request = null;

    /**
     * Injects request and config object.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->_request = $request;
        $this->_config = new \ArrayObject();
        $this->_config->offsetSet('_DEFAULT_', ['strategy' => self::DEFAULT_MATCH_STRATEGY, 'pattern' => self::DEFAULT_REGEX_PATTERN]);
    }

    /**
     * @param $routeName
     * @return bool
     */
    public function hasConfigItem($routeName)
    {
        return $this->_config->offsetExists($routeName);
    }

    /**
     * TODO: Value validation for valid route configuration!
     * @param $routeName
     * @param array $value
     * @return bool
     */
    public function addConfigItem($routeName, array $value)
    {
        if (!$this->hasConfigItem($routeName)) {
            $this->_config->offsetSet($routeName, $value);
            return true;
        }

        return false;
    }

    /**
     * @param $routeName
     */
    public function removeConfigItem($routeName)
    {
        $this->_config->offsetUnset($routeName);
    }

    /**
     * Get Query
     * @return mixed
     */
    public function getQuery()
    {
        return $this->_query;
    }

    protected function setQuery($query)
    {
        $this->_query = $query;
    }

    /**
     * TODO: Auslagern in den Bootstrap. Dr route muss nicht automatisch wissen woher seine Route kommt. Eine konkrete route ist für den Router abstrakt.
     * Receive route from http request
     * @return string
     */
    public function getRouteByRequest()
    {
        $route = substr($_SERVER['REQUEST_URI'], strlen(REWRITE_BASE));
        $route = str_replace('index.php/', '', $route);
        $route = trim(str_replace('index.php', '', $route), '/');
        return $route;
    }

    /**
     * Match given route by regular expression
     * @param $route
     * @param array $params
     * @throws \Exception
     * @return array
     */
    public function matchByRegexp($route, array $params = array())
    {
        $matches = [];

        $pattern = !array_key_exists('pattern', $params) ? self::DEFAULT_REGEX_PATTERN : $params['pattern'];

        $matched = preg_match(
            '#^' . $pattern . '$#i',
            $route,
            $matches
        );

        if (count($matches) === 0) {
            throw new \Exception(sprintf('Expected route "%s" does not match with pattern "%s"', $route, $pattern));
        }

        return $matches;
    }

    /**
     * Converts a valid routed string of params into array
     * @param $string
     * @return array
     */
    public function convertParamStringIntoArray($string)
    {
        $array = explode('/', $string);
        $result = array();
        $prevKey = null;

        foreach ($array as $key => $value) {
            if ($key % 2 === 0) {
                $prevKey = $value;
            }
            if ($key % 2 === 1) {
                $result[$prevKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Fills the request object if rewrite is possible.
     *
     * @param string $route
     * @param array $params
     * @return array
     */
    public function matchByQuery($route, array $params = array())
    {
        $result = [];
        $queryParts = explode('/', $route);

        $i = 0;

        if ($queryParts[0] == 'admin') {
            $result['admin'] = $queryParts[0];
            unset($queryParts[0]);
            $i = 1;
        }

        if (isset($queryParts[$i])) {
            $result['module'] = $queryParts[$i];
            unset($queryParts[$i]);
        }

        $i++;

        if (isset($queryParts[$i])) {
            $result['controller'] = $queryParts[$i];
            unset($queryParts[$i]);
        }

        $i++;

        if (isset($queryParts[$i])) {
            $result['action'] = $queryParts[$i];
            unset($queryParts[$i]);
        }

        if (!empty($queryParts)) {
            $result['params'] = implode('/',$queryParts);
        }

        return $result;
    }

    /**
     * Match route by strategy
     * @param $route
     * @param array $params
     * @return mixed
     */
    public function matchStrategy($route, array $params = array())
    {
        $callback = array();
        $strategy = array_key_exists('strategy', $params) ? $params['strategy'] : self::DEFAULT_MATCH_STRATEGY;

        //select default strategy delivered by router
        if (is_string($strategy) && strtolower(substr($strategy, 0, 5)) === 'match' && method_exists($this, $strategy)) {
            $callback = array($this, $strategy);
        }

        if (is_callable($strategy)) {
            $callback = $strategy;
        }

        return call_user_func_array($callback, [$route, $params]);
    }

    /**
     * @param $route
     * @return array
     */
    public function match($route)
    {
        $results = array();
        foreach ($this->_config as $routeName => $config) {
            if (!array_key_exists('strategy', $config)) {
                $config['strategy'] = self::DEFAULT_MATCH_STRATEGY;
            }
            $results[] = $this->matchStrategy($route, $config);
        }
        return $results;
    }

    /**
     * Updates Request
     * @param $result
     */
    public function updateRequest($result){
        if(array_key_exists('admin', $result) && strlen($result['admin'] > 0)){
            $this->_request->setIsAdmin(true);
        }

        if(array_key_exists('module', $result)){
            $this->_request->setModuleName($result['module']);
        }

        if(array_key_exists('controller', $result)){
            $this->_request->setControllerName($result['controller']);
        }

        if(array_key_exists('action', $result)){
            $this->_request->setActionName($result['action']);
        }

        if(array_key_exists('params', $result)){
            $params = $this->convertParamStringIntoArray($result['params']);
            foreach($params as $key => $value){
                $this->_request->setParam($key,$value);
            }
        }


    }

    /**
     * Fills the request object with the best matched route.
     */
    public function execute()
    {

        $this->_request->setModuleName('page');
        $this->_request->setControllerName('index');
        $this->_request->setActionName('index');

        $query = $this->getRouteByRequest();

        if (!empty($query)) {
            $result = $this->match($query);
            $this->updateRequest($result[0]);
        }
    }

    /**
     * @param \Ilch\Request|null $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @return \Ilch\Request|null
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param \ArrayObject|null $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * @return \ArrayObject|null
     */
    public function getConfig()
    {
        return $this->_config;
    }


}