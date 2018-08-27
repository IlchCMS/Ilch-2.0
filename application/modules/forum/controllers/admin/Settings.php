<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\Forum\Controllers\Admin\Base as BaseController;
use Modules\User\Mappers\Group as GroupMapper;

class Settings extends BaseController
{
    public function indexAction()
    {
        $groupMapper = new GroupMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('forum_threadsPerPage', $this->getRequest()->getPost('threadsPerPage'));
            $this->getConfig()->set('forum_postsPerPage', $this->getRequest()->getPost('postsPerPage'));
            $this->getConfig()->set('forum_floodInterval', $this->getRequest()->getPost('floodInterval'));
            $this->getConfig()->set('forum_excludeFloodProtection', implode(',', ($this->getRequest()->getPost('groups')) ? $this->getRequest()->getPost('groups') : []));
            $this->getConfig()->set('forum_postVoting', $this->getRequest()->getPost('postVoting'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('threadsPerPage', $this->getConfig()->get('forum_threadsPerPage'));
        $this->getView()->set('postsPerPage', $this->getConfig()->get('forum_postsPerPage'));
        $this->getView()->set('floodInterval', $this->getConfig()->get('forum_floodInterval'));
        $this->getView()->set('excludeFloodProtection', explode(',', $this->getConfig()->get('forum_excludeFloodProtection')));
        $this->getView()->set('postVoting', $this->getConfig()->get('forum_postVoting'));
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }
}
