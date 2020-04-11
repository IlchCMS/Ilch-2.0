<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Mappers\LayoutAdvSettings as LayoutAdvSettingsMapper;
use Modules\Admin\Models\Layout as LayoutModel;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;
use Ilch\Transfer;

class Layouts extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fas fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'index'])
            ],
            [
                'name' => 'menuSearch',
                'active' => false,
                'icon' => 'fas fa-search',
                'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'search'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fas fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'settings']),
                [
                    'name' => 'menuAdvSettings',
                    'active' => false,
                    'icon' => 'fas fa-cogs',
                    'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'advSettings'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'index') {
            $items[0]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'search' || $this->getRequest()->getActionName() === 'show') {
            $items[1]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'settings') {
            $items[2]['active'] = true;
        } elseif ($this->getRequest()->getActionName() === 'advSettings' || $this->getRequest()->getActionName() === 'advSettingsShow') {
            $items[2][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuLayouts',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index']);

        $layouts = [];
        $versionsOfLayouts = [];
        $modulesNotInstalled = [];
        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            if (is_dir($layoutPath)) {
                $configClass = '\\Layouts\\'.ucfirst(basename($layoutPath)).'\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                $model = new LayoutModel();
                $model->setKey(basename($layoutPath));
                $model->setName($config->config['name']);
                $model->setVersion($config->config['version']);
                $model->setAuthor($config->config['author']);
                if (!empty($config->config['link'])) {
                    $model->setLink($config->config['link']);
                }
                $model->setDesc($config->config['desc']);
                if (!empty($config->config['modulekey'])) {
                    $moduleMapper = new ModuleMapper();
                    if ($moduleMapper->getModuleByKey($config->config['modulekey']) === null) {
                        $modulesNotInstalled[$config->config['modulekey']] = $config->config['modulekey'];
                    }
                    $model->setModulekey($config->config['modulekey']);
                }
                if (!empty($config->config['settings'])) {
                    $model->setSettings($config->config['settings']);
                }
                if (!empty($config->config['ilchCore'])) {
                    $model->setIlchCore($config->config['ilchCore']);
                }
                $layouts[] = $model;
                $versionsOfLayouts[basename($layoutPath)] = $config->config['version'];
            }
        }

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver').'layouts2.php')
            ->set('defaultLayout', $this->getConfig()->get('default_layout'))
            ->set('layouts', $layouts)
            ->set('modulesNotInstalled', $modulesNotInstalled)
            ->set('coreVersion', $this->getConfig()->get('version'))
            ->set('versionsOfLayouts', $versionsOfLayouts);
    }

    public function defaultAction()
    {
        if ($this->getRequest()->isSecure()) {
            $this->getConfig()->set('default_layout', $this->getRequest()->getParam('key'));
        }

        $this->redirect(['action' => 'index']);
    }

    public function searchAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search']);

        try {
            if ($this->getRequest()->isSecure()) {
                $layoutFilename = $this->getRequest()->getParam('key').'-v'.$this->getRequest()->getParam('version');

                $transfer = new Transfer();
                $transfer->setZipSavePath(ROOT_PATH.'/updates/');
                $transfer->setDownloadUrl($this->getConfig()->get('updateserver').'layouts/'.$layoutFilename.'.zip');
                $transfer->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'layouts/'.$layoutFilename.'.zip-signature.sig');

                if (!$transfer->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->addMessage('certMissingOrExpired', 'danger');
                    return;
                }

                if (!$transfer->save()) {
                    $this->addMessage('layoutVerificationFailed', 'danger');
                    return;
                }

                if (!$transfer->install()) {
                    $this->addMessage('layoutInstallationFailed', 'danger');
                    return;
                }

                $this->addMessage('downSuccess');
            }
        } finally {
            $layoutsDir = [];
            $versionsOfLayouts = [];
            foreach (glob(ROOT_PATH.'/application/layouts/*') as $layoutPath) {
                if (is_dir($layoutPath)) {
                    $configClass = '\\Layouts\\'.ucfirst(basename($layoutPath)).'\\Config\\Config';
                    $config = new $configClass($this->getTranslator());
                    $versionsOfLayouts[basename($layoutPath)] = $config->config['version'];
                    $layoutsDir[] = basename($layoutPath);
                }
            }

            $this->getView()->set('updateserver', $this->getConfig()->get('updateserver'))
                ->set('coreVersion', $this->getConfig()->get('version'))
                ->set('versionsOfLayouts', $versionsOfLayouts)
                ->set('layouts', $layoutsDir);
        }
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            try {
                $layoutFilename = $this->getRequest()->getParam('key').'-v'.$this->getRequest()->getParam('newVersion');

                $transfer = new Transfer();
                $transfer->setZipSavePath(ROOT_PATH.'/updates/');
                $transfer->setDownloadUrl($this->getConfig()->get('updateserver').'layouts/'.$layoutFilename.'.zip');
                $transfer->setDownloadSignatureUrl($this->getConfig()->get('updateserver').'layouts/'.$layoutFilename.'.zip-signature.sig');

                if (!$transfer->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                    // Certificate is missing or expired.
                    $this->addMessage('certMissingOrExpired', 'danger');
                    return;
                }

                if (!$transfer->save()) {
                    $this->addMessage('layoutVerificationFailed', 'danger');
                    return;
                }
                
                if (!$transfer->update($this->getRequest()->getParam('version'))) {
                    $this->addMessage('layoutUpdateFailed', 'danger');
                    return;
                }

                $this->addMessage('updateSuccess');
            } finally {
                $this->redirect(['action' => $this->getRequest()->getParam('from')]);
            }
        }
    }

    public function showAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search'])
            ->add($this->getTranslator()->trans('menuLayout').' '.$this->getTranslator()->trans('info'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        $layoutsDir = [];
        foreach (glob(ROOT_PATH.'/application/layouts/*') as $layoutPath) {
            if (is_dir($layoutPath)) {
                $layoutsDir[] = basename($layoutPath);
            }
        }

        $this->getView()->set('updateserver', $this->getConfig()->get('updateserver'))
            ->set('layouts', $layoutsDir);
    }

    public function settingsAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'settings']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('favicon', $this->getRequest()->getPost('favicon'))
                ->set('apple_icon', $this->getRequest()->getPost('appleIcon'))
                ->set('page_title', $this->getRequest()->getPost('pageTitle'))
                ->set('keywords', $this->getRequest()->getPost('keywords'))
                ->set('description', $this->getRequest()->getPost('description'));

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('favicon', $this->getConfig()->get('favicon'))
            ->set('appleIcon', $this->getConfig()->get('apple_icon'))
            ->set('pageTitle', $this->getConfig()->get('page_title'))
            ->set('keywords', $this->getConfig()->get('keywords'))
            ->set('description', $this->getConfig()->get('description'));
    }

    public function advSettingsAction()
    {
        $layoutAdvSettingsMapper = new LayoutAdvSettingsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'settings'])
            ->add($this->getTranslator()->trans('menuAdvSettings'), ['action' => 'advSettings']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_layouts')) {
            foreach ($this->getRequest()->getPost('check_layouts') as $advSettingsLayoutKey) {
                $layoutAdvSettingsMapper->deleteSettings($advSettingsLayoutKey);
            }
        }

        $layouts = [];
        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            if (is_dir($layoutPath)) {
                $key = basename($layoutPath);
                $configClass = '\\Layouts\\'.ucfirst($key).'\\Config\\Config';
                $config = new $configClass($this->getTranslator());
                if (empty($config->config['modulekey']) && empty($config->config['settings'])) {
                    continue;
                }
                $model = new LayoutModel();
                $model->setKey($key);
                $model->setName($config->config['name']);
                $model->setAuthor($config->config['author']);
                if (!empty($config->config['modulekey'])) {
                    $model->setModulekey($config->config['modulekey']);
                }
                $layouts[$key] = $model;
            }
        }

        $layoutKeys = $layoutAdvSettingsMapper->getListOfLayoutKeys();
        $orphanedSettings = [];
        foreach ($layoutKeys as $layoutKey) {
            if (!isset($layouts[$layoutKey])) {
                $orphanedSettings[] = $layoutKey;
            }
        }

        if ($this->getRequest()->getPost('deleteOrphanedSettings')) {
            foreach($orphanedSettings as $layoutKey) {
                $layoutAdvSettingsMapper->deleteSettings($layoutKey);
            }
            $orphanedSettings = [];
        }
        if (!empty($orphanedSettings)) {
            $this->addMessage('orphanedSettings', 'info');
        }

        $this->getView()->set('orphanedSettingsExist', (!empty($orphanedSettings)));
        $this->getView()->set('layouts', $layouts);
    }

    public function advSettingsShowAction()
    {
        $layoutKey = $this->getRequest()->getParam('layoutKey');

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'settings'])
            ->add($this->getTranslator()->trans('menuAdvSettings'), ['action' => 'advSettings'])
            ->add($this->getTranslator()->trans('menuAdvSettingsShow'), ['action' => 'advSettings', 'layoutKey' => $layoutKey]);

        $settings = [];
        $layoutPath = APPLICATION_PATH.'/layouts/'.$layoutKey;
        if (is_dir($layoutPath)) {
            $configClass = '\\Layouts\\'.ucfirst(basename($layoutPath)).'\\Config\\Config';
            $config = new $configClass($this->getTranslator());
            if (!empty($config->config['settings'])) {
                $settings = $config->config['settings'];
            }

            $this->getLayout()->getTranslator()->load($layoutPath.'/translations/');
        }

        $layoutAdvSettingsMapper = new LayoutAdvSettingsMapper();

        if ($this->getRequest()->isPost()) {
            $postedSettings = [];
            foreach($this->getRequest()->getPost() as $key => $value) {
                $layoutAdvSettingsModel = new LayoutAdvSettingsModel();
                $layoutAdvSettingsModel->setLayoutKey($layoutKey);
                $layoutAdvSettingsModel->setKey($key);
                $layoutAdvSettingsModel->setValue($value);
                $postedSettings[] = $layoutAdvSettingsModel;
            }

            $layoutAdvSettingsMapper->save($postedSettings);

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'advSettingsShow', 'layoutKey' => $layoutKey]);
        }

        $settingsValues = null;

        if (empty($settings)) {
            $layoutAdvSettingsMapper->deleteSettings($layoutKey);
        } else {
            $settingsValues = $layoutAdvSettingsMapper->getSettings($layoutKey);
        }

        $this->getView()->set('layoutKey', $layoutKey);
        $this->getView()->set('settings', $settings);
        $this->getView()->set('settingsValues', $settingsValues);
    }

    public function deleteAdvSettingsAction()
    {
        if ($this->getRequest()->isSecure()) {
            $layoutAdvSettingsMapper = new LayoutAdvSettingsMapper();
            $layoutAdvSettingsMapper->deleteSettings($this->getRequest()->getParam('layoutKey'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'advSettings']);
        }
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            if ($this->getConfig()->get('default_layout') === $this->getRequest()->getParam('key')) {
                $this->addMessage('cantDeleteDefaultLayout', 'info');
            } else {
                $configClass = '\\Layouts\\'.ucfirst($this->getRequest()->getParam('key')).'\\Config\\Config';
                $config = new $configClass();

                if (method_exists($config, 'uninstall')) {
                    $config->uninstall();
                }
                removeDir(APPLICATION_PATH.'/layouts/'.$this->getRequest()->getParam('key'));

                // Call uninstall() of module related to the layout if it is installed. Delete folder of module.
                if (is_dir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'))) {
                    $modules = new ModuleMapper();

                    $isInstalled = $modules->getModuleByKey($this->getRequest()->getParam('key'));
                    if ($isInstalled) {
                        $configClass = '\\Modules\\'.ucfirst($this->getRequest()->getParam('key')).'\\Config\\Config';
                        $config = new $configClass();
                        $config->uninstall();
                        $modules->delete($this->getRequest()->getParam('key'));
                    }

                    removeDir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'));
                }

                // Delete advanced layout settings
                $layoutAdvSettingsMapper = new LayoutAdvSettingsMapper();
                $layoutAdvSettingsMapper->deleteSettings($this->getRequest()->getParam('key'));

                $this->addMessage('deleteSuccess');
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function refreshURLAction()
    {
        if (!empty(url_get_contents($this->getConfig()->get('updateserver').'layouts2.php', true, true))) {
            $this->redirect()
                ->withMessage('updateSuccess')
                ->to(['action' => $this->getRequest()->getParam('from')]);
        }

        $this->redirect()
            ->withMessage('lastUpdateError', 'danger')
            ->to(['action' => $this->getRequest()->getParam('from')]);
    }
}
