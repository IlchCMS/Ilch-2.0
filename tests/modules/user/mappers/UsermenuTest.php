<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\Usermenu as UsermenuMapper;
use Modules\User\Models\Usermenu as UsermenuModel;
use Modules\User\Models\Usermenusettings as UsermenuSettingsModel;

class UsermenuTest extends DatabaseTestCase
{
    /**
     * @var UsermenuMapper
     */
    protected UsermenuMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database_user_menu.yml');
        $this->out = new UsermenuMapper();
    }

    /**
     * Tests that getUserMenu() returns all menu entries.
     */
    public function testGetUserMenu()
    {
        $menu = $this->out->getUserMenu();

        self::assertNotNull($menu);
        self::assertCount(5, $menu);
        self::assertInstanceOf(UsermenuModel::class, $menu[0]);
    }

    /**
     * Tests that getUserMenu() returns the correct fields for the first entry.
     */
    public function testGetUserMenuFirstFields()
    {
        $menu = $this->out->getUserMenu();

        self::assertEquals(1, $menu[0]->getId());
        self::assertEquals('user/panel/index', $menu[0]->getKey());
        self::assertEquals('fa-solid fa-house', $menu[0]->getIcon());
        self::assertEquals(1, $menu[0]->getPosition());
    }

    /**
     * Tests that getUserMenu() returns the correct fields for the last entry.
     */
    public function testGetUserMenuLastFields()
    {
        $menu = $this->out->getUserMenu();

        self::assertEquals(5, $menu[4]->getId());
        self::assertEquals('user/panel/settings', $menu[4]->getKey());
        self::assertEquals('fa-solid fa-gears', $menu[4]->getIcon());
        self::assertEquals(5, $menu[4]->getPosition());
    }

    /**
     * Tests that getUserMenu() orders the entries by position, not by id.
     */
    public function testGetUserMenuOrderedByPosition()
    {
        // Add a row with a higher id but the lowest position: it must come first.
        $this->db->insert('user_menu')
            ->values(['key' => 'user/panel/extra', 'icon' => 'fa-solid fa-plus', 'position' => 0])
            ->execute();

        $menu = $this->out->getUserMenu();

        self::assertCount(6, $menu);
        self::assertEquals(0, $menu[0]->getPosition());
        self::assertEquals('user/panel/extra', $menu[0]->getKey());

        $positions = array_map(fn ($item) => $item->getPosition(), $menu);
        self::assertSame([0, 1, 2, 3, 4, 5], $positions);
    }

    /**
     * Tests that getUserMenu() returns null when no entries exist.
     */
    public function testGetUserMenuEmpty()
    {
        $this->db->truncate('[prefix]_user_menu');

        self::assertNull($this->out->getUserMenu());
    }

    /**
     * Tests that getUserMenuSettingsLinks() returns the links for the given locale.
     */
    public function testGetUserMenuSettingsLinks()
    {
        $links = $this->out->getUserMenuSettingsLinks('en_EN');

        self::assertNotNull($links);
        self::assertCount(2, $links);
        self::assertInstanceOf(UsermenuSettingsModel::class, $links[0]);

        // The mapper does not sort the results, so compare order-independently.
        $keys = array_map(fn ($item) => $item->getKey(), $links);
        sort($keys);
        self::assertSame(['opt_comments', 'opt_mail'], $keys);
    }

    /**
     * Tests that getUserMenuSettingsLinks() returns the correct fields.
     */
    public function testGetUserMenuSettingsLinksFields()
    {
        $links = $this->out->getUserMenuSettingsLinks('en_EN');

        $mail = null;
        foreach ($links as $link) {
            if ($link->getKey() === 'opt_mail') {
                $mail = $link;
            }
        }

        self::assertNotNull($mail);
        self::assertSame('en_EN', $mail->getLocale());
        self::assertSame('Receive e-mail notifications', $mail->getName());
        self::assertSame('Receive an e-mail when something happens', $mail->getDescription());
    }

    /**
     * Tests that getUserMenuSettingsLinks() only returns the entries of the requested locale.
     */
    public function testGetUserMenuSettingsLinksGermanLocale()
    {
        $links = $this->out->getUserMenuSettingsLinks('de_DE');

        self::assertNotNull($links);
        self::assertCount(1, $links);
        self::assertSame('opt_mail', $links[0]->getKey());
        self::assertSame('de_DE', $links[0]->getLocale());
        self::assertSame('E-Mail-Benachrichtigungen empfangen', $links[0]->getName());
    }

    /**
     * Tests that getUserMenuSettingsLinks() returns null when no entries exist for the locale.
     */
    public function testGetUserMenuSettingsLinksUnknownLocale()
    {
        self::assertNull($this->out->getUserMenuSettingsLinks('fr_FR'));
    }

    /**
     * Tests that getUserMenuSettingsLinks() returns null when the table is empty.
     */
    public function testGetUserMenuSettingsLinksEmpty()
    {
        $this->db->truncate('[prefix]_user_menu_settings_links');

        self::assertNull($this->out->getUserMenuSettingsLinks('en_EN'));
    }

    /**
     * Returns database schema sql statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}
