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
        $items =
            [
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
                'name' => 'menuSearchModules',
                'active' => false,
                'icon' => 'fa fa-folder-open',
                'url' => $this->getLayout()->getUrl(['controller' => 'modules', 'action' => 'searchmodules'])
            ],
            ];

        if ($this->getRequest()->getActionName() == 'notinstalled') {
            $items[1]['active'] = true; 
        } else if ($this->getRequest()->getActionName() == 'searchmodules') {
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

        $this->getView()->set('modulesNotInstalled', $modules->getModulesNotInstalled($this->getTranslator()));
    }

    public function searchmodulesAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearchModules'), ['action' => 'searchmodules']);

        if ($this->getRequest()->isPost('layout')) {
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
            $this->addMessage('Success');
        }
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

    public function deleteAction()
    {
        $modules = new ModuleMapper();
        $key = $this->getRequest()->getParam('key');

        if ($this->getRequest()->isSecure()) {
            $configClass = '\\Modules\\'.ucfirst($key).'\\Config\\config';
            $config = new $configClass($this->getTranslator());
            $config->uninstall();
            $modules->delete($key);
            removeDir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
