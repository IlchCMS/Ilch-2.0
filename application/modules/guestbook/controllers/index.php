<?php
/**
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Controllers;

use Guestbook\Models\Guestbook as GuestbookModels;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend 
{
	public function indexAction() 
	{
        $this->model = new GuestbookModels();
        $entries = $this->model->getEntries();
		$this->getView()->set('entries', $entries);
    }

    public function newEntryAction() 
	{
        if (isset($_POST['saveEntry'])) 
		{
            if (empty($_POST['text']) or empty($_POST['email']) or empty($_POST['homepage']) or empty($_POST['name'])) 
			{
                $this->redirect(array('action' => 'error'));
            } else {
				$entryDatas = array
				(
					'name' => trim($this->getRequest()->getPost('name')),
                    'email' => trim($this->getRequest()->getPost('email')),
                    'text' => trim($this->getRequest()->getPost('text')),
                    'homepage' => trim($this->getRequest()->getPost('homepage')),
                    'datetime' => date('Y-m-d H:i:s')
                );
                $this->model = new GuestbookModels();
                $this->model->saveEntry($entryDatas);
                $this->redirect(array('action' => 'index'));
            }
        }
	}
    
    public function errorAction() 
	{
		$this->getView()->set('info', 'nicht alle Felder ausgef&uuml;llt');
    }
}
