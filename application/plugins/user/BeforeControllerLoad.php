<?php
/**
 * Holds class User_BeforeControllerLoadPlugin.
 *
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Does user operations before the controller loads.
 *
 * @author  Martin Jainta
 * @copyright Ilch CMS 2.0
 * @package ilch
 */
class User_BeforeControllerLoadPlugin
{
	/**
	 * Checks if a user id was given in the request and sets the user.
	 *
	 * If no user id is given a default user will be created.
	 */
	public function __construct()
	{
		$userId = null;

		if(isset($_SESSION['user_id']))
		{
			$userId = (int)$_SESSION['user_id'];
		}

		$mapper = new User_UserMapper();
		$user = $mapper->getUserById($userId);

		if($user === null)
		{
			$user = new User_UserModel();
			$user->setId(0);
			$user->setName('Guest');
		}

		Ilch_Registry::set('user', $user);
	}
}