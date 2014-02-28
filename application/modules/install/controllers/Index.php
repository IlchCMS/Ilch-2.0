<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Install\Controllers;
defined('ACCESS') or die('no direct access');

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

            $dbConnect = $db->connect(reset($hostParts), $this->getRequest()->getPost('dbUser'), $this->getRequest()->getPost('dbPassword'), $port);

            if (!$dbConnect) {
                $errors['dbConnection'] = 'dbConnectionError';
            }

            if ($dbConnect && !$db->setDatabase($this->getRequest()->getPost('dbName'))) {
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
            $_SESSION['install']['adminName'] = $this->getRequest()->getPost('adminName');
            $_SESSION['install']['adminPassword'] = $this->getRequest()->getPost('adminPassword');
            $_SESSION['install']['adminPassword2'] = $this->getRequest()->getPost('adminPassword2');
            $_SESSION['install']['adminEmail'] = $this->getRequest()->getPost('adminEmail');
            $_SESSION['install']['cmsType'] = $this->getRequest()->getPost('cmsType');

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

                /*
                 * Install every registered module.
                 */
                $modulesToInstall = array('admin', 'user', 'article', 'page', 'guestbook', 'contact', 'partner', 'link', 'shoutbox', 'impressum', 'media', 'comment');
                $moduleMapper = new \Admin\Mappers\Module();

                /*
                 * Clear old tables.
                 */
                $db->dropTablesByPrefix($db->getPrefix());

                foreach ($modulesToInstall as $module) {
                    $configClass = '\\'.ucfirst($module).'\\Config\\config';
                    $config = new $configClass($this->getTranslator());
                    $config->install();

                    if (!empty($config->name)) {
                        $moduleModel = new \Admin\Models\Module();
                        $moduleModel->setKey($config->key);

                        foreach ($config->name as $key => $value) {
                            $moduleModel->addName($key, $value);
                        }

                        $moduleModel->setIconSmall($config->icon_small);
                        $moduleMapper->save($moduleModel);
                    }
                }

                $db->queryMulti(file_get_contents(APPLICATION_PATH.'/modules/install/install/install_'.$_SESSION['install']['cmsType'].'.sql'));

                unset($_SESSION['install']);
                $this->redirect(array('action' => 'finish'));
            }

            $this->getView()->set('errors', $errors);
        }

        foreach (array('adminName', 'adminPassword', 'adminPassword2', 'adminEmail') as $name) {
            if (!empty($_SESSION['install'][$name])) {
                $this->getView()->set($name, $_SESSION['install'][$name]);
            }
        }
    }

    public function finishAction()
    {
    }
}
