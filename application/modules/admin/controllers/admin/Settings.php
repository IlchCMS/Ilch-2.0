<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('locale', $this->getRequest()->getPost('language'));
            $this->getTranslator()->setLocale($this->getRequest()->getPost('language'));
            $this->getConfig()->set('maintenance_mode', $this->getRequest()->getPost('maintenanceMode'));
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('maintenanceMode', $this->getConfig()->get('maintenance_mode'));
    }
}
