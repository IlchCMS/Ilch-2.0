<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
use Admin\Mappers\Menu as MenuMapper;
use Admin\Models\Menu as MenuModel;
defined('ACCESS') or die('no direct access');

class Menu extends \Ilch\Controller\Admin
{
	public function indexAction()
	{
		$menuMapper = new MenuMapper();

		if($this->getRequest()->isPost())
		{
			$menuModel = new MenuModel();
			$menuModel->setId(1);
			$menuModel->setContent($this->getRequest()->getPost('hiddenMenu'));
			$menuMapper->save($menuModel);
		}
		
		$this->getView()->set('menus', $menuMapper->getMenus());
	}
}