<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

class Modules extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('modules'), array('action' => 'index'));

        $modules = new \Modules\Admin\Mappers\Module();
        $this->getView()->set('modules', $modules->getModules());
    }

    public function deleteAction()
    {
        $modules = new \Modules\Admin\Mappers\Module();
        $key = $this->getRequest()->getParam('key');

        if($this->getRequest()->isSecure()) {
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
