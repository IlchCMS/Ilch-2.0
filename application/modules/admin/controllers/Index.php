<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Admin_IndexController extends Ilch_Controller
{
	public function init()
	{
		$menu = array();
		$this->getLayout()->set('menu', $menu);
	}

	public function indexAction()
	{
	}
}