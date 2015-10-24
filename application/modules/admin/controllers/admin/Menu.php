<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Menu as MenuMapper;
use Modules\Page\Mappers\Page as PageMapper;
use Modules\Admin\Models\MenuItem;
use Modules\Admin\Models\Menu as MenuModel;

class Menu extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menu'), array('action' => 'index'));

        $menuId = 1;

        if ($this->getRequest()->getParam('menu')) {
            $menuId = (int)$this->getRequest()->getParam('menu');
        }

        $menuMapper = new MenuMapper();
        $pageMapper = new PageMapper();

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = $this->getRequest()->getPost('items');
                $oldItems = $menuMapper->getMenuItems($menuId);

                /*
                 * Deletes old entries from database.
                 */
                if (!empty($oldItems)) {
                    foreach ($oldItems as $oldItem) {
                        if (!isset($items[$oldItem->getId()])) {
                            $menuMapper->deleteItem($oldItem);
                        }
                    }
                }

                if ($items) {
                    $sortArray = array();

                    foreach ($sortItems as $sortItem) {
                        if ($sortItem->item_id !== null) {
                            $sortArray[$sortItem->item_id] = (int)$sortItem->parent_id;
                        }
                    }

                    foreach ($items as $item) {
                        $menuItem = new MenuItem();

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $menuItem->setId($item['id']);
                        }

                        $menuItem->setMenuId($menuId);
                        $menuItem->setType($item['type']);
                        $menuItem->setSiteId($item['siteid']);
                        $menuItem->setHref($item['href']);
                        $menuItem->setTitle($item['title']);

                        if((int)$item['boxkey'] > 0) {
                            $menuItem->setBoxId($item['boxkey']);
                        } else {
                            $menuItem->setBoxKey($item['boxkey']);
                        }

                        $menuItem->setModuleKey($item['modulekey']);

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

                    $sort = 0;

                    foreach ($sortArray as $id => $parent) {
                        $menuItem = new MenuItem();
                        $menuItem->setId($id);
                        $menuItem->setSort($sort);
                        $menuItem->setParentId($parent);
                        $menuMapper->saveItem($menuItem);
                        $sort += 10;
                    }
                }

                $menu = new MenuModel();
                $menu->setId($menuId);
                $menuMapper->save($menu);
            }

            if ($this->getRequest()->getPost('delete')) {
                $id = (int)$this->getRequest()->getParam('menu');
                $menuMapper->delete($id);
                $this->redirect(array('action' => 'index'));
            }

            $this->addMessage('saveSuccess');
        }

        $menuItems = $menuMapper->getMenuItemsByParent($menuId, 0);
        $menu = $menuMapper->getMenu($menuId);
        $menus = $menuMapper->getMenus();

        $moduleMapper = new \Modules\Admin\Mappers\Module();
        $boxMapper = new \Modules\Admin\Mappers\Box();

        $locale = '';

        if ((bool)$this->getConfig()->get('multilingual_acp')) {
            if ($this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }
        }

        $this->getView()->set('menu', $menu);
        $this->getView()->set('menus', $menus);
        $this->getView()->set('menuItems', $menuItems);
        $this->getView()->set('menuMapper', $menuMapper);
        $this->getView()->set('pages', $pageMapper->getPageList($locale));
        $this->getView()->set('boxes', (array)$boxMapper->getBoxList($locale));
        $this->getView()->set('modules', $moduleMapper->getModules());
    }

    public function addAction()
    {
        $menuMapper = new MenuMapper();
        $menu = new MenuModel();
        $menu->setTitle('New');
        $newId = $menuMapper->save($menu);
        $this->addMessage('saveSuccess');
        $this->redirect(array('action' => 'index', 'menu' => $newId));
    }
}
