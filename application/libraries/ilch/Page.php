<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Page
{
	/**
	 * Defines if cms is installed.
	 *
	 * @var boolean ilchInstalled
	 */
	protected $_ilchInstalled = false;

	/**
	 * Sets the installed flag.
	 *
	 * @param boolean $installed
	 */
	public function setInstalled($installed)
	{
		$this->_ilchInstalled = (bool)$installed;
	}

	/**
	 * Load and initialize cms.
	 *
	 * @param Ilch_Config_File $fileConfig
	 */
	public function loadCms(Ilch_Config_File $fileConfig)
	{
		$request = new Ilch_Request();
		$translator = new Ilch_Translator();
		$router = new Ilch_Router($request, $fileConfig);

		$plugin = new Ilch_Plugin();
		$plugin->addPluginData('request', $request);
		$plugin->detectPlugins();

		if($this->_ilchInstalled)
		{
			$dbFactory = new Ilch_Database_Factory();
			$db = $dbFactory->getInstanceByConfig($fileConfig);
			$databaseConfig = new Ilch_Config_Database($db);
			$databaseConfig->loadConfigFromDatabase();
			Ilch_Registry::set('db', $db);
			Ilch_Registry::set('config', $databaseConfig);
		}

		$plugin->execute('AfterDatabaseLoad');
		$router->execute();

		if(!$this->_ilchInstalled)
		{
			$request->setModuleName('install');
		}

		$layout = new Ilch_Layout($request, $translator, $router);
		$view = new Ilch_View($request, $translator, $router);

		$controller = $this->_loadController($layout, $view, $plugin, $request, $router, $translator);
		$plugin->addPluginData('controller', $controller);
		$plugin->execute('AfterControllerLoad');

		if($request->isAdmin())
		{
			$viewOutput = $view->loadScript(APPLICATION_PATH.'/modules/'.$request->getModuleName().'/views/admin/'.$request->getControllerName().'/'.$request->getActionName().'.php');
		}
		else
		{
			$viewOutput = $view->loadScript(APPLICATION_PATH.'/modules/'.$request->getModuleName().'/views/'.$request->getControllerName().'/'.$request->getActionName().'.php');
		}
		

		if(!empty($viewOutput))
		{
			$controller->getLayout()->setContent($viewOutput);
		}

		if($controller->getLayout()->getDisabled() === false)
		{
			if($controller->getLayout()->getFile() != '')
			{
				$layout->loadScript(APPLICATION_PATH.'/layouts/'.$controller->getLayout()->getFile().'.php');
			}
			elseif(file_exists(APPLICATION_PATH.'/layouts/'.$request->getModuleName().'/index.php'))
			{
				$layout->loadScript(APPLICATION_PATH.'/layouts/'.$request->getModuleName().'/index.php');
			}
		}
	}

	/**
	 * @param Ilch_Layout $layout
	 * @param Ilch_View $view
	 * @param Ilch_Plugin $plugin
	 * @param Ilch_Request $request
	 * @param Ilch_Router $router
	 * @param Ilch_Translator $translator
	 * @return Ilch_Controller_Base
	 * @throws InvalidArgumentException
	 */
	protected function _loadController(Ilch_Layout $layout, Ilch_View $view, Ilch_Plugin $plugin, Ilch_Request $request, Ilch_Router $router, Ilch_Translator $translator)
	{
		if($request->isAdmin())
		{
			$controller = ucfirst($request->getModuleName()).'_Admin_'.ucfirst($request->getControllerName()).'Controller';
		}
		else
		{
			$controller = ucfirst($request->getModuleName()).'_'.ucfirst($request->getControllerName()).'Controller';
		}

		$controller = new $controller($layout, $view, $request, $router, $translator);
		$action = $request->getActionName().'Action';

		$plugin->addPluginData('controller', $controller);
		$plugin->execute('BeforeControllerLoad');

		if(method_exists($controller, 'init'))
		{
			$controller->init();
		}

		if(method_exists($controller, $action))
		{
			$controller->$action();
		}
		else
		{
			throw new InvalidArgumentException('action "'.$action.'" not known for controller "'.$request->getControllerName().'"');
		}

		$translator->load(APPLICATION_PATH.'/modules/'.$request->getModuleName().'/translations');

		$plugin->execute('AfterControllerLoad');

		return $controller;
	}
}