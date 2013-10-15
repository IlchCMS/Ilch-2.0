<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Page
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
	 * @var Ilch_Plugin
	 */
	private $_plugin;

	/**
	 * @var Ilch_Layout_Base
	 */
	private $_layout;

	/**
	 * @var Ilch_View
	 */
	private $_view;

	/**
	 * @var Ilch_Config_File
	 */
	private $_fileConfig;

	/**
	 * Initialize all needed objects.
	 */
	public function __construct()
	{
		$this->_request = new Request();
		$this->_translator = new Translator();
		$this->_router = new Router($this->_request);
		$this->_plugin = new Plugin();
		$this->_view = new View($this->_request, $this->_translator, $this->_router);

		$this->_fileConfig = new Config\File();
		$this->_router->execute();

		if($this->_request->isAdmin())
		{
			$this->_layout = new Layout\Admin($this->_request, $this->_translator, $this->_router);
		}
		else
		{
			$this->_layout = new Layout\Frontend($this->_request, $this->_translator, $this->_router);
		}

		$this->_plugin->detectPlugins();
		$this->_plugin->addPluginData('request', $this->_request);
		$this->_plugin->addPluginData('layout', $this->_layout);
		$this->_plugin->addPluginData('router', $this->_router);
	}

	/**
	 * Load all specific cms data.
	 */
	public function loadCms()
	{
		if(file_get_contents(CONFIG_PATH.'/config.php') != '')
		{
			/*
			 * Cms is installed
			 */
			$this->_fileConfig->loadConfigFromFile(CONFIG_PATH.'/config.php');

			if($this->_fileConfig->get('debugModus') === false)
			{
				@ini_set('display_errors', 'off');
				error_reporting(0);
			}

			$dbFactory = new Database\Factory();
			$db = $dbFactory->getInstanceByConfig($this->_fileConfig);
			$databaseConfig = new Config\Database($db);
			$databaseConfig->loadConfigFromDatabase();
			Registry::set('db', $db);
			Registry::set('config', $databaseConfig);

			$this->_plugin->addPluginData('db', $db);
			$this->_plugin->addPluginData('config', $databaseConfig);
			$this->_plugin->addPluginData('translator', $this->_translator);
			$this->_plugin->execute('AfterDatabaseLoad');
		}
		else
		{
			/*
			 * Cms not installed yet.
			 */
			$this->_request->setModuleName('install');
		}
	}

	/**
	 * Loads the page.
	 */
	public function loadPage()
	{
		$controller = $this->_loadController();

		$this->_translator->load(APPLICATION_PATH.'/modules/'.$this->_request->getModuleName().'/translations');
		$this->_plugin->addPluginData('controller', $controller);
		$this->_plugin->execute('AfterControllerLoad');

		if($this->_request->isAdmin())
		{
			$this->_translator->load(APPLICATION_PATH.'/modules/admin/translations');
			$viewOutput = $this->_view->loadScript(APPLICATION_PATH.'/modules/'.$this->_request->getModuleName().'/views/admin/'.$this->_request->getControllerName().'/'.$this->_request->getActionName().'.php');
		}
		else
		{
			$viewOutput = $this->_view->loadScript(APPLICATION_PATH.'/modules/'.$this->_request->getModuleName().'/views/'.$this->_request->getControllerName().'/'.$this->_request->getActionName().'.php');
		}

		if(!empty($viewOutput))
		{
			$controller->getLayout()->setContent($viewOutput);
		}

		if($controller->getLayout()->getDisabled() === false)
		{
			if($controller->getLayout()->getFile() != '')
			{
				$this->_layout->loadScript(APPLICATION_PATH.'/layouts/'.$controller->getLayout()->getFile().'.php');
			}
			elseif(file_exists(APPLICATION_PATH.'/layouts/'.$this->_request->getModuleName().'/index.php'))
			{
				$this->_layout->loadScript(APPLICATION_PATH.'/layouts/'.$this->_request->getModuleName().'/index.php');
			}
		}
	}

	/**
	 * Loads controller defined by the request object.
	 *
	 * @return Ilch_Controller_Base
	 */
	protected function _loadController()
	{
		if($this->_request->isAdmin())
		{
			$controller = ucfirst($this->_request->getModuleName()).'\\Controllers\\Admin\\'.ucfirst($this->_request->getControllerName());
		}
		else
		{
			$controller = ucfirst($this->_request->getModuleName()).'\\Controllers\\'.ucfirst($this->_request->getControllerName());
		}

		$controller = new $controller($this->_layout, $this->_view, $this->_request, $this->_router, $this->_translator);
		$action = $this->_request->getActionName().'Action';

		$this->_plugin->addPluginData('controller', $controller);
		$this->_plugin->execute('BeforeControllerLoad');

		if(method_exists($controller, 'init'))
		{
			$controller->init();
		}

		if(method_exists($controller, $action))
		{
			$controller->$action();
		}

		return $controller;
	}

	/**
	 * Returns the view object.
	 *
	 * @return Ilch_View
	 */
	public function getView()
	{
		return $this->_view;
	}

	/**
	 * Returns the request object.
	 *
	 * @return Ilch_Request
	 */
	public function getRequest()
	{
		return $this->_request;
	}
}