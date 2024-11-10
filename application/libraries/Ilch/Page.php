<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

class Page
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @var \Ilch\Layout\Base
     */
    private $layout;

    /**
     * @var View
     */
    private $view;

    /**
     * @var \Ilch\Config\File
     */
    private $fileConfig;

    /**
     * @var Accesses
     */
    private $accesses;

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
        $this->accesses = new Accesses($this->request);

        $this->fileConfig = new Config\File();
        $this->router->execute();

        if ($this->request->isAdmin()) {
            $this->layout = new Layout\Admin($this->request, $this->translator, $this->router);
        } else {
            $this->layout = new Layout\Frontend($this->request, $this->translator, $this->router);
        }

        if ($this->request->isPost() && !$this->request->isSecure()) {
            $message = 'No valid secure token given, add function getTokenField() to formular.';
            if (!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')) {
                $message .= ' If you previously visited this website over HTTPS, try again accessing this site over HTTPS or clear cookies and restart your browser.';
            }
            throw new \InvalidArgumentException($message);
        }

        $this->plugin->detectPlugins();
        $this->plugin->addPluginData('request', $this->request);
        $this->plugin->addPluginData('layout', $this->layout);
        $this->plugin->addPluginData('router', $this->router);
        $this->plugin->addPluginData('accesses', $this->accesses);
    }

    /**
     * Load all specific cms data.
     */
    public function loadCms()
    {
        $this->fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');

        if (($this->fileConfig->get('dbUser')) !== null) {
            // Cms is installed
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
            if ($databaseConfig->get('domain')) {
                $this->view->initializeHtmlPurifier($databaseConfig);
                $this->layout->initializeHtmlPurifier($databaseConfig);
            } else {
                $this->view->initializeHtmlPurifier();
                $this->layout->initializeHtmlPurifier();
            }
        } else {
            // Cms not installed yet.
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
        $this->translator->load(APPLICATION_PATH . '/libraries/Ilch/Translations');
        if ($this->request->isAdmin()) {
            $this->translator->load(APPLICATION_PATH . '/modules/admin/translations');
        }
        $this->translator->load(APPLICATION_PATH . '/modules/' . $this->request->getModuleName() . '/translations');

        Registry::set('translator', $this->translator);
        $controller = $this->loadController();
        $this->translator->load(APPLICATION_PATH . '/' . dirname($controller->getLayout()->getFile()) . '/translations');
        $controllerName = $this->request->getControllerName();
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $this->request->getControllerName());
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]) . '\\';
        }

        $this->plugin->addPluginData('controller', $controller);
        $this->plugin->execute('AfterControllerLoad');

        if ($this->request->isAdmin()) {
            $viewOutput = $this->view->loadScript(APPLICATION_PATH . '/modules/' . $this->request->getModuleName() . '/views/admin/' . $dir . $controllerName . '/' . $this->request->getActionName() . '.php');
        } else {
            $viewPath = APPLICATION_PATH . '/' . dirname($controller->getLayout()->getFile()) . '/views/modules/' . $this->request->getModuleName() . '/' . $dir . $controllerName . '/' . $this->request->getActionName() . '.php';

            if (!file_exists($viewPath)) {
                $viewPath = APPLICATION_PATH . '/modules/' . $this->request->getModuleName() . '/views/' . $dir . $controllerName . '/' . $this->request->getActionName() . '.php';
            }

            $viewOutput = $this->view->loadScript($viewPath);
        }
        $this->fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');

        if (($this->fileConfig->get('dbUser')) !== null) {
            // Always allow access to registration (previously no access for guests to the user module lead to them being unable to register)
            $accesses = ($this->request->getModuleName() === 'user' && ($this->request->getControllerName() === 'regist' || $this->request->getControllerName() === 'login')) || $this->accesses->hasAccess('Module');
        } else {
            $accesses = true;
        }

        if (!empty($viewOutput)) {
            if ($this->request->isAdmin()) {
                $controller->getLayout()->setContent($viewOutput);
            } elseif ($accesses) {
                $controller->getLayout()->setContent($viewOutput);
            } else {
                $this->translator->load(APPLICATION_PATH . '/modules/user/translations/');

                $controller->getLayout()->setContent($this->accesses->getErrorPage($this->translator->trans('noAccessPage')));
            }
        }

        if ($this->request->isAjax()) {
            echo $viewOutput;
        } elseif ($controller->getLayout()->getDisabled() === false) {
            if ($controller->getLayout()->getFile() != '') {
                $this->layout->loadScript(APPLICATION_PATH . '/' . $controller->getLayout()->getFile() . '.php');
            }
        }
    }

    /**
     * Loads controller defined by the request object.
     *
     * @return \Ilch\Controller\Base|null
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function loadController()
    {
        $controllerName = $this->request->getControllerName();
        $findSub = strpos($controllerName, '_');
        $dir = '';

        if ($findSub !== false) {
            $controllerParts = explode('_', $this->request->getControllerName());
            $controllerName = $controllerParts[1];
            $dir = ucfirst($controllerParts[0]) . '\\';
        }

        if ($this->request->isAdmin()) {
            $controller = '\\Modules\\' . ucfirst($this->request->getModuleName()) . '\\Controllers\\Admin\\' . $dir . ucfirst($controllerName);
        } else {
            $controller = '\\Modules\\' . ucfirst($this->request->getModuleName()) . '\\Controllers\\' . $dir . ucfirst($controllerName);
        }

        // Check if module exists.
        if (!is_dir(APPLICATION_PATH . '/modules/' . $this->request->getModuleName())) {
            $errorModule = $this->request->getModuleName();

            $url = new \Ilch\Controller\Base($this->layout, $this->view, $this->request, $this->router, $this->translator);
            $url->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Module', 'errorText' => $errorModule]);
        }

        // Check if controller exists.
        if (!class_exists($controller)) {
            $errorController = $this->request->getControllerName();

            $url = new \Ilch\Controller\Base($this->layout, $this->view, $this->request, $this->router, $this->translator);
            $url->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Controller', 'errorText' => $errorController]);
        } else {
            $controller = new $controller($this->layout, $this->view, $this->request, $this->router, $this->translator);
            $action = $this->request->getActionName() . 'Action';

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
        return null;
    }

    /**
     * Returns the view object.
     *
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * Returns the request object.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
