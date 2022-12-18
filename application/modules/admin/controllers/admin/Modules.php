<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Models\Module as ModuleModel;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Mappers\NotificationPermission as NotificationPermissionMapper;
use Modules\User\Mappers\Group as GroupMapper;

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
                'name' => 'menuUpdates',
                'active' => false,
                'icon' => 'fa fa-refresh',
                'url' => $this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'updates'])
            ],
            [
                'name' => 'menuSearch',
                'active' => false,
                'icon' => 'fa fa-search',
                'url' => $this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'search'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'notinstalled') {
            $items[1]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() === 'updates') {
            $items[2]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'search' || $this->getRequest()->getActionName() === 'show') {
            $items[3]['active'] = true;
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
        $gotokey = true;
        if ($this->getRequest()->getParam('anchor') === 'false') {
            $gotokey = false;
        }

        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuInstalled'), ['action' => 'index']);

        $dependencies = [];
        $configurations = [];

        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $key = basename($modulesPath);

            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
            if (class_exists($configClass)) {
                $config = new $configClass($this->getTranslator());
                $dependencies[$key] = (!empty($config->config['depends']) ? $config->config['depends'] : []);
                $configurations[$key] = $config->config;
            }
        }

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'modules.php')
            ->set('modules', $moduleMapper->getModules())
            ->set('versionsOfModules', $moduleMapper->getVersionsOfModules())
            ->set('dependencies', $dependencies)
            ->set('configurations', $configurations)
            ->set('coreVersion', $this->getConfig()->get('version'))
            ->set('gotokey', $gotokey);
    }

    public function notinstalledAction()
    {
        $gotokey = true;
        if ($this->getRequest()->getParam('anchor') === 'false') {
            $gotokey = false;
        }

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

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'modules.php')
            ->set('versionsOfModules', $moduleMapper->getVersionsOfModules())
            ->set('modulesNotInstalled', $modulesNotInstalled)
            ->set('dependencies', $dependencies)
            ->set('coreVersion', $this->getConfig()->get('version'))
            ->set('gotokey', $gotokey);
    }

    public function searchAction()
    {
        $gotokey = true;
        if ($this->getRequest()->getParam('anchor') === 'false') {
            $gotokey = false;
        }

        $moduleMapper = new ModuleMapper();
        $groupMapper = new GroupMapper();

        $groups = $groupMapper->getGroupList();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search']);

        try {
            if ($this->getRequest()->isSecure()) {
                $moduleFilename = $this->getRequest()->getParam('key').'-v'.$this->getRequest()->getParam('version');

                $transfer = new \Ilch\Transfer();
                $transfer->setZipSavePath(ROOT_PATH.'/updates/');
                $transfer->setDownloadUrl($this->getConfig()->get('updateserver').'modules/'.$moduleFilename.'.zip');
                $transfer->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'modules/'.$moduleFilename.'.zip-signature.sig');

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

                foreach ($groups as $key => $group) {
                    if ($group->getId() !== 1) {
                        $groupMapper->saveAccessData($group->getId(), $this->getRequest()->getParam('key'), 1, 'module');
                    }
                }

                $this->addMessage('downSuccess');
            }
        } finally {
            if ($this->getRequest()->isSecure()) {
                if ($this->getRequest()->getPost('gotokey')) {
                    $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => '#Module_' . $this->getRequest()->getParam('key')]);
                } else {
                    $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => 'false']);
                }
            }
            $dependencies = [];
            $modulesDir = [];
            foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
                $key = basename($modulesPath);
                $modulesDir[] = $key;

                $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
                if (class_exists($configClass)) {
                    $config = new $configClass($this->getTranslator());
                    $dependencies[$key] = (!empty($config->config['depends']) ? $config->config['depends'] : []);
                }
            }

            $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'modules.php')
                ->set('versionsOfModules', $moduleMapper->getVersionsOfModules())
                ->set('modules', $modulesDir)
                ->set('dependencies', $dependencies)
                ->set('coreVersion', $this->getConfig()->get('version'))
                ->set('gotokey', $gotokey);
        }
    }

    public function updatesAction()
    {
        $gotokey = true;
        if ($this->getRequest()->getParam('anchor') === 'false') {
            $gotokey = false;
        }

        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('updatesAvailable'), ['action' => 'index']);

        $dependencies = [];
        $configurations = [];

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

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'modules.php')
            ->set('modules', $moduleMapper->getModules())
            ->set('versionsOfModules', $moduleMapper->getVersionsOfModules())
            ->set('dependencies', $dependencies)
            ->set('configurations', $configurations)
            ->set('coreVersion', $this->getConfig()->get('version'))
            ->set('gotokey', $gotokey);
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            try {
                $key = $this->getRequest()->getParam('key');
                $moduleFilename = $key.'-v'.$this->getRequest()->getParam('version');

                $transfer = new \Ilch\Transfer();
                $transfer->setZipSavePath(ROOT_PATH.'/updates/');
                $transfer->setDownloadUrl($this->getConfig()->get('updateserver').'modules/'.$moduleFilename.'.zip');
                $transfer->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'modules/'.$moduleFilename.'.zip-signature.sig');

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
                if ($this->getRequest()->getPost('gotokey')) {
                    $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => '#Module_' . $key]);
                } else {
                    $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => 'false']);
                }
            }
        }
    }

    public function localUpdateAction()
    {
        try {
            $moduleMapper = new ModuleMapper();

            $key = $this->getRequest()->getParam('key');
            $moduleModel = $moduleMapper->getModuleByKey($key);
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\Config';
            $config = new $configClass($this->getTranslator());
            // TODO: Check if update failed to display moduleUpdateFailed etc.
            $config->getUpdate($moduleModel->getVersion());
            $moduleMapper->updateVersion($key, $config->config['version']);
            $this->addMessage('updateSuccess');
        } catch (\Exception $e) {
            $this->addMessage('moduleUpdateFailed', 'danger');
        } finally {
            if ($this->getRequest()->getPost('gotokey')) {
                $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => '#Module_' . $key]);
            } else {
                $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => 'false']);
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

        $modulesDir = [];
        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $modulesDir[] = basename($modulesPath);
        }

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver'))
            ->set('versionsOfModules', $moduleMapper->getVersionsOfModules())
            ->set('moduleMapper', $moduleMapper)
            ->set('modules', $modulesDir)
            ->set('coreVersion', $this->getConfig()->get('version'));
    }

    public function installAction()
    {
        $moduleMapper = new ModuleMapper();
        $boxMapper = new BoxMapper();
        $groupMapper = new GroupMapper();

        $groups = $groupMapper->getGroupList();
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
                if (isset($config->config['hide_menu'])) {
                    $moduleModel->setHideMenu(true);
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

                foreach ($groups as $key => $group) {
                    if ($group->getId() !== 1) {
                        $groupMapper->saveAccessData($group->getId(), $key, 1, 'module');
                    }
                }
            }

            $this->addMessage('installSuccess');
        }

        if ($this->getRequest()->getPost('gotokey')) {
            $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => '#Module_' . $this->getRequest()->getParam('key')]);
        } elseif ($this->getRequest()->getParam('from') === 'notinstalled') {
                $this->redirect(['action' => $this->getRequest()->getParam('from')]);
            } else {
                $this->redirect(['action' => $this->getRequest()->getParam('from'), 'anchor' => 'false']);
            }
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

    public function refreshURLAction()
    {
        if (!empty(url_get_contents($this->getConfig()->get('updateserver').'modules.php', true, true))) {
            $this->redirect()
                ->withMessage('updateSuccess')
                ->to(['action' => $this->getRequest()->getParam('from')]);
        }

        $this->redirect()
            ->withMessage('lastUpdateError', 'danger')
            ->to(['action' => $this->getRequest()->getParam('from')]);
    }
}
