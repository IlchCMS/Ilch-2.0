<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Controllers\Admin;

use Guestbook\Mappers\Guestbook as GuestbookMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'Guestbook',
            array
            (
                array
                (
                    'name' => 'Verwalten',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->url(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $guestbookMapper = new GuestbookMapper();

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
        $guestbookMapper->delete($this->getRequest()->getParam('id'));
        $this->addMessage('deleteSuccess');
        
        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(array('action' => 'index', 'showsetfree' => 1));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    public function setfreeAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $model = new \Guestbook\Models\Entry();
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
