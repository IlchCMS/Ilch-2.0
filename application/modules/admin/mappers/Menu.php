<?php
/**
 * Holds Menu.
 *
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Mappers;
use Admin\Models\MenuItem;
use Admin\Models\Menu as MenuModel;
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
     * Gets the menu for the given id.
     * 
     * @return \Admin\Models\Menu
     */
    public function getMenu($menuId)
    {
        $menu = new \Admin\Models\Menu($this);
        
        $menuRow = $this->db()->selectRow
        (
            array('id','title'),
            'menu',
            array('id' => $menuId)
        );

        $menu->setId($menuRow['id']);
        $menu->setTitle($menuRow['title']);

        return $menu;
    }

    /**
     * Gets all menu items by menu id.
     */
    public function getMenuItems($menuId)
    {
        $items = array();
        $itemRows = $this->db()->selectArray
        (
            '*',
            'menu_items',
            array
            (
                'menu_id' => $menuId,
            )
        );

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new MenuItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setSiteId($itemRow['page_id']);
            $itemModel->setHref($itemRow['href']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setMenuId($menuId);
            $items[] = $itemModel;
        }

        return $items;
    }

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

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new MenuItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setSiteId($itemRow['page_id']);
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
     * @param  MenuItem $menuItem
     * @return integer
     */
    public function saveItem(MenuItem $menuItem)
    {
        $fields = array
        (
            'href' => $menuItem->getHref(),
            'title' => $menuItem->getTitle(),
            'menu_id' => $menuItem->getMenuId(),
            'parent_id' => $menuItem->getParentId(),
            'page_id' => $menuItem->getSiteId(),
            'type' => $menuItem->getType(),
        );

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int) $this->db()->selectCell
        (
            'id',
            'menu_items',
            array
            (
                'id' => $menuItem->getId(),
            )
        );

        if ($itemId) {
            $this->db()->update
            (
                $fields,
                'menu_items',
                array
                (
                    'id' => $itemId,
                )
            );
        } else {
            $itemId = $this->db()->insert
            (
                $fields,
                'menu_items'
            );
        }

        return $itemId;
    }
    
    /**
     * Save one menu.
     *
     * @param MenuModel $menu
     * @return integer
     */
    public function save(MenuModel $menu)
    {
        $menuId = (int)$this->db()->selectCell
        (
            'id',
            'menu',
            array
            (
                'id' => $menu->getId(),
            )
        );

        if ($menuId) {
            $this->db()->update
            (
                array('title' => $menu->getTitle()),
                'menu',
                array
                (
                    'id' => $menuId,
                )
            );
        } else {
            $menuId = $this->db()->insert
            (
                array('title' => $menu->getTitle()),
                'menu'
            );
        }

        return $menuId;
    }
 
    /**
     * Delete the given menu item.
     *
     * @param  MenuItem $menuItem
     */
    public function delete($menuItem)
    {
        $this->db()->delete
        (
            'menu_items',
            array
            (
                'id' => $menuItem->getId(),
            )
        );
    }
}
