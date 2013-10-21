<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
use Admin\Mappers\Menu as MenuMapper;
use Admin\Models\MenuItem;
defined('ACCESS') or die('no direct access');

class Menu extends \Ilch\Controller\Admin
{
	public function indexAction()
	{
		$menuMapper = new MenuMapper();

		if($this->getRequest()->isPost())
		{
			$sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
			$items = $this->getRequest()->getPost('items');

			if($items)
			{
				$sortArray = array();

				foreach($sortItems as $sortItem)
				{
					if($sortItem->item_id !== null)
					{
						$sortArray[$sortItem->item_id] = (int)$sortItem->parent_id;
					}
				}

				foreach($items as $item)
				{
					$menuItem = new MenuItem();

					if(strpos($item['id'], 'tmp_') !== false)
					{
						$item['id'] = str_replace('tmp_', '', $item['id']);
					}
					else
					{
						$menuItem->setId($item['id']);
					}
	
					if(isset($sortArray[$item['id']]))
					{
						$menuItem->setParentId($sortArray[$item['id']]);
					}
					else
					{
						$menuItem->setParentId(0);	
					}
					
					$menuItem->setMenuId(1);
					$menuItem->setHref($item['href']);
					$menuItem->setTitle($item['title']);
					$menuMapper->saveItem($menuItem);
				}
			}
		}

		$menuItems = $menuMapper->getMenuItemsByParent(1, 0);
		$this->getView()->set('menuItems', $menuItems);
		$this->getView()->set('menuMapper', $menuMapper);
	}
}