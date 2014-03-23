<?php
/**
 * @package ilch
 */

namespace Admin\Controllers\Admin;
defined('ACCESS') or die('no direct access');

class Modules extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        $modules = new \Admin\Mappers\Module();
        $this->getView()->set('modules', $modules->getModules());
    }

    public function deleteAction()
    {
        $modules = new \Admin\Mappers\Module();
        $key = $this->getRequest()->getParam('key');

        if($this->getRequest()->isSecure()) {
            $configClass = '\\'.ucfirst($key).'\\Config\\config';
            $config = new $configClass($this->getTranslator());
            $config->uninstall();
            $modules->delete($key);
            removeDir(APPLICATION_PATH.'/modules/'.$this->getRequest()->getParam('key'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
