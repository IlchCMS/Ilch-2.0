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
 * #todo add matcher registry for custom matcher startegies
 * Class Router
 * @package Ilch
 */
class Router
{

	const DEFAULT_REGEX_PATTERN = '/?(?P<module>\w+)/(?P<controller>\w+)(/(?P<action>\w+)?)?(/(?P<params>[a-zA-Z0-9_/]+)?)?';
	const DEFAULT_MATCH_STRATEGY = 'matchByQuery';

	private $_query = null;

	/**
	 * Injects request and config object.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->_request = $request;
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
	 * @param null $pattern
	 * @return array
	 * @throws \Exception
	 */
	public function matchByRegexp($route, $pattern = null)
	{
		$matches = [];

		$pattern = $pattern === null ? self::DEFAULT_REGEX_PATTERN : $pattern;

		$matched = preg_match(
			'#^' . $pattern . '$#i',
			$route,
			$matches
		);

		if (count($matches) === 0)
		{
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

		foreach ($array as $key => $value)
		{
			if ($key % 2 === 0)
			{
				$prevKey = $value;
			}
			if ($key % 2 === 1)
			{
				$result[$prevKey] = $value;
			}
		}

		return $result;
	}

	/**
	 * Fills the request object if rewrite is possible.
	 *
	 * @param $params
	 * @internal param string $route
	 */
	public function matchByQuery($params)
	{
		$route = $params['route'];

		$queryParts = explode('/', $route);

		$i = 0;

		if ($queryParts[0] == 'admin')
		{
			$this->_request->setIsAdmin(true);
			unset($queryParts[0]);
			$i = 1;
		}

		if (isset($queryParts[$i]))
		{
			$this->_request->setModuleName($queryParts[$i]);
			unset($queryParts[$i]);
		}

		$i++;

		if (isset($queryParts[$i]))
		{
			$this->_request->setControllerName($queryParts[$i]);
			unset($queryParts[$i]);
		}

		$i++;

		if (isset($queryParts[$i]))
		{
			$this->_request->setActionName($queryParts[$i]);
			unset($queryParts[$i]);
		}

		if (!empty($queryParts))
		{
			$paramKey = $paramValue = '';

			foreach ($queryParts as $value)
			{
				if (!empty($paramKey))
				{
					$this->_request->setParam($paramKey, $value);
					$paramKey = '';
				}

				$paramKey = $value;
			}
		}
	}

	/**
	 * #todo add strategy iterator in addition to param
	 * #todo execute strategy iterator if param Strategy is null
	 * match(route, params)
	 * match(route, strategy, params)
	 * Match route by strategy
	 * @param string $strategy
	 * @param array $params
	 * @return mixed
	 */
	public function match($strategy = self::DEFAULT_MATCH_STRATEGY, array $params = array())
	{
		$callback = array();
		if (is_array($strategy))
		{
			$params = $strategy;
			$strategy = self::DEFAULT_MATCH_STRATEGY;
		}
		if (!array_key_exists('route', $params))
		{
			$params['route'] = $this->getRouteByRequest();
		}
		//select default strategy delivered by router
		if (is_string($strategy) && strtolower(substr($strategy, 0, 5)) === 'match' && method_exists($this, $strategy))
		{
			$callback = array($this, $strategy);
		}

		if (is_callable($strategy))
		{
			$callback = $strategy;
		}

		return call_user_func_array($callback, $params);
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

		if (!empty($query))
		{
			$this->match(
				self::DEFAULT_MATCH_STRATEGY,
				array('route' => $query)
			);
		}
	}
}