<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Controllers\Admin;

use Guestbook\Mappers\Guestbook as GuestbookMapper;
use Guestbook\Mappers\Settings as SettingsMapper;

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
                    'name' => 'Settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->url(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }
    
    public function indexAction()
    {
    }
    
    public function showAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $this->getView()->set('entries', $guestbookMapper->getEntries());
    }
    
    public function shownewAction()
    {
        $settingsMapper = new SettingsMapper();
        $this->getView()->set('entries', $settingsMapper->getNewEntries());
    }

    public function delAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $id = $this->getRequest()->getParam('id');
        $guestbookMapper->deleteEntry($id);
        $this->addMessage('successful');
        $this->redirect(array('action' => 'show'));
    }
        
    public function delspamAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $id = $this->getRequest()->getParam('id');
        $guestbookMapper->deleteEntry($id);
        $this->addMessage('successful');
        $this->redirect(array('action' => 'shownew'));
    }
    
    public function setfreeAction()
    {
        $id = $this->getRequest()->getParam('id');
        $settingsMapper = new SettingsMapper();
        
        $fild = array
        (
            'setfree' => 'setfree'
        ); 
        $where = array
        (
            'id' => $id
        );
        
        $settingsMapper->saveSetfree($fild, $where);
        $this->redirect(array('action' => 'shownew'));
    }
}

