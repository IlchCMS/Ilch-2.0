<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Models\MenuItem;
use Modules\Admin\Models\Menu as MenuModel;

class MenuTest extends DatabaseTestCase
{
    /**
     * @var Menu
     */
    protected Menu $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new Menu();
    }

    /**
     * Tests that getMenuIdForPosition() returns the id of the menu at the given position.
     */
    public function testGetMenuIdForPosition()
    {
        self::assertEquals(1, $this->out->getMenuIdForPosition(1));
        self::assertEquals(2, $this->out->getMenuIdForPosition(2));
    }

    /**
     * Tests that getMenuIdForPosition() returns false/null for a position beyond the existing menus.
     */
    public function testGetMenuIdForPositionOutOfRange()
    {
        self::assertEmpty($this->out->getMenuIdForPosition(99));
    }

    /**
     * Tests that getMenus() returns all menus.
     */
    public function testGetMenus()
    {
        $menus = $this->out->getMenus();

        self::assertCount(2, $menus);
        self::assertInstanceOf(MenuModel::class, $menus[0]);
    }

    /**
     * Tests that getMenus() returns the correct fields for each menu.
     */
    public function testGetMenusFields()
    {
        $titles = [];
        foreach ($this->out->getMenus() as $menu) {
            $titles[$menu->getId()] = $menu->getTitle();
        }

        self::assertSame('Main Menu', $titles[1] ?? null);
        self::assertSame('Footer Menu', $titles[2] ?? null);
    }

    /**
     * Tests that getMenus() returns an empty array when no menus exist.
     */
    public function testGetMenusEmpty()
    {
        // Removed directly via SQL because the mapper's delete() of the last menu
        // triggers an auto-truncate that fails on the foreign key (see testDeleteLastMenu).
        $this->db->query('DELETE FROM `[prefix]_menu_items`');
        $this->db->query('DELETE FROM `[prefix]_menu`');

        self::assertIsArray($this->out->getMenus());
        self::assertCount(0, $this->out->getMenus());
    }

    /**
     * Tests that getMenu() returns the menu with the given id.
     */
    public function testGetMenu()
    {
        $menu = $this->out->getMenu(1);

        self::assertNotNull($menu);
        self::assertEquals(1, $menu->getId());
        self::assertEquals('Main Menu', $menu->getTitle());
    }

    /**
     * Tests that getMenu() returns null for a non-existent id.
     */
    public function testGetMenuNotFound()
    {
        self::assertNull($this->out->getMenu(999));
    }

    /**
     * Tests that getMenuItems() returns all items of the given menu in ascending sort order.
     */
    public function testGetMenuItems()
    {
        $items = $this->out->getMenuItems(1);

        self::assertCount(4, $items);
        self::assertInstanceOf(MenuItem::class, $items[0]);
        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
        self::assertEquals(3, $items[2]->getId());
        self::assertEquals(5, $items[3]->getId());
    }

    /**
     * Tests that getMenuItems() returns the correct fields for a module link item.
     */
    public function testGetMenuItemsModuleLinkFields()
    {
        $items = $this->out->getMenuItems(1);

        self::assertEquals(MenuItem::TYPE_MODULE_LINK, $items[0]->getType());
        self::assertEquals('Contact', $items[0]->getTitle());
        self::assertEquals('contact', $items[0]->getModuleKey());
        self::assertEquals(0, $items[0]->getSiteId());
        self::assertEquals(0, $items[0]->getBoxId());
        self::assertEquals(0, $items[0]->getParentId());
        self::assertEquals(1, $items[0]->getMenuId());
        self::assertEquals('1,2,3', $items[0]->getAccess());
        // The database value is null, the model casts it to an empty string.
        self::assertSame('', $items[0]->getHref());
        self::assertSame('', $items[0]->getBoxKey());
        // target is not cast, null is kept.
        self::assertNull($items[0]->getTarget());
    }

    /**
     * Tests that getMenuItems() returns the correct fields for a link item.
     */
    public function testGetMenuItemsLinkFields()
    {
        $items = $this->out->getMenuItems(2);

        self::assertCount(1, $items);
        self::assertEquals(MenuItem::TYPE_LINK, $items[0]->getType());
        self::assertEquals('Impressum', $items[0]->getTitle());
        self::assertEquals('/impressum', $items[0]->getHref());
        self::assertEquals('_blank', $items[0]->getTarget());
        self::assertEquals(2, $items[0]->getMenuId());
    }

    /**
     * Tests that getMenuItems() returns an empty array for a menu without items.
     */
    public function testGetMenuItemsEmpty()
    {
        self::assertIsArray($this->out->getMenuItems(999));
        self::assertCount(0, $this->out->getMenuItems(999));
    }

    /**
     * Tests that saveItem() inserts a new menu item.
     */
    public function testSaveItemInsert()
    {
        $item = new MenuItem();
        $item->setMenuId(1);
        $item->setSort(5);
        $item->setType(MenuItem::TYPE_LINK);
        $item->setTitle('External Link');
        $item->setHref('https://www.example.com');
        $item->setAccess('1,2,3');

        $newId = $this->out->saveItem($item);

        self::assertGreaterThan(5, $newId);

        $items = $this->out->getMenuItems(1);
        self::assertCount(5, $items);

        $saved = null;
        foreach ($items as $candidate) {
            if ($candidate->getId() === $newId) {
                $saved = $candidate;
            }
        }

        self::assertNotNull($saved);
        self::assertEquals('External Link', $saved->getTitle());
        self::assertEquals('https://www.example.com', $saved->getHref());
        self::assertEquals(MenuItem::TYPE_LINK, $saved->getType());
        self::assertEquals('1,2,3', $saved->getAccess());
        // box_id and parent_id were not set, the database defaults (0) are used.
        self::assertEquals(0, $saved->getBoxId());
        self::assertEquals(0, $saved->getParentId());
    }

    /**
     * Tests that saveItem() updates an existing menu item.
     */
    public function testSaveItemUpdate()
    {
        $item = new MenuItem();
        $item->setId(1);
        $item->setMenuId(1);
        $item->setSort(1);
        $item->setType(MenuItem::TYPE_MODULE_LINK);
        $item->setTitle('Updated Contact');
        $item->setModuleKey('contact');
        $item->setAccess('1,2');

        $itemId = $this->out->saveItem($item);

        self::assertEquals(1, $itemId);

        $items = $this->out->getMenuItems(1);
        self::assertCount(4, $items);

        $updated = null;
        foreach ($items as $candidate) {
            if ($candidate->getId() === 1) {
                $updated = $candidate;
            }
        }

        self::assertNotNull($updated);
        self::assertEquals('Updated Contact', $updated->getTitle());
        self::assertEquals('1,2', $updated->getAccess());
    }

    /**
     * Tests that saveItem() keeps untouched (null) fields of an existing item on update.
     */
    public function testSaveItemUpdateKeepsNullFields()
    {
        $update = new MenuItem();
        $update->setId(4);
        $update->setMenuId(2);
        $update->setType(MenuItem::TYPE_LINK);
        $update->setTitle('Legal Notice');
        $update->setAccess('1,2,3');

        $this->out->saveItem($update);

        $items = $this->out->getMenuItems(2);
        self::assertCount(1, $items);
        // The href is null in the update, so the previously saved value must remain.
        self::assertEquals('/impressum', $items[0]->getHref());
        self::assertEquals('Legal Notice', $items[0]->getTitle());
    }

    /**
     * Tests that getLastMenuId() returns the highest menu id.
     */
    public function testGetLastMenuId()
    {
        self::assertEquals(2, $this->out->getLastMenuId());
    }

    /**
     * Tests that getLastMenuId() returns null when no menus exist.
     */
    public function testGetLastMenuIdEmpty()
    {
        // Removed directly via SQL, see the note in testGetMenusEmpty.
        $this->db->query('DELETE FROM `[prefix]_menu_items`');
        $this->db->query('DELETE FROM `[prefix]_menu`');

        self::assertNull($this->out->getLastMenuId());
    }

    /**
     * Tests that getLastMenuItemId() returns the highest menu item id.
     */
    public function testGetLastMenuItemId()
    {
        self::assertEquals(5, $this->out->getLastMenuItemId());
    }

    /**
     * Tests that save() inserts a new menu and returns its id.
     */
    public function testSaveInsert()
    {
        $menu = new MenuModel();
        $menu->setId(0);
        $menu->setTitle('Navigation');

        $newId = $this->out->save($menu);

        self::assertGreaterThan(2, $newId);

        $found = $this->out->getMenu($newId);
        self::assertNotNull($found);
        self::assertEquals('Navigation', $found->getTitle());
    }

    /**
     * Tests that save() returns the existing id without inserting a duplicate for an existing menu.
     */
    public function testSaveExistingMenu()
    {
        $menu = new MenuModel();
        $menu->setId(1);
        $menu->setTitle('Changed Title');

        $menuId = $this->out->save($menu);

        self::assertEquals(1, $menuId);
        self::assertCount(2, $this->out->getMenus());

        // Note: save() does not update the title of an existing menu.
        self::assertEquals('Main Menu', $this->out->getMenu(1)->getTitle());
    }

    /**
     * Tests that deleteItem() removes the given menu item.
     */
    public function testDeleteItem()
    {
        $item = new MenuItem();
        $item->setId(2);

        $this->out->deleteItem($item);

        $items = $this->out->getMenuItems(1);
        self::assertCount(3, $items);
        foreach ($items as $candidate) {
            self::assertNotEquals(2, $candidate->getId());
        }
    }

    /**
     * Tests that deleteItem() on a non-existent id does not throw.
     */
    public function testDeleteItemNotFound()
    {
        $item = new MenuItem();
        $item->setId(999);

        $this->out->deleteItem($item);

        self::assertCount(4, $this->out->getMenuItems(1));
    }

    /**
     * Tests that deleteItemsByModuleKey() removes the module items and their whole subtree.
     */
    public function testDeleteItemsByModuleKey()
    {
        $this->out->deleteItemsByModuleKey('contact');

        // Item 1 (module contact), item 2 (child of 1) and item 5 (grandchild of 1) are removed.
        $items = $this->out->getMenuItems(1);
        self::assertCount(1, $items);
        self::assertEquals(3, $items[0]->getId());

        // Items of the other menu are untouched.
        self::assertCount(1, $this->out->getMenuItems(2));
    }

    /**
     * Tests that deleteItemsByModuleKey() does nothing for an unknown module key.
     */
    public function testDeleteItemsByModuleKeyNotFound()
    {
        $this->out->deleteItemsByModuleKey('unknownmodule');

        self::assertCount(4, $this->out->getMenuItems(1));
        self::assertCount(1, $this->out->getMenuItems(2));
    }

    /**
     * Tests that deleteItemsByMenuId() removes all items of the given menu.
     */
    public function testDeleteItemsByMenuId()
    {
        $this->out->deleteItemsByMenuId(1);

        self::assertCount(0, $this->out->getMenuItems(1));
        self::assertCount(1, $this->out->getMenuItems(2));
    }

    /**
     * Tests that deleteItemByBoxId() removes the menu items of the given box id.
     */
    public function testDeleteItemByBoxId()
    {
        $this->out->deleteItemByBoxId(7);

        $items = $this->out->getMenuItems(1);
        self::assertCount(3, $items);
        foreach ($items as $candidate) {
            self::assertNotEquals(7, $candidate->getBoxId());
        }
    }

    /**
     * Tests that deleteItemByPageId() removes the menu items of the given page id.
     */
    public function testDeleteItemByPageId()
    {
        $this->out->deleteItemByPageId(5);

        $items = $this->out->getMenuItems(1);
        self::assertCount(3, $items);
        foreach ($items as $candidate) {
            self::assertNotEquals(5, $candidate->getSiteId());
        }
    }

    /**
     * Tests that delete() removes the menu and its menu items.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getMenu(1));
        self::assertCount(1, $this->out->getMenus());
        self::assertCount(0, $this->out->getMenuItems(1));
        self::assertCount(1, $this->out->getMenuItems(2));
    }

    /**
     * Tests that delete() on the last menu also resets the auto increment of the menu table.
     *
     * NOTE: This test currently fails. Menu::delete() truncates the `menu` table when no menu
     * remains, but `menu_items` references `menu` via a foreign key constraint, so MySQL rejects
     * the TRUNCATE (error 1701). The test documents the intended behavior and should pass once
     * the truncate in Menu::delete() is fixed.
     */
    public function testDeleteLastMenu()
    {
        $this->out->delete(2);
        $this->out->delete(1);

        self::assertCount(0, $this->out->getMenus());

        $menu = new MenuModel();
        $menu->setId(0);
        $menu->setTitle('Fresh Menu');

        self::assertEquals(1, $this->out->save($menu));
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
