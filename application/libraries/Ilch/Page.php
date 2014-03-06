<?php
/**
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

        if ($this->_request->isAdmin()) {
            $this->_layout = new Layout\Admin($this->_request, $this->_translator, $this->_router);
        } else {
            $this->_layout = new Layout\Frontend($this->_request, $this->_translator, $this->_router);
        }

        if ($this->_request->isPost() && !$this->_request->isSecure()) {
            throw new \InvalidArgumentException(
                'no valid secure token given, add function getTokenField() to formular'
            );
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
        $this->_fileConfig->loadConfigFromFile(CONFIG_PATH.'/config.php');

        if (($this->_fileConfig->get('dbUser')) !== null) {
            /*
             * Cms is installed
             */
            if ($this->_fileConfig->get('debugModus') === false) {
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
            $this->_router->defineStartPage($databaseConfig->get('start_page'), $this->_translator);
        } else {
            /*
             * Cms not installed yet.
             */
            $this->_request->setModuleName('install');

            if (!empty($_SESSION['language'])) {
                $this->_translator->setLocale($_SESSION['language']);
            }
        }
    }

    /**
     * Loads the page.
     */
    public function loadPage()
    {
        $this->_translator->load(APPLICATION_PATH.'/modules/'.$this->_request->getModuleName().'/translations');
        
        if($this->_request->isAdmin()) {
            $this->_translator->load(APPLICATION_PATH.'/modules/admin/translations');
        }

        $controller = $this->_loadController();
        $controllerName = $this->_request->getControllerName();
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $this->_request->getControllerName());
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]).'\\';
        }
        
        $this->_plugin->addPluginData('controller', $controller);
        $this->_plugin->execute('AfterControllerLoad');
        

        if ($this->_request->isAdmin()) {
            $viewOutput = $this->_view->loadScript(APPLICATION_PATH.'/modules/'.$this->_request->getModuleName().'/views/admin/'.$dir.$controllerName.'/'.$this->_request->getActionName().'.php');
        } else {
            $viewPath = APPLICATION_PATH.'/'.dirname($controller->getLayout()->getFile()).'/views/modules/'.$this->_request->getModuleName().'/'.$dir.$controllerName.'/'.$this->_request->getActionName().'.php';

            if (!file_exists($viewPath)) {
                $viewPath = APPLICATION_PATH.'/modules/'.$this->_request->getModuleName().'/views/'.$dir.$controllerName.'/'.$this->_request->getActionName().'.php';
            }

            $viewOutput = $this->_view->loadScript($viewPath);
        }

        if (!empty($viewOutput)) {
            $controller->getLayout()->setContent($viewOutput);
        }

        if ($controller->getLayout()->getDisabled() === false) {
            if ($controller->getLayout()->getFile() != '') {
                $this->_layout->loadScript(APPLICATION_PATH.'/'.$controller->getLayout()->getFile().'.php');
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
        $controllerName = $this->_request->getControllerName();
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $this->_request->getControllerName());
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]).'\\';
        }

        if ($this->_request->isAdmin()) {
            $controller = ucfirst($this->_request->getModuleName()).'\\Controllers\\Admin\\'.$dir.ucfirst($controllerName);
        } else {
            $controller = ucfirst($this->_request->getModuleName()).'\\Controllers\\'.$dir.ucfirst($controllerName);
        }

        $controller = new $controller($this->_layout, $this->_view, $this->_request, $this->_router, $this->_translator);
        $action = $this->_request->getActionName().'Action';

        $this->_plugin->addPluginData('controller', $controller);
        $this->_plugin->execute('BeforeControllerLoad');

        if (method_exists($controller, 'init')) {
            $controller->init();
        }

        if (method_exists($controller, $action)) {
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
