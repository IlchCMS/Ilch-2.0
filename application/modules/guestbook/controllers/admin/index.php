<?php
/**
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Controllers\Admin;

use Guestbook\Models\Guestbook as GuestbookModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin 
{
	public function indexAction() 
	{
    }
	
	public function showAction() 
	{
        $this->model = new GuestbookModel();
        $entries = $this->model->getEntries();
        $this->getView()->set('entries', $entries);   
    }
	
	public function delAction() 
	{
		$id = $this->getRequest()->getParam('id');
		$this->model = new GuestbookModel();
        $this->model->delEntry($id);
		$this->addMessage('');
	    $this->redirect(array('action' =>  'show'));
	}
}
