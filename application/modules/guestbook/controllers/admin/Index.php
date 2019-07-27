<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Controllers\Admin;

use Modules\Guestbook\Mappers\Guestbook as GuestbookMapper;
use Modules\Guestbook\Models\Entry as GuestbookModel;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'guestbook',
            $items
        );
    }

    public function indexAction()
    {
        $guestbookMapper = new GuestbookMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('guestbook'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);
        
        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                    $guestbookMapper->delete($entryId);
                }

                $this->redirect(['action' => 'index']);
            }

            if ($this->getRequest()->getPost('action') == 'setfree') {
                foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                    $model = new GuestbookModel();
                    $model->setId($entryId);
                    $model->setFree(1);
                    $guestbookMapper->save($model);
                }

                $this->redirect(['action' => 'index']);
            }
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $entries = $guestbookMapper->getEntries(['setfree' => 0]);
        } else {
            $entries = $guestbookMapper->getEntries(['setfree' => 1]);
        }

        $countAwaitingApproval = count($guestbookMapper->getEntries(['setfree' => 0]));

        if ($countAwaitingApproval == 0) {
            $notificationsMapper = new NotificationsMapper();
            $notificationsMapper->deleteNotificationsByType('guestbookEntryAwaitingApproval');
        }

        $this->getView()->set('entries', $entries);
        $this->getView()->set('badge', $countAwaitingApproval);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $guestbookMapper = new GuestbookMapper();

            $guestbookMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(['action' => 'index', 'showsetfree' => 1]);
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    public function setfreeAction()
    {
        if ($this->getRequest()->isSecure()) {
            $guestbookMapper = new GuestbookMapper();

            $model = new GuestbookModel();
            $model->setId($this->getRequest()->getParam('id'));
            $model->setFree(1);
            $guestbookMapper->save($model);
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(['action' => 'index', 'showsetfree' => 1]);
        } else {
            $this->redirect(['action' => 'index']);
        }
    }
}
