<?php
/**
 * Holds class PHPUnit_Ilch_Controller_TestCase.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Base class for controller test cases for Ilch.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
abstract class PHPUnit_Ilch_Controller_TestCase extends PHPUnit_Ilch_TestCase
{
	/**
	 * A data array which will be used to create a config object for the registry.
	 *
	 * @var Array
	 */
	protected $_configData = array();

	/**
	 * Holds request parameters.
	 *
	 * @var array
	 */
	private $_requestParams = array();

	/**
	 * The action to call.
	 *
	 * @var string
	 */
	private $_action = 'index';

	/**
	 * Holds the string output of the action after it was called.
	 *
	 * @var string
	 */
	private $_actionOutput = null;

	/**
	 * Holds the view object after the action was called.
	 *
	 * @var Ilch_View
	 */
	private $_view = null;

	/**
	 * Holds the request object after the action was called.
	 *
	 * @var Ilch_Request
	 */
	private $_request = null;

	/**
	 * Filling the config object with individual testcase data.
	 */
	public function setUp()
	{
		parent::setUp();
		Ilch_Registry::remove('config');

		$serverTimeZone = date_default_timezone_get();
		date_default_timezone_set('UTC');

		require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';

		Ilch_Registry::set('startTime', microtime(true));
	}

	/**
	 * Sets a request parameter.
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setRequestParam($key, $value)
	{
		$this->_requestParams[$key] = $value;
	}

	/**
	 * Loads the action.
	 *
	 * Emulates the functionality for setting necessary constants done in the index.php
	 * and calls the page object to load the cms and the controller.
	 */
	public function load()
	{
		/*
		 * We are using the classname of the testcase to generate the module and controller out of it.
		 * Example: From the classname "Modules_User_Controllers_UserlistTest" the regex greps "User"
		 * as the module name and "Userlist" as the controller name.
		 */
		$matches = array();
		$requestFromClass = preg_match('/Modules_([a-zA-Z0-9]+)_Controllers_([a-zA-Z0-9]+)Test$/', get_class($this), $matches);
		$requestURI = '/'.PHP_SELF.'/index.php/'.strtolower($matches[1]).'/'.strtolower($matches[2]).'/'.$this->_action;

		if(!empty($this->_requestParams))
		{
			/**
			 * Appending params to the request if some are given.
			 */
			foreach($this->_requestParams as $key => $value)
			{
				$requestURI .= '/'.$key.'/'.$value;
			}
		}

		$_SERVER['REQUEST_URI'] = $requestURI;

		$_SERVER['PHP_SELF'] = '/'.PHP_SELF;
		$rewriteBaseParts = $_SERVER['PHP_SELF'];
		$rewriteBaseParts = explode('index.php', $rewriteBaseParts);
		$rewriteBaseParts = rtrim(reset($rewriteBaseParts), '/');

		define('REWRITE_BASE', $rewriteBaseParts);
		define('BASE_URL', 'http://'.HTTP_HOST.REWRITE_BASE);
		define('STATIC_URL', BASE_URL);

		/*
		 * Loading page and controller.
		 */
		$page = new Ilch_Page();
		$page->loadCms();

		/*
		 * The action gets called.
		 * Storing the generated output in a variable.
		 */
		ob_start();
			$page->loadPage();
			$this->_actionOutput = ob_get_contents();
		ob_end_clean();

		$this->_view = $page->getView();
		$this->_request = $page->getRequest();
	}

	/**
	 * Sets the action to call.
	 *
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->_action = (string)$action;
	}

	/**
	 * Performs an assertion to check if the action output matches an expression.
	 *
	 * @param  string  $expr  If the string is surrounded by two slashes or the delimiter character it counts as a regular expression.
	 * @param  integer $count The number of how often the expression should match. Default is 1.
	 */
	protected function _assertActionOutputExpr($expr, $count = 1, $delimiter = '/')
	{
		if(substr($expr, 0, 1) === $delimiter && substr($expr, -1) === $delimiter)
		{
			/*
			 * Expression is a regex, lets use preg_match.
			 */
			$result = preg_match_all($expr, $this->_actionOutput);
		}
		else
		{
			$result = substr_count($this->_actionOutput, $expr);
		}

		if($result === false)
		{
			throw new RuntimeException('The check of the action output returned an error state.');
		}

		$this->assertEquals($count, $result, 'The expected count of '.$count.' did not match the actual count '.$result.' for the expression "'.$expr.'"');
	}

	/**
	 * Asserts that a view param with the given key exists and is not null.
	 *
	 * @param  string $key
	 */
	protected function _assertViewParamExists($key)
	{
		$param = $this->_view->get($key);
		$this->assertTrue(isset($param), 'The view param "'.$key.'" does not exist.');
	}

	/**
	 * Asserts that a specific view param equals a given value.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 */
	protected function _assertViewParamEquals($key, $value)
	{
		$param = $this->_view->get($key);
		$this->assertEquals($value, $param, 'The view param "'.$key.'" does not have the correct value, expected "'.$value.'", actual "'.$this->_view->get($key).'".');
	}

	/**
	 * Asserts that a request variable with a specific key exists.
	 *
	 * @param  string $key
	 */
	protected function _assertRequestParamExists($key)
	{
		$params = $this->_request->getParams();
		$this->assertTrue(isset($params[$key]), 'The request parameter "'.$key.'" does not exist.');
	}

	/**
	 * Asserts that a specific request variable equals a given value.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 */
	protected function _assertRequestParamEquals($key, $value)
	{
		$this->assertEquals($value, $this->_request->getParam($key), 'The request variable "'.$key.'" does not have the correct value, expected "'.$value.'", actual "'.$this->_request->getParam($key).'".');
	}
}