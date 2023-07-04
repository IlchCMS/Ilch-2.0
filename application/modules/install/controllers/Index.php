<?php

/**
 * @copyright Ilch 2
 */

namespace Modules\Install\Controllers;

use Ilch\Config\File;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function init()
    {
        if (!extension_loaded('openssl')) {
            die('Ilch CMS 2.* needs at least the PHP openssl extension to try an install.');
        }
        $fileConfig = new File();
        $fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');
        if ($fileConfig->get('dbUser') !== null && $this->getRequest()->getActionName() !== 'finish') {
            /*
             * Cms is installed
             */
            $this->redirect()->to(null);
        } else {
            /*
             * Cms not installed yet.
             */
            $this->getLayout()->setFile('modules/install/layouts/index');

            /*
             * Dont set a time limit for installer.
             */
            @set_time_limit(300);

            $menu = [
                'index' => ['langKey' => 'menuWelcome'],
                'license' => ['langKey' => 'menuLicense'],
                'connect' => ['langKey' => 'menuConnect'],
                'systemcheck' => ['langKey' => 'menuSystemCheck'],
                'database' => ['langKey' => 'menuDatabase'],
                'configuration' => ['langKey' => 'menuConfiguration'],
                'finish' => ['langKey' => 'menuFinish'],
            ];

            foreach ($menu as $key => $values) {
                if ($this->getRequest()->getActionName() === $key) {
                    break;
                }

                $menu[$key]['done'] = true;
            }

            $this->getLayout()->set('menu', $menu);
        }
    }

    public function indexAction()
    {
        if ($this->getRequest()->getParam('language')) {
            $this->getTranslator()->setLocale($this->getRequest()->getParam('language'));
            $_SESSION['language'] = $this->getRequest()->getParam('language');
            $this->redirect(['action' => 'index']);
        }
        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'language' => 'required',
                'timezone' => 'required',
            ]);

            if ($validation->isValid()) {
                $_SESSION['language'] = $this->getRequest()->getPost('language');
                $_SESSION['install']['timezone'] = $this->getRequest()->getPost('timezone');
                $this->redirect(['action' => 'license']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $timezone = SERVER_TIMEZONE;
        if (!empty($_SESSION['install']['timezone'])) {
            $timezone = $_SESSION['install']['timezone'];
        }

        $this->getView()->set('timezone', $timezone)
            ->set('languages', $this->getTranslator()->getLocaleList())
            ->set('timezones', \DateTimeZone::listIdentifiers());
    }

    public function licenseAction()
    {
        $licenseFile = ROOT_PATH . '/LICENSE';
        if (file_exists($licenseFile)) {
            $license = file_get_contents($licenseFile);
            $licenseContent = nl2br(str_replace(['<', '>'], ['"', '"'], $license));

            $this->getView()->set('licenseText', $licenseContent);
        } else {
            $this->getView()->set('licenseMissing', true);
        }

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'licenseAccepted' => 'acceptLicense',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'licenseAccepted' => 'required|numeric|min:1|max:1',
            ]);

            if ($validation->isValid()) {
                $_SESSION['install']['licenseAccepted'] = $this->getRequest()->getPost('licenseAccepted');
                $this->redirect(['action' => 'connect']);
            }
            unset($_SESSION['install']['licenseAccepted']);
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'license']);
        }

        if (!empty($_SESSION['install']['licenseAccepted'])) {
            $this->getView()->set('licenseAccepted', true);
        }
    }

    public function connectAction()
    {
        if (!extension_loaded('mysqli')) {
            $this->addMessage($this->getTranslator()->trans('mysqliExtensionMissing'), 'danger');
            $this->getView()->set('mysqliExtensionMissing', true);
        } else {
            $fields = ['dbEngine' => 'Mysql', 'dbHost' => 'localhost', 'dbUser' => '', 'dbPassword' => ''];

            if ($this->getRequest()->isPost()) {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'dbEngine' => 'required',
                    'dbHost' => 'required',
                    'dbUser' => 'required',
                ]);

                if ($validation->isValid()) {
                    $ilch = new \Ilch\Database\Factory();
                    $db = $ilch->getInstanceByEngine($this->getRequest()->getPost('dbEngine'));
                    $hostParts = explode(':', $this->getRequest()->getPost('dbHost'));
                    $port = null;

                    if (!empty($hostParts[1])) {
                        $port = $hostParts[1];
                    }

                    try {
                        $db->connect(
                            reset($hostParts),
                            $this->getRequest()->getPost('dbUser'),
                            $this->getRequest()->getPost('dbPassword'),
                            $port
                        );

                        foreach ($fields as $name => $default) {
                            $_SESSION['install'][$name] = $this->getRequest()->getPost($name);
                        }

                        $this->redirect(['action' => 'systemcheck']);
                    } catch (\RuntimeException $ex) {
                        $validation->getErrorBag()->addError('dbEngine', $this->getTranslator()->trans('dbConnectionError'));
                    }
                }
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'connect']);
            }

            foreach ($fields as $name => $default) {
                $this->getView()->set($name, $_SESSION['install'][$name] ?? $default);
            }
        }
    }

    public function systemcheckAction()
    {
        $errors = [];
        $phpVersion = '7.3';
        $this->getView()->set('phpVersion', $phpVersion);
        if (!version_compare(PHP_VERSION, $phpVersion, '>=')) {
            $errors['phpVersion'] = true;
        }

        $hostParts = explode(':', $_SESSION['install']['dbHost']);
        $port = (!empty($hostParts[1])) ? $hostParts[1] : null;
        $dbLinkIdentifier = mysqli_connect($_SESSION['install']['dbHost'], $_SESSION['install']['dbUser'], $_SESSION['install']['dbPassword'], null, $port);
        $dbVersion = mysqli_get_server_info($dbLinkIdentifier);
        if (strpos(mysqli_get_server_info($dbLinkIdentifier), 'MariaDB') !== false) {
            $requiredVersion = '5.5';
        } else {
            $requiredVersion = '5.5.3';
        }
        if (!version_compare($dbVersion, $requiredVersion, '>=')) {
            $errors['dbVersion'] = true;
        }
        $this->getView()->set('requiredVersion', $requiredVersion);
        $this->getView()->set('dbVersion', $dbVersion);

        if (!is_writable(ROOT_PATH)) {
            $errors['writableRootPath'] = true;
        }

        if (!is_writable(APPLICATION_PATH)) {
            $errors['writableConfig'] = true;
        }

        if (!is_writable(ROOT_PATH . '/backups/')) {
            $errors['writableBackups'] = true;
        }

        if (!is_writable(ROOT_PATH . '/updates/')) {
            $errors['writableUpdates'] = true;
        }

        if (!is_writable(ROOT_PATH . '/.htaccess')) {
            $errors['writableHtaccess'] = true;
        }

        if (!is_writable(APPLICATION_PATH . '/modules/media/static/upload/')) {
            $errors['writableMedia'] = true;
        }

        if (!is_writable(APPLICATION_PATH . '/modules/user/static/upload/avatar/')) {
            $errors['writableAvatar'] = true;
        }

        if (!is_writable(APPLICATION_PATH . '/modules/user/static/upload/gallery/')) {
            $errors['writableGallery'] = true;
        }

        if (!is_writable(ROOT_PATH . '/certificate/')) {
            $errors['writableCertificate'] = true;
        }

        if (!extension_loaded('gd')) {
            $errors['gd'] = true;
        }

        if (!extension_loaded('mysqli')) {
            $errors['mysqli'] = true;
        }

        if (!extension_loaded('mbstring')) {
            $errors['mbstring'] = true;
        }

        if (!extension_loaded('zip')) {
            $errors['zip'] = true;
        }

        if (!extension_loaded('openssl')) {
            $errors['openssl'] = true;
        } else {
            if (file_exists(ROOT_PATH . '/certificate/Certificate.crt')) {
                $public_key = file_get_contents(ROOT_PATH . '/certificate/Certificate.crt');
                $certinfo = openssl_x509_parse($public_key);
                $validTo = $certinfo['validTo_time_t'];
                if ($validTo < time()) {
                    $errors['expiredCert'] = true;
                }
            } else {
                $errors['missingCert'] = true;
            }
        }

        if (!extension_loaded('curl')) {
            $errors['curl'] = true;
        }



        if ($this->getRequest()->isPost() && empty($errors)) {
            $this->redirect(['action' => 'database']);
        } elseif ($this->getRequest()->isPost()) {
            $this->redirect(['action' => 'systemcheck']);
        } else {
            $this->getView()->set('errors', $errors);
        }
    }

    public function databaseAction()
    {
        $fields = ['dbName' => '', 'dbPrefix' => 'ilch_'];

        $port = null;
        $hostParts = explode(':', $_SESSION['install']['dbHost']);

        if (!empty($hostParts[1])) {
            $port = $hostParts[1];
        }

        $con = mysqli_connect($_SESSION['install']['dbHost'], $_SESSION['install']['dbUser'], $_SESSION['install']['dbPassword'], null, $port);
        $result = mysqli_query($con, 'SHOW DATABASES');

        $dbList = [];

        if ($result !== false) {
            while ($row = mysqli_fetch_row($result)) {
                if (($row[0] !== 'information_schema') && ($row[0] !== 'performance_schema') && ($row[0] !== 'mysql')) {
                    $dbList[] = $row[0];
                }
            }
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'dbName' => 'required',
                'dbPrefix' => 'required',
            ]);

            if ($validation->isValid()) {
                if (in_array($this->getRequest()->getPost('dbName'), $dbList)) {
                    try {
                        $ilch = new \Ilch\Database\Factory();
                        $db = $ilch->getInstanceByEngine($_SESSION['install']['dbEngine']);

                        $db->connect(
                            reset($hostParts),
                            $_SESSION['install']['dbUser'],
                            $_SESSION['install']['dbPassword'],
                            $port
                        );

                        if ($db->setDatabase($this->getRequest()->getPost('dbName'))) {
                            foreach ($fields as $name => $default) {
                                $_SESSION['install'][$name] = $this->getRequest()->getPost($name);
                            }
                        } else {
                            $validation->getErrorBag()->addError('dbName', $this->getTranslator()->trans('dbDatabaseDoesNotExist'));
                        }

                        $this->redirect(['action' => 'configuration']);
                    } catch (\Exception $e) {
                        $validation->getErrorBag()->addError('dbName', $this->getTranslator()->trans('dbDatabaseCouldNotConnect'));
                    }
                } else {
                    $validation->getErrorBag()->addError('dbName', $this->getTranslator()->trans('dbDatabaseDoesNotExist'));
                }
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'database']);
        }

        foreach ($fields as $name => $default) {
            $this->getView()->set($name, $_SESSION['install'][$name] ?? $default);
        }

        $this->getView()->set('database', $dbList);
    }

    public function configurationAction()
    {
        $fields = ['usage' => '', 'adminName' => '', 'adminPassword' => '', 'adminPassword2' => '', 'adminEmail' => ''];
        $systemModules = ['admin', 'article', 'user', 'media', 'comment', 'imprint', 'contact', 'privacy', 'statistic', 'cookieconsent'];

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'usage' => 'required',
                'adminName' => 'required',
                'adminPassword' => 'required|min:6,string|max:30,string',
                'adminPassword2' => 'required|same:adminPassword|min:6,string|max:30,string',
                'adminEmail' => 'required|email',
            ]);

            if ($this->getRequest()->getPost('modulesToInstall')) {
                $_SESSION['install']['modulesToInstall'][$this->getRequest()->getPost('usage')] = $this->getRequest()->getPost('modulesToInstall');
            }
            if ($validation->isValid()) {
                foreach ($fields as $name => $default) {
                    $_SESSION['install'][$name] = $this->getRequest()->getPost($name);
                }

                /*
                 * Write install config.
                 */
                $fileConfig = new File();
                $fileConfig->set('dbEngine', $_SESSION['install']['dbEngine']);
                $fileConfig->set('dbHost', $_SESSION['install']['dbHost']);
                $fileConfig->set('dbUser', $_SESSION['install']['dbUser']);
                $fileConfig->set('dbPassword', $_SESSION['install']['dbPassword']);
                $fileConfig->set('dbName', $_SESSION['install']['dbName']);
                $fileConfig->set('dbPrefix', $_SESSION['install']['dbPrefix']);
                $fileConfig->saveConfigToFile(CONFIG_PATH . '/config.php');

                /*
                 * Initialize install database.
                 */
                $dbFactory = new \Ilch\Database\Factory();
                $db = $dbFactory->getInstanceByConfig($fileConfig);
                \Ilch\Registry::set('db', $db);

                $modulesToInstall = $_SESSION['install']['modulesToInstall'][$_SESSION['install']['usage']];
                if (!empty($modulesToInstall)) {
                    $modulesToInstall = array_merge($systemModules, $modulesToInstall);
                } else {
                    $modulesToInstall = $systemModules;
                }

                $moduleMapper = new \Modules\Admin\Mappers\Module();
                $boxMapper = new \Modules\Admin\Mappers\Box();

                // Clear old tables
                $db->query('SET FOREIGN_KEY_CHECKS = 0;');
                $db->dropTablesByPrefix($db->getPrefix());

                foreach ($modulesToInstall as $module) {
                    $configClass = '\\Modules\\' . ucfirst($module) . '\\Config\\Config';
                    $config = new $configClass($this->getTranslator());
                    $config->install();

                    if (!empty($config->config)) {
                        if ($config->config['key'] !== 'admin') {
                            $moduleModel = new \Modules\Admin\Models\Module();
                            $moduleModel->setKey($config->config['key']);
                            if (isset($config->config['author'])) {
                                $moduleModel->setAuthor($config->config['author']);
                            }
                            if (isset($config->config['link'])) {
                                $moduleModel->setLink($config->config['link']);
                            }
                            if (isset($config->config['languages'])) {
                                foreach ($config->config['languages'] as $key => $value) {
                                    $moduleModel->addContent($key, $value);
                                }
                            }
                            if (isset($config->config['system_module'])) {
                                $moduleModel->setSystemModule(true);
                            }
                            if (isset($config->config['hide_menu'])) {
                                $moduleModel->setHideMenu(true);
                            }
                            if (isset($config->config['version'])) {
                                $moduleModel->setVersion($config->config['version']);
                            }
                            $moduleModel->setIconSmall($config->config['icon_small']);
                            $moduleMapper->save($moduleModel);
                        }

                        if (isset($config->config['boxes'])) {
                            $boxModel = new \Modules\Admin\Models\Box();
                            $boxModel->setModule($config->config['key']);
                            foreach ($config->config['boxes'] as $key => $value) {
                                $boxModel->addContent($key, $value);
                            }
                            $boxMapper->install($boxModel);
                        }
                    }
                }

                $menuMapper = new \Modules\Admin\Mappers\Menu();
                $menu1 = new \Modules\Admin\Models\Menu();
                $menu1->setId(1);
                $menu1->setTitle('Hauptmenü');
                $menuMapper->save($menu1);

                $menu2 = new \Modules\Admin\Models\Menu();
                $menu2->setId(2);
                $menu2->setTitle('Hauptmenü 2');
                $menuMapper->save($menu2);

                $sort = 0;
                $menuItem = new \Modules\Admin\Models\MenuItem();
                $menuItem->setMenuId(1);
                $menuItem->setParentId(0);
                $menuItem->setTitle('Menü');
                $menuItem->setType(0);
                $menuMapper->saveItem($menuItem);

                foreach ($modulesToInstall as $module) {
                    /*
                     * Will not linked in menu
                     */
                    if (in_array($module, ['comment', 'shoutbox', 'admin', 'media', 'newsletter', 'statistic', 'cookieconsent', 'error', 'contact', 'imprint', 'privacy'])) {
                        continue;
                    }

                    $configClass = '\\Modules\\' . ucfirst($module) . '\\Config\\Config';
                    $config = new $configClass($this->getTranslator());

                    $menuItem = new \Modules\Admin\Models\MenuItem();
                    $menuItem->setMenuId(1);
                    $menuItem->setSort($sort);
                    $menuItem->setParentId(1);
                    $menuItem->setType(3);
                    $menuItem->setModuleKey($config->config['key']);
                    $menuItem->setTitle($config->config['languages'][$this->getTranslator()->getLocale()]['name']);
                    $menuMapper->saveItem($menuItem);
                    $sort += 10;
                }

                $boxes = "INSERT INTO `[prefix]_menu_items` (`menu_id`, `sort`, `parent_id`, `page_id`, `box_id`, `box_key`, `type`, `title`, `href`, `module_key`) VALUES
                    (1, 80, 0, 0, 0, 'user_login', 4, 'Login', '', ''),
                    (1, 90, 0, 0, 0, 'admin_layoutswitch', 4, 'Layout', '', ''),
                    (1, 100, 0, 0, 0, 'statistic_stats', 4, 'Statistik', '', ''),
                    (1, 110, 0, 0, 0, 'statistic_online', 4, 'Online', '', ''),
                    (2, 10, 0, 0, 0, 'admin_langswitch', 4, 'Sprache', '', ''),
                    (2, 20, 0, 0, 0, 'article_article', 4, 'Letzte Artikel', '', ''),
                    (2, 30, 0, 0, 0, 'article_archive', 4, 'Archiv', '', ''),
                    (2, 40, 0, 0, 0, 'article_categories', 4, 'Kategorien', '', ''),
                    (2, 50, 0, 0, 0, 'article_keywords', 4, 'Keywords', '', '');";
                $db->query($boxes);
                $db->query('SET FOREIGN_KEY_CHECKS = 1;');

                unset($_SESSION['install']);
                $this->redirect(['action' => 'finish']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'configuration']);
        }

        foreach ($fields as $name => $default) {
            $this->getView()->set($name, $_SESSION['install'][$name] ?? $default);
        }
        $this->getView()->set('modulesToInstall', $_SESSION['install']['modulesToInstall'] ?? '')
            ->set('usages', ['clan', 'private']);
    }

    public function ajaxconfigAction()
    {
        $reload = $this->getRequest()->getParam('reload') ?? false;
        $type = $this->getRequest()->getParam('type');
        $this->getRequest()->setIsAjax(true);
        $modules = [];

        /*
         * System-Modules
         */
        $modules['user']['types'] = [];
        $modules['article']['types'] = [];
        $modules['media']['types'] = [];
        $modules['comment']['types'] = [];
        $modules['contact']['types'] = [];
        $modules['imprint']['types'] = [];
        $modules['privacy']['types'] = [];
        $modules['cookieconsent']['types'] = [];
        $modules['statistic']['types'] = [];

        /*
         * Optional-Modules.
         */
        // calendar module needs to be installed early, so that the table calendar_events exists for modules that use it.
        $modules['calendar']['types'] = ['clan', 'private'];
        $modules['checkoutbasic']['types'] = ['clan'];
        $modules['war']['types'] = ['clan'];
        $modules['history']['types'] = ['clan'];
        $modules['rule']['types'] = ['clan'];
        $modules['teams']['types'] = ['clan'];
        $modules['training']['types'] = ['clan'];
        $modules['forum']['types'] = ['clan', 'private'];
        $modules['guestbook']['types'] = ['clan', 'private'];
        $modules['link']['types'] = ['clan', 'private'];
        $modules['linkus']['types'] = ['clan', 'private'];
        $modules['partner']['types'] = ['clan', 'private'];
        $modules['shoutbox']['types'] = ['clan', 'private'];
        $modules['gallery']['types'] = ['clan', 'private'];
        $modules['downloads']['types'] = ['clan', 'private'];
        $modules['newsletter']['types'] = ['clan', 'private'];
        $modules['birthday']['types'] = ['clan', 'private'];
        $modules['events']['types'] = ['clan', 'private'];
        $modules['away']['types'] = ['clan', 'private'];
        $modules['awards']['types'] = ['clan', 'private'];
        $modules['jobs']['types'] = ['clan', 'private'];
        $modules['faq']['types'] = ['clan', 'private'];
        $modules['vote']['types'] = ['clan', 'private'];
        $modules['shop']['types'] = ['clan', 'private'];

        $modulesToInstall = [];
        if (!empty($_SESSION['install']['modulesToInstall'][$type])) {
            if ($reload) {
                unset($_SESSION['install']['modulesToInstall'][$type]);
            } else {
                    $modulesToInstall = $_SESSION['install']['modulesToInstall'][$type];
            }
        }

        $dependencies = [];
        foreach ($modules as $key => $module) {
            $configClass = '\\Modules\\' . ucfirst($key) . '\\Config\\Config';
            $config = new $configClass($this->getTranslator());
            $modules[$key]['config'] = $config;
            $dependencies[$key] = (!empty($config->config['depends']) ? $config->config['depends'] : []);

            if (in_array($type, $module['types']) && (empty($modulesToInstall) || in_array($key, $modulesToInstall))) {
                $modules[$key]['checked'] = true;
            }
        }

        $this->getView()->set('modulesToInstall', $modulesToInstall);
        $this->getView()->set('modules', $modules)
            ->set('dependencies', $dependencies);
    }

    public function finishAction()
    {
    }
}
