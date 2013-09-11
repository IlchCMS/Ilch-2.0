<?php
/**
 * Holds class Admin_LogoutController.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Handles the logout of a user.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
 * @package ilch
 */
class Admin_LogoutController extends Ilch_Controller
{
	public function init()
	{
		$this->getLayout()->setFile('admin/logout');
	}

	public function indexAction()
	{

	}

	/**
	 * Does the logout for a user.
	 */
	public function logoutAction()
	{
		if(isset($_SESSION['user_id']))
		{
			unset($_SESSION['user_id']);
			Ilch_Registry::remove('user');
		}
	}
}