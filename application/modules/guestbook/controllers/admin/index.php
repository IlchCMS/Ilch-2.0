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
        $entries = $guestbookMapper->getEntries();
        $this->getView()->set('entries', $entries);
    }

    public function delAction() 
    {
        $id = $this->getRequest()->getParam('id');
        $GuestbookMapper = new GuestbookMapper();
        $GuestbookMapper->delEntry($id);
        $this->addMessage('Successful');
        $this->redirect(array('action' => 'show'));
    }

}
