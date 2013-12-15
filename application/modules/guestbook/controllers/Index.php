<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Controllers;

use Guestbook\Mappers\Guestbook as GuestbookMapper;
use Ilch\Date as IlchDate;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend 
{
    public function indexAction() 
    {
        $guestbookMapper = new GuestbookMapper();
        $entries = $guestbookMapper->getEntries();
        $this->getView()->set('entries', $entries);
    }

    public function newEntryAction() 
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['saveEntry'])) 
        {
            if (empty($postData['text'])) 
            {
                $this->redirect(array('action' => 'error'));
            } else {
                $ilchdate = new IlchDate;
                $date = $ilchdate->toDb();
                $entryDatas = array
                (
                    'name' => trim($this->getRequest()->getPost('name')),
                    'email' => trim($this->getRequest()->getPost('email')),
                    'text' => trim($this->getRequest()->getPost('text')),
                    'homepage' => trim($this->getRequest()->getPost('homepage')),
                    'datetime' => $date
                );
                $GuestbookMapper = new GuestbookMapper();
                $GuestbookMapper->saveEntry($entryDatas);
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function errorAction() 
    {
        $this->getView()->set('info', 'nicht alle Felder ausgef&uuml;llt');
    }

}
