<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Controller_Base
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
	 * @var Ilch_Layout
	 */
	private $_layout;

	/**
	 * @var Ilch_View
	 */
	private $_view;

	/**
	 * The currently logged in user or null if the user is a guest.
	 *
	 * @var User_UserModel
	 */
	private $_user;

	/**
	 * Injects the layout/view to the controller.
	 *
	 * @param Ilch_Layout $layout
	 * @param Ilch_View $view
	 * @param Ilch_Request $request
	 * @param Ilch_Router $router
	 * @param Ilch_Translator $translator
	 */
	public function __construct(Ilch_Layout $layout, Ilch_View $view, Ilch_Request $request, Ilch_Router $router, Ilch_Translator $translator)
	{
		$this->_layout = $layout;
		$this->_view = $view;
		$this->_request = $request;
		$this->_router = $router;
		$this->_translator = $translator;
		$this->_user = Ilch_Registry::get('user');
	}

	/**
	 * Redirect to given params.
	 *
	 * @param array $urlArray
	 * @param string $route
	 * @param boolean $rewrite
	 */
	public function redirect($urlArray, $route = null, $rewrite = false)
	{
		header("location: ".$this->getLayout()->url($urlArray, $route, $rewrite));
		exit;
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
	 * Gets the router object.
	 *
	 * @return Ilch_Router
	 */
	public function getRouter()
	{
		return $this->_router;
	}

	/**
	 * Gets the config object.
	 *
	 * @return Ilch_Config
	 */
	public function getConfig()
	{
		return Ilch_Registry::get('config');
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
	 * Gets the layout object.
	 *
	 * @return Ilch_Layout
	 */
	public function getLayout()
	{
		return $this->_layout;
	}

	/**
	 * Gets the view object.
	 *
	 * @return Ilch_Request
	 */
	public function getView()
	{
		return $this->_view;
	}
}