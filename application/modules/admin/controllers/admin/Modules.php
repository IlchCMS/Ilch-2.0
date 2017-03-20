<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Models\Module as ModuleModel;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;

class Modules extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuInstalled',
                'active' => false,
                'icon' => 'fa fa-folder',
                'url' => $this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'index'])
            ],
            [
                'name' => 'menuNotInstalled',
                'active' => false,
                'icon' => 'fa fa-folder-open',
                'url' => $this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'notinstalled'])
            ],
            [
                'name' => 'menuSearch',
                'active' => false,
                'icon' => 'fa fa-search',
                'url' => $this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'search'])
            ],
        ];

        if ($this->getRequest()->getActionName() == 'notinstalled') {
            $items[1]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'search' OR $this->getRequest()->getActionName() == 'show') {
            $items[2]['active'] = true; 
        } else {
            $items[0]['active'] = true; 
        }

        $this->getLayout()->addMenu
        (
            'menuModules',
            $items
        );
    }

    public function indexAction()
    {
        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuInstalled'), ['action' => 'index']);

        $dependencies = [];

        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $key = basename($modulesPath);
            $modulesDir[] = $key;

            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
            if (class_exists($configClass)) {
                $config = new $configClass($this->getTranslator());
                $dependencies[$key] = (!empty($config->config['depends']) ? $config->config['depends'] : []);
                $configurations[$key] = $config->config;
            }
        }

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'modules.php');
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('versionsOfModules', $moduleMapper->getVersionsOfModules());
        $this->getView()->set('dependencies', $dependencies);
        $this->getView()->set('configurations', $configurations);
        $this->getView()->set('coreVersion', $this->getConfig()->get('version'));
    }

    public function notinstalledAction()
    {
        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuNotInstalled'), ['action' => 'notinstalled']);

        $modulesNotInstalled = $moduleMapper->getModulesNotInstalled();

        // Return early if there is nothing to show to avoid doing unnecessary work.
        if (empty($modulesNotInstalled)) {
            return;
        }

        $dependencies = [];

        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $key = basename($modulesPath);
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
            if (class_exists($configClass)) {
                $config = new $configClass($this->getTranslator());
                $dependencies[$key] = (!empty($config->config['depends']) ? $config->config['depends'] : []);
            }
        }

        $this->getView()->set('versionsOfModules', $moduleMapper->getVersionsOfModules());
        $this->getView()->set('modulesNotInstalled', $modulesNotInstalled);
        $this->getView()->set('dependencies', $dependencies);
        $this->getView()->set('coreVersion', $this->getConfig()->get('version'));
    }

    public function searchAction()
    {
        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search']);

        try {
            if ($this->getRequest()->isSecure()) {
                $transfer = new \Ilch\Transfer();
                $transfer->setZipSavePath(ROOT_PATH.'/updates/');
                $transfer->setDownloadUrl($this->getConfig()->get('updateserver').'modules/'.$this->getRequest()->getParam('key').'.zip');
                $transfer->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'modules/'.$this->getRequest()->getParam('key').'.zip-signature.sig');

                if (!$transfer->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->addMessage('certMissingOrExpired', 'danger');
                    return;
                }

                if (!$transfer->save()) {
                    $this->addMessage('moduleVerificationFailed', 'danger');
                    return;
                }

                if (!$transfer->install()) {
                    $this->addMessage('moduleInstallationFailed', 'danger');
                    return;
                }

                $this->addMessage('downSuccess');
            }
        } finally {
            $dependencies = [];

            foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
                $key = basename($modulesPath);
                $modulesDir[] = $key;

                $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
                if (class_exists($configClass)) {
                    $config = new $configClass($this->getTranslator());
                    $dependencies[$key] = (!empty($config->config['depends']) ? $config->config['depends'] : []);
                }
            }

            $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'modules.php');
            $this->getView()->set('versionsOfModules', $moduleMapper->getVersionsOfModules());
            $this->getView()->set('modules', $modulesDir);
            $this->getView()->set('dependencies', $dependencies);
            $this->getView()->set('coreVersion', $this->getConfig()->get('version'));
        }
    }
    
    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            try {
                $key = $this->getRequest()->getParam('key');

                $transfer = new \Ilch\Transfer();
                $transfer->setZipSavePath(ROOT_PATH.'/updates/');
                $transfer->setDownloadUrl($this->getConfig()->get('updateserver').'modules/'.$key.'.zip');
                $transfer->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'modules/'.$key.'.zip-signature.sig');

                if (!$transfer->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->addMessage('certMissingOrExpired', 'danger');
                    return;
                }

                if (!$transfer->save()) {
                    $this->addMessage('moduleVerificationFailed', 'danger');
                    return;
                }

                $moduleMapper = new ModuleMapper();
                $moduleModel = $moduleMapper->getModuleByKey($key);

                if (!$transfer->update($moduleModel->getVersion())) {
                    $this->addMessage('moduleUpdateFailed', 'danger');
                    return;
                }

                $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                $moduleMapper->updateVersion($key, $config->config['version']);
                $this->addMessage('updateSuccess');
            } finally {
                $this->redirect(['action' => $this->getRequest()->getParam('from')]);
            }
        }
    }
    
    public function showAction()
    {
        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search'])
                ->add($this->getTranslator()->trans('menuModules').' '.$this->getTranslator()->trans('info'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $modulesDir[] = basename($modulesPath);
        }

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver'));
        $this->getView()->set('versionsOfModules', $moduleMapper->getVersionsOfModules());
        $this->getView()->set('moduleMapper', $moduleMapper);
        $this->getView()->set('modules', $modulesDir);
        $this->getView()->set('coreVersion', $this->getConfig()->get('version'));
    }

    public function installAction()
    {
        $moduleMapper = new ModuleMapper();
        $boxMapper = new BoxMapper();
        $key = $this->getRequest()->getParam('key');

        if ($this->getRequest()->isSecure()) {
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
            $config = new $configClass($this->getTranslator());
            $config->install();

            if (!empty($config->config)) {
                $moduleModel = new ModuleModel();
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
                if (isset($config->config['isLayout'])) {
                    $moduleModel->setLayoutModule(true);
                }
                if (isset($config->config['link'])) {
                    $moduleModel->setLink($config->config['link']);
                }
                if (isset($config->config['version'])) {
                    $moduleModel->setVersion($config->config['version']);
                }

                $moduleModel->setIconSmall($config->config['icon_small']);
                $moduleMapper->save($moduleModel);

                if (isset($config->config['boxes'])) {
                    $boxModel = new BoxModel();
                    $boxModel->setModule($config->config['key']);
                    foreach ($config->config['boxes'] as $key => $value) {
                        $boxModel->addContent($key, $value);
                    }
                    $boxMapper->install($boxModel);
                }
            }

            $this->addMessage('installSuccess');
        }

        $this->redirect(['action' => 'notinstalled']);
    }

    public function uninstallAction()
    {
        $moduleMapper = new ModuleMapper();
        $key = $this->getRequest()->getParam('key');

        if ($this->getRequest()->isSecure()) {
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
            $config = new $configClass($this->getTranslator());
            $config->uninstall();
            $moduleMapper->delete($key);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $notificationPermissionMapper = new NotificationPermissionMapper();
            $notificationsMapper = new NotificationsMapper();

            removeDir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'));

            $notificationPermissionMapper->deletePermissionOfModule($this->getRequest()->getParam('key'));
            $notificationsMapper->deleteNotificationsByModule($this->getRequest()->getParam('key'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'notinstalled']);
    }
}
