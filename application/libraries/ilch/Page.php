<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
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
     * Loads the config, if already created.
     */
    public function loadConfig()
    {
        if(file_exists(CONFIG_PATH.'/config.php'))
        {
            require_once CONFIG_PATH.'/config.php';
            $this->_ilchInstalled = true;
        }
    }

    public function loadCms()
    {
        $layout = new Ilch_Layout();
        $view = new Ilch_View();
	$plugin = new Ilch_Plugin();
	$plugin->detectPlugins();
	$request = new Ilch_Request();

	$dbClass = 'Ilch_Database_'.DB_ENGINE;
	$db = new $dbClass();
        Ilch_Registry::set('db', $db);

	/*
	 * Load controller as first.
	 */
        $controller = $this->_loadController($layout, $view, $plugin, $request);

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
            if(empty($controller->view->name))
            {
                $viewOutput = $view->load($controller->modulName ,$controller->name , $controller->actionName);
            }
            else
            {
                $viewOutput = $view->load($controller->modulName ,$controller->name , $controller->view->name);
            }
        }

        $controller->layout->setContent($viewOutput);
        $controller->layout->controller = $controller;

        if($controller->layout->getDisabled() === false)
        {
            if
            (
                empty($controller->layout->name)
                &&
                file_exists(APPLICATION_PATH.'/layouts/'.$controller->modulName.'/index.php')
            )
            {
                $layout->load($controller->modulName.'/index');
            }
            elseif(!empty($controller->layout->name))
            {
                $layout->load($controller->layout->name);
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
     * @param Ilch_Layout $layout
     * @param Ilch_View $view
     * @param Ilch_Plugin $plugin
     * @param Ilch_Request $request
     * @return Ilch_Controller
     * @throws InvalidArgumentException
     */
    protected function _loadController(Ilch_Layout $layout, Ilch_View $view, Ilch_Plugin $plugin, Ilch_Request $request)
    {
        if(!$this->_ilchInstalled)
        {
            $moduleName = 'Install';
        }
        elseif(empty($_GET['module']))
        {
            $moduleName = 'Start';
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

	$request->setModuleName(strtolower($moduleName));
	$request->setControllerName(strtolower($controllerName));
	$request->setActionName(strtolower($actionName));

	foreach(array('module', 'controller', 'action') as $name)
	{
	    unset($_REQUEST[$name]);
	}

	$request->setParams($_REQUEST);

        $controller = $moduleName.'_'.$controllerName.'Controller';
        $controller = new $controller($layout, $view, $plugin, $request);
        $controller->actionName = $actionName;
        $controller->modulName = strtolower($moduleName);
        $controller->name = strtolower($controllerName);
        $action = $actionName.'Action';

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

	/*
	 * Load "AfterControllerLoad" - plugins.
	 */
        $plugin->execute('AfterControllerLoad');

        return $controller;
    }
}