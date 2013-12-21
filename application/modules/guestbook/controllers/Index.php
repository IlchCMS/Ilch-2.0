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
        $this->getView()->set('entries', $guestbookMapper->getEntries());
    }

    public function newEntryAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $ilchdate = new IlchDate;

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $email = trim($this->getRequest()->getPost('email'));
            $text = trim($this->getRequest()->getPost('text'));
            $homepage = trim($this->getRequest()->getPost('homepage'));

            if (empty($text)) {
                $this->addMessage('missingText', 'error');
            } elseif(empty($name)) {
                $this->addMessage('missingName', 'error');
            } else {
                $entryDatas = array
                (
                    'name' => $name,
                    'email' => $email,
                    'text' => $text,
                    'homepage' => $homepage,
                    'datetime' => $ilchdate->toDb()
                );

                $guestbookMapper->saveEntry($entryDatas);
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}
