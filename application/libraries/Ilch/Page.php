<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Page
{
    /**
     * @var \Ilch\Request
     */
    private $request;

    /**
     * @var \Ilch\Translator
     */
    private $translator;

    /**
     * @var \Ilch\Router
     */
    private $router;

    /**
     * @var \Ilch\Plugin
     */
    private $plugin;

    /**
     * @var \Ilch\Layout\Base
     */
    private $layout;

    /**
     * @var \Ilch\View
     */
    private $view;

    /**
     * @var \Ilch\Config\File
     */
    private $fileConfig;

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

        if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
            list($selector, $authenticator) = explode(':', $_COOKIE['remember']);

            $authTokenMapper = new \Modules\User\Mappers\AuthToken();
            $row = $authTokenMapper->getAuthToken($selector);

            if(!empty($row) && strtotime($row['expires']) >= time()) {
                if (hash_equals($row['token'], hash('sha256', base64_decode($authenticator)))) {
                    $_SESSION['user_id'] = $row['userid'];
                    // A new token is generated, a new hash for the token is stored over the old record, and a new login cookie is issued to the user.
                    $authTokenModel = new \Modules\User\Models\AuthToken();

                    $authTokenModel->setSelector($selector);
                    // 33 bytes (264 bits) of randomness for the actual authenticator. This should be unpredictable in all practical scenarios.
                    $authenticator = openssl_random_pseudo_bytes(33);
                    // SHA256 hash of the authenticator. This mitigates the risk of user impersonation following information leaks.
                    $authTokenModel->setToken(hash('sha256', $authenticator));
                    $authTokenModel->setUserid($_SESSION['user_id']);
                    $authTokenModel->setExpires(date('Y-m-d\TH:i:s', strtotime( '+30 days' )));

                    setcookie('remember', $authTokenModel->getSelector().':'.base64_encode($authenticator), strtotime( '+30 days' ), '/', $_SERVER['SERVER_NAME'], false, false);
                    $authTokenMapper->updateAuthToken($authTokenModel);

                    $controller->redirect('/');
                } else {
                    // If the series is present but the token does not match, a theft is assumed.
                    // The user receives a strongly worded warning and all of the user's remembered sessions are deleted.
                    $authTokenMapper->deleteAllAuthTokenOfUser($row['userid']);
                }
            }
        }

        if ($this->request->isAdmin()) {
            $viewOutput = $this->view->loadScript(APPLICATION_PATH.'/modules/'.$this->request->getModuleName().'/views/admin/'.$dir.$controllerName.'/'.$this->request->getActionName().'.php');
        } else {
            $viewPath = APPLICATION_PATH.'/'.dirname($controller->getLayout()->getFile()).'/views/modules/'.$this->request->getModuleName().'/'.$dir.$controllerName.'/'.$this->request->getActionName().'.php';

            if (!file_exists($viewPath)) {
                $viewPath = APPLICATION_PATH.'/modules/'.$this->request->getModuleName().'/views/'.$dir.$controllerName.'/'.$this->request->getActionName().'.php';
            }

            $viewOutput = $this->view->loadScript($viewPath);
        }
        $this->fileConfig->loadConfigFromFile(CONFIG_PATH.'/config.php');

        if (($this->fileConfig->get('dbUser')) !== null) {
            $accesses = $this->accesses->hasAccess('Module');
        } else {
            $accesses = true;
        }

        if (!empty($viewOutput)) {
            if (!$this->request->isAdmin()) {
                if ($accesses) {
                    $controller->getLayout()->setContent($viewOutput);
                } else {
                    $this->translator->load(APPLICATION_PATH.'/modules/user/translations/');

                    $controller->getLayout()->setContent($this->accesses->getErrorPage($this->translator->trans('noAccessPage')));
                }
            } else {
                $controller->getLayout()->setContent($viewOutput);
            }
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
     * @return \Ilch\Controller\Base
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

        /*
         * Check if module exists.
         */
        if (!is_dir(APPLICATION_PATH.'/modules/'.$this->request->getModuleName())) {
            $errorModule = $this->request->getModuleName();

            $url = new \Ilch\Controller\Base($this->layout, $this->view, $this->request, $this->router, $this->translator);
            $url->redirect(array('module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Module', 'errorText' => $errorModule));
        }

        /*
         * Check if controller exists.
         */
        if (!class_exists($controller)) {
            $errorController = $this->request->getControllerName();

            $url = new \Ilch\Controller\Base($this->layout, $this->view, $this->request, $this->router, $this->translator);
            $url->redirect(array('module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Controller', 'errorText' => $errorController));
        }

        if (class_exists($controller)) {
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
    }

    /**
     * Returns the view object.
     *
     * @return \Ilch\View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Returns the request object.
     *
     * @return \Ilch\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
