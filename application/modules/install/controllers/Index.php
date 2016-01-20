<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Install\Controllers;

class Index extends \Ilch\Controller\Frontend
{
    public function init()
    {
        $this->getLayout()->setFile('modules/install/layouts/index');

        /*
         * Dont set a time limit for installer.
         */
        @set_time_limit(0);

        $menu = array
        (
            'index' => array
            (
                'langKey' => 'menuWelcomeAndLanguage'
            ),
            'license' => array
            (
                'langKey' => 'menuLicence'
            ),
            'systemcheck' => array
            (
                'langKey' => 'menuSystemCheck'
            ),
            'database' => array
            (
                'langKey' => 'menuDatabase'
            ),
            'config' => array
            (
                'langKey' => 'menuConfig'
            ),
            'finish' => array
            (
                'langKey' => 'menuFinish'
            ),
        );

        foreach ($menu as $key => $values) {
            if ($this->getRequest()->getActionName() === $key) {
                break;
            }

            $menu[$key]['done'] = true;
        }

        $this->getLayout()->set('menu', $menu);
    }

    public function indexAction()
    {
        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $local = $this->getRequest()->getParam('language');

        if ($local) {
            $this->getTranslator()->setLocale($local);
            $_SESSION['language'] = $local;
            $this->redirect(array('action' => 'index'));
        }

        if ($this->getRequest()->isPost()) {
            $_SESSION['install']['timezone'] = $this->getRequest()->getPost('timezone');
            $this->redirect(array('action' => 'license'));
        }

        if (!empty($_SESSION['install']['timezone'])) {
            $this->getView()->set('timezone', $_SESSION['install']['timezone']);
        } else {
            $this->getView()->set('timezone', SERVER_TIMEZONE);
        }

        $this->getView()->set('timezones', \DateTimeZone::listIdentifiers());
    }

    public function licenseAction()
    {
        $this->getView()->set('licenceText', file_get_contents(APPLICATION_PATH.'/../licence.txt'));

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('licenceAccepted')) {
                $this->redirect(array('action' => 'systemcheck'));
            } else {
                $this->getView()->set('error', true);
            }
        }
    }

    public function systemcheckAction()
    {
        $errors = array();
        $this->getView()->set('phpVersion', phpversion());

        if (!version_compare(phpversion(), '5.4.0', '>=')) {
            $errors['version'] = true;
        }

        if (!is_writable(CONFIG_PATH)) {
            $errors['writableConfig'] = true;
        }

        if (!is_writable(APPLICATION_PATH.'/../.htaccess')) {
            $errors['writableHtaccess'] = true;
        }

        if (!is_writable(APPLICATION_PATH.'/modules/media/static/upload/')) {
             $errors['writableMedia'] = true;
        }

        if (!is_writable(APPLICATION_PATH.'/modules/user/static/upload/avatar/')) {
             $errors['writableAvatar'] = true;
        }

        if ($this->getRequest()->isPost() && empty($errors)) {
            $this->redirect(array('action' => 'database'));
        }
    }

    public function databaseAction()
    {
        $errors = array();

        if ($this->getRequest()->isPost()) {
            $_SESSION['install']['dbEngine'] = $this->getRequest()->getPost('dbEngine');
            $_SESSION['install']['dbHost'] = $this->getRequest()->getPost('dbHost');
            $_SESSION['install']['dbUser'] = $this->getRequest()->getPost('dbUser');
            $_SESSION['install']['dbPassword'] = $this->getRequest()->getPost('dbPassword');
            $_SESSION['install']['dbName'] = $this->getRequest()->getPost('dbName');
            $_SESSION['install']['dbPrefix'] = $this->getRequest()->getPost('dbPrefix');

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
            } catch (\RuntimeException $ex) {
                $errors['dbConnection'] = 'dbConnectionError';
            }

            if (!$db->setDatabase($this->getRequest()->getPost('dbName'))) {
                $errors['dbDatabase'] = 'dbDatabaseError';
            }

            if (empty($errors)) {
                $this->redirect(array('action' => 'config'));
            }

            $this->getView()->set('errors', $errors);
        }

        foreach (array('dbHost', 'dbUser', 'dbPassword', 'dbName', 'dbPrefix') as $name) {
            if (!empty($_SESSION['install'][$name])) {
                $this->getView()->set($name, $_SESSION['install'][$name]);
            }
        }
    }

    public function configAction()
    {
        $errors = array();

        if ($this->getRequest()->isPost()) {
            $_SESSION['install']['usage'] = $this->getRequest()->getPost('usage');
            $_SESSION['install']['modulesToInstall'][$_SESSION['install']['usage']] = $this->getRequest()->getPost('modulesToInstall');
            $_SESSION['install']['adminName'] = $this->getRequest()->getPost('adminName');
            $_SESSION['install']['adminPassword'] = $this->getRequest()->getPost('adminPassword');
            $_SESSION['install']['adminPassword2'] = $this->getRequest()->getPost('adminPassword2');
            $_SESSION['install']['adminEmail'] = $this->getRequest()->getPost('adminEmail');

            if (empty($_SESSION['install']['adminName'])) {
                $errors['adminName'] = 'fieldEmpty';
            }

            if (empty($_SESSION['install']['adminPassword'])) {
                $errors['adminPassword'] = 'fieldEmpty';
            }

            if ($_SESSION['install']['adminPassword'] !== $_SESSION['install']['adminPassword2']) {
                $errors['adminPassword2'] = 'fieldDiffersPassword';
            }

            if (empty($_SESSION['install']['adminEmail'])) {
                $errors['adminEmail'] = 'fieldEmpty';
            } elseif (!filter_var($_SESSION['install']['adminEmail'], FILTER_VALIDATE_EMAIL)) {
                $errors['adminEmail'] = 'fieldEmail';
            }

            if (empty($errors)) {
                /*
                 * Write install config.
                 */
                $fileConfig = new \Ilch\Config\File();
                $fileConfig->set('dbEngine', $_SESSION['install']['dbEngine']);
                $fileConfig->set('dbHost', $_SESSION['install']['dbHost']);
                $fileConfig->set('dbUser', $_SESSION['install']['dbUser']);
                $fileConfig->set('dbPassword', $_SESSION['install']['dbPassword']);
                $fileConfig->set('dbName', $_SESSION['install']['dbName']);
                $fileConfig->set('dbPrefix', $_SESSION['install']['dbPrefix']);
                $fileConfig->saveConfigToFile(CONFIG_PATH.'/config.php');

                /*
                 * Initialize install database.
                 */
                $dbFactory = new \Ilch\Database\Factory();
                $db = $dbFactory->getInstanceByConfig($fileConfig);
                \Ilch\Registry::set('db', $db);

                $modulesToInstall = $_SESSION['install']['modulesToInstall'][$_SESSION['install']['usage']];
                if (!empty($modulesToInstall)) {
                    $modulesToInstall = array_merge(array('admin', 'article', 'user', 'page', 'media', 'comment', 'imprint', 'contact', 'privacy', 'statistic'), $modulesToInstall);
                } else {
                    $modulesToInstall = array('admin', 'article', 'user', 'page', 'media', 'comment', 'imprint', 'contact', 'privacy', 'statistic');
                }

                $moduleMapper = new \Modules\Admin\Mappers\Module();

                /*
                 * Clear old tables.
                 */
                $db->dropTablesByPrefix($db->getPrefix());

                foreach ($modulesToInstall as $module) {
                    $configClass = '\\Modules\\'.ucfirst($module).'\\Config\\config';
                    $config = new $configClass($this->getTranslator());
                    $config->install();

                    if (!empty($config->config)) {
                        $moduleModel = new \Modules\Admin\Models\Module();
                        $moduleModel->setKey($config->config['key']);

                        if (isset($config->config['author'])) {
                            $moduleModel->setAuthor($config->config['author']);
                        }

                        if (isset($config->config['languages'])) {
                            foreach ($config->config['languages'] as $key => $value) {
                                $moduleModel->addContent($key, $value);
                            }
                        }

                        if (isset($config->config['system_module'])) {
                            $moduleModel->setSystemModule(true);
                        }

                        $moduleModel->setIconSmall($config->config['icon_small']);
                        $moduleMapper->save($moduleModel);
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

                /*
                 * Will not linked in menu
                 */
                foreach ($modulesToInstall as $module) {
                    if (in_array($module, array('comment', 'shoutbox', 'admin', 'media', 'page', 'newsletter', 'statistic'))) {
                        continue;
                    }

                    $configClass = '\\Modules\\'.ucfirst($module).'\\Config\\config';
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
                        (2, 30, 0, 0, 0, 'article_categories', 4, 'Kategorien', '', ''),
                        (2, 40, 0, 0, 0, 'article_archive', 4, 'Archive', '', '')";
                $db->queryMulti($boxes);

                unset($_SESSION['install']);
                $this->redirect(array('action' => 'finish'));
            }

            $this->getView()->set('errors', $errors);
        }

        foreach (array('modulesToInstall', 'usage', 'adminName', 'adminPassword', 'adminPassword2', 'adminEmail') as $name) {
            if (!empty($_SESSION['install'][$name])) {
                $this->getView()->set($name, $_SESSION['install'][$name]);
            }
        }
    }

    public function ajaxconfigAction()
    {
        $type = $this->getRequest()->getParam('type');
        $this->getRequest()->setIsAjax(true);
        $modules = array();

        /*
         * System-Modules
         */
        $modules['user']['types']       = array();
        $modules['article']['types']    = array();
        $modules['page']['types']       = array();
        $modules['media']['types']      = array();
        $modules['comment']['types']    = array();
        $modules['contact']['types']    = array();
        $modules['imprint']['types']    = array();
        $modules['privacy']['types']    = array();
        $modules['statistic']['types']  = array();

        /*
         * Optional-Modules.
         */
        $modules['checkout']['types']   = array('clan');
        $modules['war']['types']        = array('clan');
        $modules['history']['types']    = array('clan');
        $modules['rule']['types']       = array('clan');
        $modules['training']['types']   = array('clan');
        $modules['forum']['types']      = array('clan', 'private');
        $modules['guestbook']['types']  = array('clan', 'private');
        $modules['link']['types']       = array('clan', 'private');
        $modules['linkus']['types']     = array('clan', 'private');
        $modules['partner']['types']    = array('clan', 'private');
        $modules['shoutbox']['types']   = array('clan', 'private');
        $modules['gallery']['types']    = array('clan', 'private');
        $modules['downloads']['types']  = array('clan', 'private');
        $modules['newsletter']['types'] = array('clan', 'private');
        $modules['birthday']['types']   = array('clan', 'private');
        $modules['events']['types']     = array('clan', 'private');
        $modules['calendar']['types']   = array('clan', 'private');
        $modules['away']['types']       = array('clan', 'private');
        $modules['awards']['types']     = array('clan', 'private');
        $modules['jobs']['types']       = array('clan', 'private');
        $modules['faq']['types']        = array('clan', 'private');

        foreach ($modules as $key => $module) {
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\config';
            $config = new $configClass($this->getTranslator());
            $modules[$key]['config'] = $config;

            if(in_array($type, $module['types']))
            {
               $modules[$key]['checked'] = true;
            }
        }

        $modulesToInstall = array();

        if(!empty($_SESSION['install']['modulesToInstall'][$type]))
        {
            $modulesToInstall = $_SESSION['install']['modulesToInstall'][$type];
        }

        $this->getView()->set('modulesToInstall', $modulesToInstall);
        $this->getView()->set('modules', $modules);
    }

    public function finishAction()
    {
    }
}
