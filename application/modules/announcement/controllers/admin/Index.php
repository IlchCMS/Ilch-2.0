<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Announcement\Controllers\Admin;

use Modules\Announcement\Mappers\Announcement as AnnouncementMapper;
use Modules\Announcement\Models\Announcement as AnnouncementModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'Announcements',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'add'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'add') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'Announcements',
            $items
        );
    }

    public function indexAction()
    {
        $mapper = new AnnouncementMapper();

        $this->getView()->set('announcements', $mapper->getAllAnnouncements());
    }

    public function addAction()
    {
        $content = $this->getRequest()->getPost('content');

        $mapper = new AnnouncementMapper();

        if($content != "")
        {
            $mapper->createAnnouncement($content);
            $this->redirectToIndex();
        }

    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');

        $mapper = new AnnouncementMapper();

        $mapper->deleteAnnouncement($id);

        $this->redirectToIndex("deleteSuccess");
    }

    public function activateAction()
    {
        $id = $this->getRequest()->getParam('id');

        $mapper = new AnnouncementMapper();

        $mapper->activateAnnouncement($id);

        $this->redirectToIndex();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $mapper = new AnnouncementMapper();

        if($this->getRequest()->getPost('save') == 'save')
        {
            $mapper->editAnnouncement($id, $this->getRequest()->getPost('content'));

            $this->redirectToIndex();
            return;
        }

        $this->getView()->set('announcement', $mapper->getAnnouncementByID((int) $id));
    }

    private function redirectToIndex($msg = "saveSuccess")
    {

        $this->redirect()
                    ->withMessage($msg)  //deleteSuccess | saveSuccess
                    ->to(['controller' => 'index', 'action' => 'index']);
    }
}