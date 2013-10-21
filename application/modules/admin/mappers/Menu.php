<?php
/**
 * Holds Menu.
 *
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Mappers;
use Admin\Models\Menu as MenuModel;
use Admin\Models\MenuItem;
defined('ACCESS') or die('no direct access');

/**
 * The menu mapper class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Menu extends \Ilch\Mapper
{
	/**
	 * Gets all menu items by parent item id.
	 */
	public function getMenuItemsByParent($menuId, $itemId)
	{
		$items = array();
		$itemRows = $this->db()->selectArray
		(
			'*',
			'menu_items',
			array
			(
				'menu_id' => $menuId,
				'parent_id' => $itemId
			)
		);
		
		if(empty($itemRows))
		{
			return null;
		}
		
		foreach($itemRows as $itemRow)
		{
			$itemModel = new MenuItem();
			$itemModel->setId($itemRow['id']);
			$itemModel->setHref($itemRow['href']);
			$itemModel->setTitle($itemRow['title']);
			$itemModel->setParentId($itemId);
			$itemModel->setMenuId($menuId);
			$items[] = $itemModel;
		}

		return $items;
	}
	
	

	/**
	 * Save one menu item.
	 *
	 * @param MenuItem $menuItem
	 * @return integer
	 */
	public function saveItem(MenuItem $menuItem)
	{
		$fields = array
		(
			'href' => $menuItem->getHref(),
			'title' => $menuItem->getTitle(),
			'menu_id' => $menuItem->getMenuId(),
			'parent_id' => $menuItem->getParentId()
		);

		$itemId = (int)$this->db()->selectCell
		(
			'id',
			'menu_items',
			array
			(
				'id' => $menuItem->getId(),
			)
		);

		if($itemId)
		{
			$this->db()->update
			(
				$fields,
				'menu_items',
				array
				(
					'id' => $itemId,
				)
			);
		}
		else
		{
			$itemId = $this->db()->insert
			(
				$fields,
				'menu_items'
			);
		}
		
		return $itemId;
	}
}