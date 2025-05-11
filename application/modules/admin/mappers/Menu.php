<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Ilch\Mapper;
use Modules\Admin\Models\MenuItem;
use Modules\Admin\Models\Menu as MenuModel;

class Menu extends Mapper
{
    /**
     * Gets the menu id for the menu position.
     *
     * @param int $position
     * @return false|string|null
     */
    public function getMenuIdForPosition(int $position)
    {
        return $this->db()->select(['id'])
            ->from('menu')
            ->order(['id' => 'ASC'])
            ->limit(1)
            ->offset($position - 1)
            ->execute()
            ->fetchCell();
    }

    /**
     * Gets the menus.
     *
     * @return MenuModel[]
     */
    public function getMenus(): array
    {
        $menus = [];
        $menuRows = $this->db()->select(['id','title'])
            ->from('menu')
            ->execute()
            ->fetchRows();

        foreach ($menuRows as $menuRow) {
            $menu = new MenuModel();
            $menu->setId($menuRow['id']);
            $menu->setTitle($menuRow['title']);
            $menus[] = $menu;
        }

        return $menus;
    }

    /**
     * Gets the menu for the given id.
     *
     * @param int $menuId
     * @return MenuModel|null
     */
    public function getMenu(int $menuId): ?MenuModel
    {
        $menuRow = $this->db()->select(['id','title'])
            ->from('menu')
            ->where(['id' => $menuId])
            ->execute()
            ->fetchAssoc();

        if (empty($menuRow)) {
            return null;
        }

        $menu = new MenuModel();
        $menu->setId($menuRow['id']);
        $menu->setTitle($menuRow['title']);

        return $menu;
    }

    /**
     * Gets all menu items by menu id.
     * @param int $menuId
     * @return array|[]
     */
    public function getMenuItems(int $menuId): array
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
     * Save one menu item.
     *
     * @param MenuItem $menuItem
     * @return int
     */
    public function saveItem(MenuItem $menuItem): int
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
     * @return false|string|null
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
     * @return false|string|null
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
     * @return int
     */
    public function save(MenuModel $menu): int
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
    public function deleteItem(MenuItem $menuItem)
    {
        $this->db()->delete('menu_items')
            ->where(['id' => $menuItem->getId()])
            ->execute();
    }

    /**
     * Delete items for the given modulkey.
     *
     * @param string $moduleKey
     * @param int|null $parentID
     */
    public function deleteItemsByModuleKey(string $moduleKey, ?int $parentID = null)
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
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('menu')
            ->where(['id' => $id])
            ->execute();
        // Rows in menu_items get deleted due to a FKC.

        // Truncate table if this was the last menu. This will also reset AUTO_INCREMENT.
        if (!$this->getMenus()) {
            $this->db()->truncate('menu');
        }
    }

    /**
     * Delete all items with a specific menu id.
     *
     * @param int $menuId
     */
    public function deleteItemsByMenuId(int $menuId)
    {
        $this->db()->delete('menu_items')
            ->where(['menu_id' => $menuId])
            ->execute();
    }

    /**
     * Delete menu item by the box id.
     *
     * @param int $boxId
     */
    public function deleteItemByBoxId(int $boxId)
    {
        $this->db()->delete('menu_items')
            ->where(['box_id' => $boxId])
            ->execute();
    }

    /**
     * Delete menu item by the page id.
     *
     * @param int $pageId
     */
    public function deleteItemByPageId(int $pageId)
    {
        $this->db()->delete('menu_items')
            ->where(['page_id' => $pageId])
            ->execute();
    }
}
