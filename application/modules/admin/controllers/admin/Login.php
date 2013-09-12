<?php
/**
 * Holds class Admin_LoginController.
 *
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Handles the login functionality.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
 * @package ilch
 */
class Admin_Admin_LoginController extends Ilch_Controller_Admin
{
	/**
	 * Sets the layout file for this controller.
	 */
	public function init()
	{
		$this->getLayout()->setFile('admin/login');
	}

	/**
	 * Shows the standard login page.
	 * %akes the request data for the login and tries to login the user.
	 */
	public function indexAction()
	{
		$errors = array();

		if($this->getRequest()->isPost())
		{
			if(Ilch_Registry::get('user'))
			{
				$errors['alreadyLoggedIn'] = 'alreadyLoggedIn';
			}

			$email = $this->getRequest()->getPost('email');

			if($email === '')
			{
				$errors['noEmailGiven']  = 'noEmailGiven';
			}
			else
			{
				$mapper = new User_UserMapper();
				$user = $mapper->getUserByEmail($email);

				if($user == null || $user->getPassword() !== crypt($this->getRequest()->getPost('password'), $user->getPassword()))
				{
					$errors['userNotFound'] = 'userNotFound';
				}
				else
				{
					/*
					 * A use was found. Set his id in the session and redirect to the admincenter.
					 */
					$_SESSION['user_id'] = $user->getId();
					$this->redirect(array('controller' => 'index', 'action' => 'index'));
				}
			}

			$this->getLayout()->set('email', $email);
		}

		$this->getLayout()->set('errors', $errors);
	}
	
	/**
	 * Does the logout for a user.
	 */
	public function logoutAction()
	{
		session_destroy();
		unset($_SESSION);
		Ilch_Registry::remove('user');

		/*
		 * @todo flash message helper for show logout message on next site. 
		 */
		$this->redirect(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'));
	}
}