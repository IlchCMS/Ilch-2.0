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
    public function indexAction() 
    {
    }

    public function showAction() 
    {
        $guestbookMapper = new GuestbookMapper();
        $this->getView()->set('entries', $guestbookMapper->getEntries());
    }

    public function delAction() 
    {
        $guestbookMapper = new GuestbookMapper();
        $id = $this->getRequest()->getParam('id');
        $guestbookMapper->deleteEntry($id);
        $this->addMessage('successful');
        $this->redirect(array('action' => 'show'));
    }
}

