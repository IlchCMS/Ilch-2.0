<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\MenuItem;
use Modules\Admin\Models\Menu as MenuModel;

class Menu extends \Ilch\Mapper
{
    /**
     * Gets the menu id for the menu position.
     *
     * @param integer $position
     * @return int|string
     * @throws \Ilch\Database\Exception
     */
    public function getMenuIdForPosition($position)
    {
        return $this->db()->select(['id'])
            ->from('menu')
            ->order(['id' => 'ASC'])
            ->limit(1)
            ->offset(intval($position-1))
            ->execute()
            ->fetchCell();
    }

    /**
     * Gets the menus.
     * 
     * @return \Modules\Admin\Models\Menu[]
     */
    public function getMenus()
    {
        $menus = [];
        $menuRows = $this->db()->select(['id'])
            ->from('menu')
            ->execute()
            ->fetchRows();

        foreach ($menuRows as $menuRow) {
            $menu = $this->getMenu($menuRow['id']);
            $menus[] = $menu;
        }

        return $menus;
    }

    /**
     * Gets the menu for the given id.
     *
     * @param $menuId
     * @return \Modules\Admin\Models\Menu
     */
    public function getMenu($menuId)
    {
        $menu = new MenuModel();
        
        $menuRow = $this->db()->select(['id','title'])
            ->from('menu')
            ->where(['id' => $menuId])
            ->execute()
            ->fetchAssoc();

        $menu->setId($menuRow['id']);
        $menu->setTitle($menuRow['title']);

        return $menu;
    }

    /**
     * Gets all menu items by menu id.
     * @param $menuId
     * @return array|[]
     */
    public function getMenuItems($menuId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('menu_items')
                ->where(['menu_id' => $menuId])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return [];
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new MenuItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setSiteId($itemRow['page_id']);
            $itemModel->setBoxId($itemRow['box_id']);
            $itemModel->setBoxKey($itemRow['box_key']);
            $itemModel->setHref($itemRow['href']);
            $itemModel->setTarget($itemRow['target']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setModuleKey($itemRow['module_key']);
            $itemModel->setAccess($itemRow['access']);
            $itemModel->setMenuId($menuId);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets all menu items by parent item id.
     * @param $menuId
     * @param $itemId
     * @return array|null
     */
    public function getMenuItemsByParent($menuId, $itemId)
    {
        $items = [];
        $itemRows = $this->db()->select('*')
                ->from('menu_items')
                ->where(['menu_id' => $menuId, 'parent_id' => $itemId])
                ->order(['sort' => 'ASC'])
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new MenuItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setSiteId($itemRow['page_id']);
            $itemModel->setBoxId($itemRow['box_id']);
            $itemModel->setBoxKey($itemRow['box_key']);
            $itemModel->setHref($itemRow['href']);
            $itemModel->setTarget($itemRow['target']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setModuleKey($itemRow['module_key']);
            $itemModel->setAccess($itemRow['access']);
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
        $fields = [
            'href' => $menuItem->getHref(),
            'target' => $menuItem->getTarget(),
            'title' => $menuItem->getTitle(),
            'menu_id' => $menuItem->getMenuId(),
            'sort' => $menuItem->getSort(),
            'parent_id' => $menuItem->getParentId(),
            'page_id' => $menuItem->getSiteId(),
            'box_id' => $menuItem->getBoxId(),
            'box_key' => $menuItem->getBoxKey(),
            'type' => $menuItem->getType(),
            'module_key' => $menuItem->getModuleKey(),
            'access' => $menuItem->getAccess()
        ];

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id', 'menu_items', ['id' => $menuItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('menu_items')
                ->values($fields)
                ->where(['id' => $itemId])
                ->execute();
        } else {
            $itemId = $this->db()->insert('menu_items')
                ->values($fields)
                ->execute();
        }

        return $itemId;
    }

    /**
     * Get last menu id.
     *
     * @return int|string|null
     * @throws \Ilch\Database\Exception
     */
    public function getLastMenuId()
    {
        return $this->db()->select('MAX(id)')
            ->from('menu')
            ->execute()
            ->fetchCell();
    }

    /**
     * Get last menu item id.
     *
     * @return int|string|null
     * @throws \Ilch\Database\Exception
     */
    public function getLastMenuItemId()
    {
        return $this->db()->select('MAX(id)')
            ->from('menu_items')
            ->execute()
            ->fetchCell();
    }

    /**
     * Save one menu.
     *
     * @param MenuModel $menu
     * @return integer
     */
    public function save(MenuModel $menu)
    {
        $menuId = (int)$this->db()->select('id', 'menu', ['id' => $menu->getId()])
            ->execute()
            ->fetchCell();

        if (!$menuId) {
            $menuId = $this->db()->insert('menu')
                ->values(['title' => $menu->getTitle()])
                ->execute();
        }

        return $menuId;
    }

    /**
     * Delete the given menu item.
     *
     * @param MenuItem $menuItem
     */
    public function deleteItem($menuItem)
    {
        $this->db()->delete('menu_items')
            ->where(['id' => $menuItem->getId()])
            ->execute();
    }

    /**
     * Delete items for the given modulkey.
     *
     * @param string $moduleKey
     * @param int $parentID
     */
    public function deleteItemsByModuleKey($moduleKey, $parentID = null)
    {
        if ($parentID === null) {
            $itemRows = $this->db()->select('*')
                ->from('menu_items')
                ->where(['module_key' => $moduleKey])
                ->execute()
                ->fetchRows();
        } else {
            $itemRows = $this->db()->select('*')
                ->from('menu_items')
                ->where(['parent_id' => $parentID])
                ->execute()
                ->fetchRows();
         }

         foreach ($itemRows as $item) {
             $this->deleteItemsByModuleKey($moduleKey, $item['id']);
             $this->db()->delete('menu_items')
                ->where(['id' => $item['id']])
                ->execute();
         }
    }

    /**
     * Delete the given menu.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('menu')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Delete all items with a specific menu id.
     *
     * @param integer $menuId
     */
    public function deleteItemsByMenuId($menuId)
    {
        $this->db()->delete('menu_items')
            ->where(['menu_id' => $menuId])
            ->execute();
    }

    /**
     * Delete menu item by the box id.
     *
     * @param integer $boxId
     */
    public function deleteItemByBoxId($boxId)
    {
        $this->db()->delete('menu_items')
            ->where(['box_id' => $boxId])
            ->execute();
    }

    /**
     * Delete menu item by the page id.
     *
     * @param integer $pageId
     */
    public function deleteItemByPageId($pageId)
    {
        $this->db()->delete('menu_items')
            ->where(['page_id' => $pageId])
            ->execute();
    }
}
