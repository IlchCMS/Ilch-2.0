<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'reset',
                'active' => false,
                'icon' => 'fa-solid fa-trash-can',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() != 'reset') {
            $items[0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuShoutbox',
            $items
        );
    }

    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShoutbox'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_entries')) {
            foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                $shoutboxMapper->delete($entryId);
            }
        }

        $pagination->setRowsPerPage($this->getConfig()->get('shoutbox_messagesPerPageAdmincenter') ?: $this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $shoutboxEntries = $shoutboxMapper->getEntriesBy([], ['id' => 'DESC'], $pagination);
        $userNames = [];

        foreach ($shoutboxMapper->getUsersOfEntries($shoutboxEntries) as $userId => $user) {
            $userNames[$userId] = $user->getName();
        }

        $this->getView()->set('dummyUserName', $userMapper->getDummyUser()->getName());
        $this->getView()->set('userNames', $userNames);
        $this->getView()->set('shoutbox', $shoutboxEntries);
        $this->getView()->set('pagination', $pagination);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $shoutboxMapper = new ShoutboxMapper();
            $shoutboxMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function resetAction()
    {
        if ($this->getRequest()->isSecure()) {
            $shoutboxMapper = new ShoutboxMapper();
            $shoutboxMapper->truncate();

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }
}
