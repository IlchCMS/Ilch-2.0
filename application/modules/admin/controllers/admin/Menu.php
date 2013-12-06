<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
use Admin\Mappers\Menu as MenuMapper;
use Page\Mappers\Page as PageMapper;
use Admin\Models\MenuItem;
use Admin\Models\Menu as MenuModel;
defined('ACCESS') or die('no direct access');

class Menu extends \Ilch\Controller\Admin
{
    public function indexAction()
    {
        $menuId = 1;
        $menuMapper = new MenuMapper();
        $pageMapper = new PageMapper();

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->isPost()) {
            $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
            $items = $this->getRequest()->getPost('items');
            $oldItems = $menuMapper->getMenuItems(1);

            /*
             * Deletes old entries from database.
             */
            if (!empty($oldItems)) {
                foreach ($oldItems as $oldItem) {
                    if (!isset($items[$oldItem->getId()])) {
                        $menuMapper->delete($oldItem);
                    }
                }
            }

            if ($items) {
                $sortArray = array();

                foreach ($sortItems as $sortItem) {
                    if ($sortItem->item_id !== null) {
                        $sortArray[$sortItem->item_id] = (int) $sortItem->parent_id;
                    }
                }

                foreach ($items as $item) {
                    $menuItem = new MenuItem();

                    if (strpos($item['id'], 'tmp_') !== false) {
                        $tmpId = str_replace('tmp_', '', $item['id']);
                    } else {
                        $menuItem->setId($item['id']);
                    }

                    $menuItem->setMenuId(1);
                    $menuItem->setType($item['type']);
                    $menuItem->setSiteId($item['siteid']);
                    $menuItem->setHref($item['href']);
                    $menuItem->setTitle($item['title']);
                    $newId = $menuMapper->saveItem($menuItem);

                    if (isset($tmpId)) {
                        foreach ($sortArray as $id => $parentId) {
                            if ($id == $tmpId) {
                                unset($sortArray[$id]);
                                $sortArray[$newId] = $parentId;
                            }

                            if ($parentId == $tmpId) {
                                $sortArray[$id] = $newId;
                            }
                        }
                    }
                }

                foreach ($sortArray as $id => $parent) {
                    $menuItem = new MenuItem();
                    $menuItem->setId($id);
                    $menuItem->setParentId($parent);
                    $menuMapper->saveItem($menuItem);
                }
            }

            $menu = new MenuModel();
            $menu->setId($menuId);
            $menu->setTitle($this->getRequest()->getPost('menuTitle'));
            $menuMapper->save($menu);

            $this->addMessage('saveSuccess');
        }

        $menuItems = $menuMapper->getMenuItemsByParent($menuId, 0);
        $menu = $menuMapper->getMenu($menuId);

        $this->getView()->set('menu', $menu);
        $this->getView()->set('menuItems', $menuItems);
        $this->getView()->set('menuMapper', $menuMapper);
        $this->getView()->set('pages', $pageMapper->getPageList($this->getTranslator()->getLocale()));
    }
}
