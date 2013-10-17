<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
use Admin\Mappers\Menu as MenuMapper;
defined('ACCESS') or die('no direct access');

class Menu extends \Ilch\Controller\Admin
{
	public function indexAction()
	{
		$menuMapper = new MenuMapper();
		$this->getView()->set('menus', $menuMapper->getMenus());
		
		if($this->getRequest()->isPost())
		{
			var_dump($this->getRequest()->getPost('hiddenMenu'));
		}
	}
}