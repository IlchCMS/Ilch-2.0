<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Module as ModuleMapper;

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
        $modules = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuInstalled'), ['action' => 'index']);

        $this->getView()->set('modules', $modules->getModules());
    }

    public function notinstalledAction()
    {
        $modules = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuNotInstalled'), ['action' => 'notinstalled']);

        $this->getView()->set('modulesNotInstalled', $modules->getModulesNotInstalled());
    }

    public function searchAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search']);

        if ($this->getRequest()->isSecure()) {
            $transfer = new \Ilch\Transfer();
            $transfer->setZipSavePath(ROOT_PATH.'/updates/');
            $transfer->setDownloadUrl($this->getRequest()->getPost('url'));
            $transfer->setDownloadSignatureUrl($this->getRequest()->getPost('url').'-signature.sig');

            if (!$transfer->validateCert(ROOT_PATH.'/certificate/Certificate.crt')) {
                // Certificate is missing or expired.
                $this->addMessage('certMissingOrExpired', 'danger');
                return;
            }

            $transfer->save();

            $signature = file_get_contents($transfer->getZipFile().'-signature.sig');
            $pubKeyfile = ROOT_PATH.'/certificate/Certificate.crt';
            if (!$transfer->verifyFile($pubKeyfile, $transfer->getZipFile(), $signature)) {
                // Verification failed. Drop the potentially bad files.
                unlink($transfer->getZipFile());
                unlink($transfer->getZipFile().'-signature.sig');
                $this->addMessage('moduleVerificationFailed', 'danger');
                return;
            }

            $transfer->install();
            $this->addMessage('downSuccess');
        }

        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $modulesDir[] = basename($modulesPath);
        }

        $this->getView()->set('modules', $modulesDir);
    }

    public function showAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search'])
                ->add($this->getTranslator()->trans('menuModules').' '.$this->getTranslator()->trans('info'), ['action' => 'show']);

        foreach (glob(ROOT_PATH.'/application/modules/*') as $modulesPath) {
            $modulesDir[] = basename($modulesPath);
        }

        $this->getView()->set('modules', $modulesDir);
    }

    public function installAction()
    {
        $moduleMapper = new ModuleMapper();
        $key = $this->getRequest()->getParam('key');

        if ($this->getRequest()->isSecure()) {
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\config';
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

            $this->addMessage('installSuccess');
        }

        $this->redirect(['action' => 'notinstalled']);
    }

    public function uninstallAction()
    {
        $modules = new ModuleMapper();
        $key = $this->getRequest()->getParam('key');

        if ($this->getRequest()->isSecure()) {
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\config';
            $config = new $configClass($this->getTranslator());
            $config->uninstall();
            $modules->delete($key);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function deleteAction()
    {
        $modules = new ModuleMapper();

        if ($this->getRequest()->isSecure()) {
            removeDir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'notinstalled']);
    }
}
