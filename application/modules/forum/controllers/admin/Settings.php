<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\Forum\Controllers\Admin\Base as BaseController;

class Settings extends BaseController
{
    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('forum_threadsPerPage', $this->getRequest()->getPost('threadsPerPage'));
            $this->getConfig()->set('forum_postsPerPage', $this->getRequest()->getPost('postsPerPage'));
            $this->getConfig()->set('forum_postVoting', $this->getRequest()->getPost('postVoting'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('threadsPerPage', $this->getConfig()->get('forum_threadsPerPage'));
        $this->getView()->set('postsPerPage', $this->getConfig()->get('forum_postsPerPage'));
        $this->getView()->set('postVoting', $this->getConfig()->get('forum_postVoting'));
    }
}
