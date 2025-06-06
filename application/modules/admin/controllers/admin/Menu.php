<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Mappers\Menu as MenuMapper;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\MenuItem;
use Modules\Admin\Models\Menu as MenuModel;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Menu extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menu'), ['action' => 'index']);

        $menuId = 1;

        if ($this->getRequest()->getParam('menu')) {
            $menuId = (int)$this->getRequest()->getParam('menu');
        }

        $menuMapper = new MenuMapper();
        $pageMapper = new PageMapper();
        $userGroupMapper = new UserGroupMapper();

        // Saves the item tree to database.
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = (!empty($this->getRequest()->getPost('items'))) ? $this->getRequest()->getPost('items') : [];

                foreach ($items as $item) {
                    $validation = Validation::create($item, [
                        'type' => 'required|numeric|integer',
                        'title' => 'required',
                    ]);
                    if (!$validation->isValid()) {
                        $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                        $this->redirect()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'index']);
                    }
                }

                $oldItems = $menuMapper->getMenuItems($menuId);

                // Deletes old entries from database.
                if (!empty($oldItems)) {
                    foreach ($oldItems as $oldItem) {
                        if (!isset($items[$oldItem->getId()])) {
                            $menuMapper->deleteItem($oldItem);
                        }
                    }
                }

                if ($items) {
                    $sortArray = [];

                    foreach ($sortItems as $sortItem) {
                        if ($sortItem->id !== null) {
                            $sortArray[$sortItem->id] = (int)$sortItem->parent_id;
                        }
                    }

                    $targets = [
                        '_blank',
                        '_self',
                        '_parent',
                        '_top',
                    ];

                    foreach ($items as $item) {
                        $entityMap = [
                            '&' => '',
                            '<' => '',
                            '>' => '',
                            '"' => '',
                            "'" => '',
                            '/' => '',
                            '(' => '',
                            ')' => '',
                            ';' => ''
                        ];

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
                        if ($item['type'] == 1 && in_array($item['target'], $targets)) {
                            $menuItem->setTarget($item['target']);
                        }
                        $menuItem->setTitle(strtr($item['title'], $entityMap));
                        $menuItem->setAccess($item['access']);

                        if ((int)$item['boxkey'] > 0) {
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
                $this->redirect(['action' => 'index']);
            }

            $this->addMessage('saveSuccess');
        }

        $menuItems = array_filter(
            $menuMapper->getMenuItems($menuId),
            fn($item) => $item->getParentId() === 0
        );
        $menu = $menuMapper->getMenu($menuId);
        $menus = $menuMapper->getMenus();

        if (!$menus) {
            // The last remaining menu was deleted. Restore one empty menu.
            $menuMapper = new MenuMapper();

            $menu = new MenuModel();
            $menu->setTitle('New');
            $menuMapper->save($menu);

            $menus = $menuMapper->getMenus();
            $this->addMessage('lastMenuDeletedRestored');
        }

        if (!$menu) {
            $this->addMessage('menuNotFound', 'danger');

            // Don't redirect to index if the default menu with the id 1 is missing. Redirect to the next existing menu.
            if ($this->getRequest()->getParam('menu')) {
                $this->redirect(['action' => 'index']);
            }

            $this->redirect(['action' => 'index', 'menu' => $menus[0]->getId()]);;
        }

        $userGroupList = $userGroupMapper->getGroupList();

        $moduleMapper = new ModuleMapper();
        $boxMapper = new BoxMapper();

        $locale = '';

        if ((bool)$this->getConfig()->get('multilingual_acp') && $this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
            $locale = $this->getTranslator()->getLocale();
        }

        $this->getView()->set('menu', $menu);
        $this->getView()->set('menus', $menus);
        $this->getView()->set('menuItems', $menuItems);
        $this->getView()->set('menuMapper', $menuMapper);
        $this->getView()->set('pages', $pageMapper->getPageList($locale));
        $this->getView()->set('boxes', $boxMapper->getBoxList($this->getTranslator()->getLocale()));
        $this->getView()->set('self_boxes', (array)$boxMapper->getSelfBoxList($locale));
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('userGroupList', $userGroupList);
    }

    public function addAction()
    {
        $menuMapper = new MenuMapper();
        $menu = new MenuModel();
        $menu->setTitle('New');
        $newId = $menuMapper->save($menu);
        $this->addMessage('saveSuccess');
        $this->redirect(['action' => 'index', 'menu' => $newId]);
    }
}
