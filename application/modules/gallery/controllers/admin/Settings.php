<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Controllers\Admin;

use Modules\Gallery\Controllers\Admin\Base as BaseController;

class Settings extends BaseController
{
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('gallery'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('gallery_picturesPerPage', $this->getRequest()->getPost('picturesPerPage'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('picturesPerPage', $this->getConfig()->get('gallery_picturesPerPage'));
    }
}
