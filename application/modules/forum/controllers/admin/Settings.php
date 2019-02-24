<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\User\Mappers\Group as GroupMapper;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => false,
                'icon' => 'fa fa-th',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'forum',
            $items
        );
    }

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
            $this->getConfig()->set('forum_boxForumLimit', $this->getRequest()->getPost('boxForumLimit'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('threadsPerPage', $this->getConfig()->get('forum_threadsPerPage'));
        $this->getView()->set('postsPerPage', $this->getConfig()->get('forum_postsPerPage'));
        $this->getView()->set('floodInterval', $this->getConfig()->get('forum_floodInterval'));
        $this->getView()->set('excludeFloodProtection', explode(',', $this->getConfig()->get('forum_excludeFloodProtection')));
        $this->getView()->set('postVoting', $this->getConfig()->get('forum_postVoting'));
        $this->getView()->set('boxForumLimit', $this->getConfig()->get('forum_boxForumLimit'));
        $this->getView()->set('groupList', $groupMapper->getGroupList());
    }
}
