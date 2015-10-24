<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Page
{
    /**
     * @var Ilch_Request
     */
    private $request;

    /**
     * @var Ilch_Translator
     */
    private $translator;

    /**
     * @var Ilch_Router
     */
    private $router;

    /**
     * @var Ilch_Plugin
     */
    private $plugin;

    /**
     * @var Ilch_Layout_Base
     */
    private $layout;

    /**
     * @var Ilch_View
     */
    private $view;

    /**
     * @var Ilch_Config_File
     */
    private $fileConfig;

    /**
     * Initialize all needed objects.
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->translator = new Translator();
        $this->router = new Router($this->request);
        $this->plugin = new Plugin();
        $this->view = new View($this->request, $this->translator, $this->router);

        $this->fileConfig = new Config\File();
        $this->router->execute();

        if ($this->request->isAdmin()) {
            $this->layout = new Layout\Admin($this->request, $this->translator, $this->router);
        } else {
            $this->layout = new Layout\Frontend($this->request, $this->translator, $this->router);
        }

        if ($this->request->isPost() && !$this->request->isSecure()) {
            throw new \InvalidArgumentException(
                'no valid secure token given, add function getTokenField() to formular'
            );
        }

        $this->plugin->detectPlugins();
        $this->plugin->addPluginData('request', $this->request);
        $this->plugin->addPluginData('layout', $this->layout);
        $this->plugin->addPluginData('router', $this->router);
    }

    /**
     * Load all specific cms data.
     */
    public function loadCms()
    {
        $this->fileConfig->loadConfigFromFile(CONFIG_PATH.'/config.php');

        if (($this->fileConfig->get('dbUser')) !== null) {
            /*
             * Cms is installed
             */
            if ($this->fileConfig->get('debugModus') === false) {
                @ini_set('display_errors', 'off');
                error_reporting(0);
            }

            $dbFactory = new Database\Factory();
            $db = $dbFactory->getInstanceByConfig($this->fileConfig);
            $databaseConfig = new Config\Database($db);
            $databaseConfig->loadConfigFromDatabase();
            Registry::set('db', $db);
            Registry::set('config', $databaseConfig);
            $this->plugin->addPluginData('db', $db);
            $this->plugin->addPluginData('config', $databaseConfig);
            $this->plugin->addPluginData('translator', $this->translator);
            $this->plugin->execute('AfterDatabaseLoad');
            $this->router->defineStartPage($databaseConfig->get('start_page'), $this->translator);
        } else {
            /*
             * Cms not installed yet.
             */
            $this->request->setModuleName('install');

            if (!empty($_SESSION['language'])) {
                $this->translator->setLocale($_SESSION['language']);
            }
        }
    }

    /**
     * Loads the page.
     */
    public function loadPage()
    {
        $this->translator->load(APPLICATION_PATH.'/modules/'.$this->request->getModuleName().'/translations');
        
        if($this->request->isAdmin()) {
            $this->translator->load(APPLICATION_PATH.'/modules/admin/translations');
        }

        $controller = $this->loadController();
        $controllerName = $this->request->getControllerName();
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $this->request->getControllerName());
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]).'\\';
        }
        
        $this->plugin->addPluginData('controller', $controller);
        $this->plugin->execute('AfterControllerLoad');
        
        if ($this->request->isAdmin()) {
            $viewOutput = $this->view->loadScript(APPLICATION_PATH.'/modules/'.$this->request->getModuleName().'/views/admin/'.$dir.$controllerName.'/'.$this->request->getActionName().'.php');
        } else {
            $viewPath = APPLICATION_PATH.'/'.dirname($controller->getLayout()->getFile()).'/views/modules/'.$this->request->getModuleName().'/'.$dir.$controllerName.'/'.$this->request->getActionName().'.php';

            if (!file_exists($viewPath)) {
                $viewPath = APPLICATION_PATH.'/modules/'.$this->request->getModuleName().'/views/'.$dir.$controllerName.'/'.$this->request->getActionName().'.php';
            }

            $viewOutput = $this->view->loadScript($viewPath);
        }

        if (!empty($viewOutput)) {
            $controller->getLayout()->setContent($viewOutput);
        }

        if ($this->request->isAjax()) {
            echo $viewOutput;
        } elseif ($controller->getLayout()->getDisabled() === false) {
            if ($controller->getLayout()->getFile() != '') {
                $this->layout->loadScript(APPLICATION_PATH.'/'.$controller->getLayout()->getFile().'.php');
            }
        }
    }

    /**
     * Loads controller defined by the request object.
     *
     * @return Ilch_Controller_Base
     */
    protected function loadController()
    {
        $controllerName = $this->request->getControllerName();
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $this->request->getControllerName());
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]).'\\';
        }

        if ($this->request->isAdmin()) {
            $controller = '\\Modules\\'.ucfirst($this->request->getModuleName()).'\\Controllers\\Admin\\'.$dir.ucfirst($controllerName);
        } else {
            $controller = '\\Modules\\'.ucfirst($this->request->getModuleName()).'\\Controllers\\'.$dir.ucfirst($controllerName);
        }

        $controller = new $controller($this->layout, $this->view, $this->request, $this->router, $this->translator);
        $action = $this->request->getActionName().'Action';

        $this->plugin->addPluginData('controller', $controller);
        $this->plugin->execute('BeforeControllerLoad');

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
        return $this->view;
    }

    /**
     * Returns the request object.
     *
     * @return Ilch_Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
