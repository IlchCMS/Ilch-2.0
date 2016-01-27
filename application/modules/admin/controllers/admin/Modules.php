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
        $items = array
        (
            array
            (
                'name' => 'menuInstalled',
                'active' => false,
                'icon' => 'fa fa-folder',
                'url' => $this->getLayout()->getUrl(array('controller' => 'modules', 'action' => 'index'))
            ),
            array
            (
                'name' => 'menuNotInstalled',
                'active' => false,
                'icon' => 'fa fa-folder-open',
                'url' => $this->getLayout()->getUrl(array('controller' => 'modules', 'action' => 'notinstalled'))
            ),
        );

        if ($this->getRequest()->getActionName() == 'notinstalled') {
            $items[1]['active'] = true; 
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
                ->add($this->getTranslator()->trans('menuModules'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuInstalled'), array('action' => 'index'));

        $this->getView()->set('modules', $modules->getModules());
    }

    public function notinstalledAction()
    {
        $modules = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuModules'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuNotInstalled'), array('action' => 'notinstalled'));

        $this->getView()->set('modulesNotInstalled', $modules->getModulesNotInstalled($this->getTranslator()));
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

        $this->redirect(array('action' => 'notinstalled'));
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

        $this->redirect(array('action' => 'index'));
    }
}
