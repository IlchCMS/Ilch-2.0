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

    public function loadCms()
    {
		$config = new Ilch_Config();
		$this->_loadConfig($config);

		$request = new Ilch_Request();
		$translator = new Ilch_Translator();

        $layout = new Ilch_Layout($request, $translator);
        $view = new Ilch_View($request, $translator);

		$plugin = new Ilch_Plugin();
		$plugin->detectPlugins();

		if($this->_ilchInstalled)
		{
			$this->_setupDatabaseAdapter($config);
		}

		$this->_loadRouting($request);

		/*
		 * Load controller as first.
		 */
		$controller = $this->_loadController($layout, $view, $plugin, $request, $translator);

		/*
		 * Load controller view, if exists.
		 */
        if(file_exists(APPLICATION_PATH.'/modules/'.$controller->modulName.'/views/'.$controller->name.'.php'))
        {
            $viewOutput = $view->load($controller->modulName, $controller->name);
        }
        else
        {
			/*
			 * Load action views if no controller view exists.
			 */
            if(empty($controller->getView()->name))
            {
                $viewOutput = $view->load($controller->modulName ,$controller->name , $controller->actionName);
            }
            else
            {
                $viewOutput = $view->load($controller->modulName ,$controller->name , $controller->getView()->name);
            }
        }

        $controller->getLayout()->setContent($viewOutput);
        $controller->getLayout()->controller = $controller;

        if($controller->getLayout()->getDisabled() === false)
        {
            if
            (
                empty($controller->getLayout()->name)
                &&
                file_exists(APPLICATION_PATH.'/layouts/'.$controller->modulName.'/index.php')
            )
            {
                $layout->load($controller->modulName.'/index');
            }
            elseif(!empty($controller->getLayout()->name))
            {
                $layout->load($controller->getLayout()->name);
            }
            else
            {
                $layout->load('index');
            }
        }
        else
        {
            if(!empty($viewOutput))
            {
                $layout->load($viewOutput, 1);
            }
        }
    }
 
    /**
     * Setup the database adapter, create instance and connect to database.
     */
    protected function _setupDatabaseAdapter(Ilch_Config $config)
    {
		$dbClass = 'Ilch_Database_'.$config->getConfig('dbEngine');
		$db = new $dbClass();
		$db->connect($config->getConfig('dbHost'), $config->getConfig('dbUser'), $config->getConfig('dbPassword'));
		$db->setDatabase($config->getConfig('dbName'));
		$db->setPrefix($config->getConfig('dbPrefix'));

		Ilch_Registry::set('db', $db);
    }

    /**
     * Create and load a specific route.
     *
     * @param Ilch_Request $request
     */
    protected function _loadRouting(Ilch_Request $request)
    {
        if(!$this->_ilchInstalled)
        {
            $moduleName = 'Install';
        }
        elseif(empty($_GET['module']))
        {
            $moduleName = 'News';
        }
        else
        {
            $moduleName = ucfirst($_GET['module']);
        }

        if(empty($_GET['controller']))
        {
            $controllerName = 'Index';
        }
        else
        {
            $controllerName = ucfirst($_GET['controller']);
        }

        if(empty($_GET['action']))
        {
            $actionName = 'index';
        }
        else
        {
            $actionName = $_GET['action'];
        }

		foreach(array('module', 'controller', 'action') as $name)
		{
			unset($_REQUEST[$name]);
		}

		$request->setModuleName(strtolower($moduleName));
		$request->setControllerName(strtolower($controllerName));
		$request->setActionName(strtolower($actionName));
		$request->setParams($_REQUEST);
    }

    /**
     * Loads the config, if already created.
     */
    protected function _loadConfig(Ilch_Config $config)
    {
        if(file_exists(CONFIG_PATH.'/config.php'))
        {
			$config->loadConfigFromFile(CONFIG_PATH.'/config.php');
            $this->_ilchInstalled = true;
        }
    }

    /**
     * @param Ilch_Layout $layout
     * @param Ilch_View $view
     * @param Ilch_Plugin $plugin
     * @param Ilch_Request $request
     * @param Ilch_Translator $translator
     * @return Ilch_Controller
     * @throws InvalidArgumentException
     */
    protected function _loadController(Ilch_Layout $layout, Ilch_View $view, Ilch_Plugin $plugin, Ilch_Request $request, Ilch_Translator $translator)
    {
        $controller = $request->getModuleName().'_'.$request->getControllerName().'Controller';
        $controller = new $controller($layout, $view, $plugin, $request, $translator);
        $controller->actionName = $request->getActionName();
        $controller->modulName = strtolower($request->getModuleName());
        $controller->name = strtolower($request->getControllerName());
        $action = $request->getActionName().'Action';

		/*
		 * Load "BeforeControllerLoad" - plugins.
		 */
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
            throw new InvalidArgumentException('action "'.$action.'" not known');
        }

		$translator->load(APPLICATION_PATH.'/modules/'.$request->getModuleName().'/translations');

		/*
		 * Load "AfterControllerLoad" - plugins.
		 */
        $plugin->execute('AfterControllerLoad');

        return $controller;
    }
}