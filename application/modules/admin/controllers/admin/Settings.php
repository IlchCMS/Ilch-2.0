<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Admin_Admin_SettingsController extends Ilch_Controller_Admin
{
	public function indexAction()
	{
		$this->getView()->set('languages', $this->getTranslator()->getLocaleList());
		
		if($this->getRequest()->isPost())
		{
			$this->getConfig()->set('locale', $this->getRequest()->getPost('language'));
			$this->redirect(array('action' => 'index'));
		}
	}
}