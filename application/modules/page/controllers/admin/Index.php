<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Page_Admin_IndexController extends Ilch_Controller_Admin
{
	public function init()
	{
		$this->getLayout()->addMenu
		(
			'menuSite',
			array
			(
				array
				(
					'name' => 'menuSites',
					'active' => true,
					'icon' => 'icon-th-list',
					'url' => $this->getLayout()->url(array('controller' => 'index'))
				),
			)
		);
	}

	public function indexAction()
	{
		$this->getLayout()->addMenuAction
		(
			array
			(
				'name' => 'menuActionNewSite',
				'icon' => 'icon-plus-sign',
				'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'add'))
			)
		);
	}
}