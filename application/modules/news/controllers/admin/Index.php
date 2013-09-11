<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class News_Admin_IndexController extends Ilch_Controller_Admin
{
	public function indexAction()
	{
		var_dump('Admin News Modul erfolgreich geladen');
	}
	
	public function testAction()
	{
		var_dump('bin nur ein test');
	}
}