<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Controllers\Admin;

use Modules\Guestbook\Mappers\Guestbook as GuestbookMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'guestbook',
            array
            (
                array
                (
                    'name' => 'Verwalten',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $guestbookMapper = new GuestbookMapper();
        
        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_entries') as $entryId) {
                    $guestbookMapper->delete($entryId);
                }
                
                $this->redirect(array('action' => 'index'));
            }

            if ($this->getRequest()->getPost('action') == 'setfree') {
                foreach($this->getRequest()->getPost('check_entries') as $entryId) {
                    $model = new \Modules\Guestbook\Models\Entry();
                    $model->setId($entryId);
                    $model->setFree(1);
                    $guestbookMapper->save($model);
                }

                $this->redirect(array('action' => 'index'));
            }
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $entries = $guestbookMapper->getEntries(array('setfree' => 0));
        } else {
            $entries = $guestbookMapper->getEntries(array('setfree' => 1));
        }

        $this->getView()->set('entries', $entries);
        $this->getView()->set('badge', count($guestbookMapper->getEntries(array('setfree' => 0))));
    }

    public function delAction()
    {
        $guestbookMapper = new GuestbookMapper();
        
        if($this->getRequest()->isSecure()) {
            $guestbookMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(array('action' => 'index', 'showsetfree' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    public function setfreeAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $model = new \Modules\Guestbook\Models\Entry();
        $model->setId($this->getRequest()->getParam('id'));
        $model->setFree(1);
        $guestbookMapper->save($model);

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(array('action' => 'index', 'showsetfree' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }
}
