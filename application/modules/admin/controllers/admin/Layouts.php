<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Models\Layout as LayoutModel;
use Ilch\Transfer as Transfer;

class Layouts extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'index'])
            ],
            [
                'name' => 'menuSearch',
                'active' => false,
                'icon' => 'fa fa-search',
                'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'search'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'layouts', 'action' => 'settings'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'index') {
            $items[0]['active'] = true;
        } elseif ($this->getRequest()->getActionName() == 'search' OR $this->getRequest()->getActionName() == 'show') {
            $items[1]['active'] = true;
        } elseif ($this->getRequest()->getActionName() == 'settings') {
            $items[2]['active'] = true;
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
        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            $model = new LayoutModel();
            $model->setKey(basename($layoutPath));
            include_once $layoutPath.'/config/config.php';
            $model->setName($config['name']);
            $model->setVersion($config['version']);
            $model->setAuthor($config['author']);
            if (!empty($config['link'])) {
                $model->setLink($config['link']);
            }
            $model->setDesc($config['desc']);
            if (!empty($config['modulekey'])) {
                $model->setModulekey($config['modulekey']);
            }
            $layouts[] = $model;
        }

        $this->getView()->set('defaultLayout', $this->getConfig()->get('default_layout'));
        $this->getView()->set('layouts', $layouts);
    }

    public function defaultAction()
    {
        $this->getConfig()->set('default_layout', $this->getRequest()->getParam('key'));
        $this->redirect(['action' => 'index']);
    }

    public function searchAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search']);

        if ($this->getRequest()->isSecure()) {
            $transfer = new Transfer();
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
                $this->addMessage('layoutVerificationFailed', 'danger');
                return;
            }

            $transfer->install();
            $this->addMessage('downSuccess');
        }

        foreach (glob(ROOT_PATH.'/application/layouts/*') as $layoutPath) {
            $layoutsDir[] = basename($layoutPath);
        }

        $this->getView()->set('layouts', $layoutsDir);
    }

    public function showAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLayouts'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'search'])
                ->add($this->getTranslator()->trans('menuLayout').' '.$this->getTranslator()->trans('info'), ['action' => 'show']);

        foreach (glob(ROOT_PATH.'/application/layouts/*') as $layoutPath) {
            $layoutsDir[] = basename($layoutPath);
        }

        $this->getView()->set('layouts', $layoutsDir);
    }

    public function settingsAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('favicon', $this->getRequest()->getPost('favicon'));
            $this->getConfig()->set('apple_icon', $this->getRequest()->getPost('appleIcon'));

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('favicon', $this->getConfig()->get('favicon'));
        $this->getView()->set('appleIcon', $this->getConfig()->get('apple_icon'));
    }

    public function deleteAction()
    {
        if ($this->getConfig()->get('default_layout') == $this->getRequest()->getParam('key')) {
            $this->addMessage('cantDeleteDefaultLayout', 'info');
        } else {
            removeDir(APPLICATION_PATH.'/layouts/'.$this->getRequest()->getParam('key'));
            unlink(ROOT_PATH.'/updates/'.$this->getRequest()->getParam('key').'.zip-signature.sig');

            if (is_dir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'))) {
                $modules = new ModuleMapper();

                $configClass = '\\Modules\\'.ucfirst($this->getRequest()->getParam('key')).'\\Config\\config';
                $config = new $configClass();
                $config->uninstall();
                $modules->delete($this->getRequest()->getParam('key'));

                removeDir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'));
            }

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
