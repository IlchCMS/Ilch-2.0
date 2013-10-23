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

        if ($this->getRequest()->isPost()) {
            $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
            $items = $this->getRequest()->getPost('items');

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
        }

        $menuItems = $menuMapper->getMenuItemsByParent(1, 0);
        $this->getView()->set('menuItems', $menuItems);
        $this->getView()->set('menuMapper', $menuMapper);
    }
}
