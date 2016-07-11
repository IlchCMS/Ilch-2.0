<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Controllers\Admin\Base as BaseController;

class Settings extends BaseController
{
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('war_warsPerPage', $this->getRequest()->getPost('warsPerPage'));
            $this->getConfig()->set('war_enemiesPerPage', $this->getRequest()->getPost('enemiesPerPage'));
            $this->getConfig()->set('war_groupsPerPage', $this->getRequest()->getPost('groupsPerPage'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('warsPerPage', $this->getConfig()->get('war_warsPerPage'));
        $this->getView()->set('enemiesPerPage', $this->getConfig()->get('war_enemiesPerPage'));
        $this->getView()->set('groupsPerPage', $this->getConfig()->get('war_groupsPerPage'));
    }
}
