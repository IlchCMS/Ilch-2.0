<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

abstract class Ilch_Design_Base
{
	/**
	 * @var Ilch_Request
	 */
	private $_request;
	
	/**
	 * @var Ilch_Translator 
	 */
	private $_translator;

	/**
	 * @var Ilch_Router 
	 */
	private $_router;

	/**
	 * Injects request and translator to layout/view.
	 *
	 * @param Ilch_Request $request
	 * @param Ilch_Translator $translator
	 * @param Ilch_Router $router
	 */
	public function __construct(Ilch_Request $request, Ilch_Translator $translator, Ilch_Router $router)
	{
		$this->_request = $request;
		$this->_translator = $translator;
		$this->_router = $router;
	}

	/**
	 * Gets the request object.
	 *
	 * @return Ilch_Request
	 */
	public function getRequest()
	{
		return $this->_request;
	}

	/**
	 * Gets the translator object.
	 *
	 * @return Ilch_Translator
	 */
	public function getTranslator()
	{
		return $this->_translator;
	}

	/**
	 * Returns the translated text for a specific key.
	 *
	 * @param string $key
	 * @param mixed[]
	 * @return string
	 */
	public function trans($key, $placeholders = array())
	{
		return $this->getTranslator()->trans($key, $placeholders);
	}

	/**
	 * Gets the base url.
	 *
	 * @param sting $url
	 * @return string
	 */
	public function baseUrl($url = '')
	{
		return BASE_URL.'/'.$url;
	}

	/**
	 * Gets the static url.
	 *
	 * @param string $url
	 * @return string
	 */
	public function staticUrl($url = '')
	{
		if(empty($url))
		{
			return STATIC_URL;
		}

		return STATIC_URL.'/'.$url;
	}

	/**
	 * Escape the given string.
	 *
	 * @param string $string
	 * @return string
	 */
	function escape($string)
	{
		return htmlspecialchars($string, ENT_QUOTES);
	}

	/**
	 * Creates a full url for the given parts.
	 *
	 * @param arry $module
	 * @param string $route
	 * @param boolean $rewrite
	 * @return string
	 */
	public function url($urlArray = array(), $route = 'default', $rewrite  = false)
	{
		if(empty($urlArray))
		{
			return BASE_URL;
		}

		$urlParts = array();
		$config = Ilch_Registry::get('config');

		if($rewrite || ($config && $config->get('rewrite') == true))
		{
			if(isset($urlArray['module']))
			{
				$urlParts[] = $urlArray['module'];
				unset($urlArray['module']);
			}
			else
			{
				$urlParts[] = 'index';
			}
			
			if(isset($urlArray['controller']))
			{
				$urlParts[] = $urlArray['controller'];
				unset($urlArray['controller']);
			}
			else
			{
				$urlParts[] = 'index';
			}
			
			if(isset($urlArray['action']))
			{
				$urlParts[] = $urlArray['action'];
				unset($urlArray['action']);
			}
			else
			{
				$urlParts[] = 'index';
			}
			
			foreach($urlArray as $key => $value)
			{
				$urlParts[] = $key;
				$urlParts[] = $value;
			}

			return BASE_URL.'/'.implode('/', $urlParts);
		}
		else
		{
			foreach($urlArray as $key => $value)
			{
				$urlParts[] = $key.'='.$value;
			}

			return BASE_URL.'/index.php?'.implode('&', $urlParts);
		}
	}

	/**
	 * Gets the page loading time in microsecond.
	 *
	 * @return float
	 */
	public function loadTime()
	{
		$startTime = Ilch_Registry::get('startTime');
		return microtime(true) - $startTime;
	}

	/**
	 * Gets the page queries.
	 *
	 * @return integer
	 */
	public function queryCount()
	{
		$db = Ilch_Registry::get('db');
		return $db->queryCount();
	}

	/**
	 * Limit the given string to the given length. 
	 *
	 * @param string $str
	 * @param integer $length
	 * @return string
	 */
	public function limitString($str, $length)
	{
		if(strlen($str) <= $length)
		{
			return $str;
		}
		else
		{
			return preg_replace("/[^ ]*$/", '', substr($str, 0, $length)).'...';
		}
	}
}