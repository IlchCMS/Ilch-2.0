<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use Ilch\Registry;
use Ilch\Translator;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Models\Module as ModuleModel;

class ModuleTest extends DatabaseTestCase
{
    protected ModuleMapper $out;
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
        $this->out = new ModuleMapper();

        if (Registry::get('translator') === null) {
            Registry::set('translator', new Translator());
        }

        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', buildPath(__DIR__, '..', '..', '..', '..'));
        }
    }

    /**
     * Tests that getModules() returns all installed modules.
     * Requires a translator registered in the registry (active locale de_DE or en_EN).
     */
    public function testGetModules()
    {
        $modules = $this->out->getModules();

        self::assertNotNull($modules);
        self::assertCount(2, $modules);
        self::assertInstanceOf(ModuleModel::class, $modules[0]);

        self::assertSame('admin', $modules[0]->getKey());
        self::assertSame('Administration', $modules[0]->getName());

        self::assertSame('lastwar', $modules[1]->getKey());
        self::assertSame('Last War', $modules[1]->getName());
    }

    /**
     * Tests that getModules() returns the correct fields for both modules.
     */
    public function testGetModulesFields()
    {
        $modules = $this->out->getModules();

        $admin = $modules[0];
        self::assertFalse($admin->getSystemModule());
        self::assertFalse($admin->getLayoutModule());
        self::assertFalse($admin->getHideMenu());
        self::assertSame('', $admin->getVersion());
        self::assertSame('', $admin->getLink());
        self::assertSame('', $admin->getAuthor());
        self::assertSame('', $admin->getIconSmall());
        self::assertSame(['backup' => false], $admin->getFolderRights());
        self::assertCount(1, $admin->getContent());

        // Note: the mapper explodes the GROUP_CONCAT result even when it is empty,
        // so a module without PHP extensions contains one empty entry.
        self::assertSame(['' => false], $admin->getPHPExtension());

        $lastwar = $modules[1];
        // GROUP_CONCAT(...) in the mapper is sorted, so these arrays are in alphabetical order.
        self::assertSame(['jpg' => false, 'png' => false], $lastwar->getPHPExtension());
        self::assertSame(['downloads' => false, 'uploads' => false], $lastwar->getFolderRights());
    }

    /**
     * Tests that getModules() returns an empty array when no modules exist.
     */
    public function testGetModulesEmpty()
    {
        $this->db->delete()->from('modules')->execute();

        $modules = $this->out->getModules();

        self::assertIsArray($modules);
        self::assertCount(0, $modules);
    }

    /**
     * Tests that searchModules() returns an empty array for an empty query.
     */
    public function testSearchModulesEmptyQuery()
    {
        self::assertSame([], $this->out->searchModules(''));
        self::assertSame([], $this->out->searchModules('   '));
    }

    /**
     * Tests that searchModules() returns no match for unknown terms.
     */
    public function testSearchModulesNoMatch()
    {
        self::assertSame([], $this->out->searchModules('unknown'));
    }

    /**
     * Tests that searchModules() finds a module by an exact name match.
     */
    public function testSearchModulesExactNameMatch()
    {
        $matches = $this->out->searchModules('Last War');

        self::assertCount(1, $matches);
        self::assertSame('lastwar', $matches[0]['module']->getKey());
        self::assertSame(1, $matches[0]['rank']);
    }

    /**
     * Tests that searchModules() is case-insensitive.
     */
    public function testSearchModulesCaseInsensitive()
    {
        $matches = $this->out->searchModules('lAsT wAr');

        self::assertCount(1, $matches);
        self::assertSame('lastwar', $matches[0]['module']->getKey());
        self::assertSame(1, $matches[0]['rank']);
    }

    /**
     * Tests that searchModules() finds a module by a name prefix.
     */
    public function testSearchModulesNamePrefix()
    {
        $matches = $this->out->searchModules('last');

        self::assertCount(1, $matches);
        self::assertSame('lastwar', $matches[0]['module']->getKey());
        // "last" is a prefix of the name "last war" (rank 2); the key
        // match would be shifted to rank 5 and ranks below.
        self::assertSame(2, $matches[0]['rank']);
    }

    /**
     * Tests that searchModules() finds a module by a name substring.
     */
    public function testSearchModulesNameSubstring()
    {
        $matches = $this->out->searchModules('istra');

        self::assertCount(1, $matches);
        self::assertSame('admin', $matches[0]['module']->getKey());
        self::assertSame(3, $matches[0]['rank']);
    }

    /**
     * Tests that searchModules() finds a module by key when the name
     * does not match.
     */
    public function testSearchModulesKeyMatch()
    {
        $matches = $this->out->searchModules('lastwar');

        self::assertCount(1, $matches);
        self::assertSame('lastwar', $matches[0]['module']->getKey());
        // "lastwar" is the exact key (rank 1, shifted to 4); the name
        // "last war" does not contain "lastwar".
        self::assertSame(4, $matches[0]['rank']);
    }

    /**
     * Tests that a name match ranks above a key match.
     */
    public function testSearchModulesNameMatchBeatsKeyMatch()
    {
        // Module "zebra" matches by name only ("Alpha"), module "alpha"
        // by key only.
        $this->createTestModule('zebra', 'Alpha');
        $this->createTestModule('alpha', 'Zebra');

        $matches = $this->out->searchModules('alpha');

        self::assertCount(2, $matches);
        self::assertSame('zebra', $matches[0]['module']->getKey());
        self::assertSame(1, $matches[0]['rank']);
        self::assertSame('alpha', $matches[1]['module']->getKey());
        self::assertSame(4, $matches[1]['rank']);
    }

    /**
     * Tests that rank ties are broken by name and then key.
     */
    public function testSearchModulesTieBrokenAlphabetically()
    {
        // Both modules match by exact name ("Alpha"); the order falls
        // back to the module key.
        $this->createTestModule('delta', 'Alpha');
        $this->createTestModule('b_beta', 'Alpha');

        $matches = $this->out->searchModules('alpha');

        self::assertCount(2, $matches);
        self::assertSame('b_beta', $matches[0]['module']->getKey());
        self::assertSame('delta', $matches[1]['module']->getKey());
    }

    /**
     * Inserts a temporary module with a de_DE content row. The writes are
     * rolled back by the test database after the test.
     *
     * @param string $key
     * @param string $name
     */
    private function createTestModule(string $key, string $name): void
    {
        $module = new ModuleModel();
        $module->setKey($key)
            ->addContent('de_DE', ['name' => $name, 'description' => $name]);

        $this->out->save($module);
    }

    /**
     * Tests that getModulesByKey() returns the module content for key and locale.
     */
    public function testGetModulesByKey()
    {
        $module = $this->out->getModulesByKey('admin', 'de_DE');

        self::assertNotNull($module);
        self::assertSame('Administration', $module->getName());
    }

    /**
     * Tests that getModulesByKey() returns null when no content exists.
     */
    public function testGetModulesByKeyNotFound()
    {
        self::assertNull($this->out->getModulesByKey('admin', 'fr_FR'));
        self::assertNull($this->out->getModulesByKey('unknown', 'de_DE'));
    }

    /**
     * Tests that getModuleByKey() returns the module with the given key.
     */
    public function testGetModuleByKey()
    {
        $module = $this->out->getModuleByKey('admin');

        self::assertNotNull($module);
        self::assertSame('admin', $module->getKey());
        self::assertSame('', $module->getVersion());
    }

    /**
     * Tests that getModuleByKey() returns null for a non-existent key.
     */
    public function testGetModuleByKeyNotFound()
    {
        self::assertNull($this->out->getModuleByKey('unknown'));
    }

    /**
     * Tests that getModuleByKey() uses its cache unless force is set.
     */
    public function testGetModuleByKeyCaching()
    {
        $module = $this->out->getModuleByKey('admin');
        self::assertNotNull($module);

        $this->db->update('modules')
            ->values(['icon_small' => 'fa-solid fa-gears'])
            ->where(['key' => 'admin'])
            ->execute();

        // Without force the cached result is returned.
        $cached = $this->out->getModuleByKey('admin');
        self::assertSame($module, $cached);
        self::assertSame('', $cached->getIconSmall());

        // With force the new value from the database is returned.
        $fresh = $this->out->getModuleByKey('admin', true);
        self::assertNotSame($module, $fresh);
        self::assertSame('fa-solid fa-gears', $fresh->getIconSmall());
    }

    /**
     * Tests that getKeysInstalledModules() returns the keys of all installed modules.
     */
    public function testGetKeysInstalledModules()
    {
        $keys = $this->out->getKeysInstalledModules();

        self::assertIsArray($keys);
        self::assertCount(2, $keys);
        self::assertContains('admin', $keys);
        self::assertContains('lastwar', $keys);
    }

    /**
     * Tests that getKeysInstalledModules() returns an empty array when no modules exist.
     */
    public function testGetKeysInstalledModulesEmpty()
    {
        $this->db->delete()->from('modules')->execute();

        self::assertSame([], $this->out->getKeysInstalledModules());
    }

    /**
     * Tests that getVersionsOfModules() contains the installed modules with their versions.
     * Note: also contains not installed (filesystem) modules, so the count is not asserted.
     */
    public function testGetVersionsOfModules()
    {
        $versions = $this->out->getVersionsOfModules();

        self::assertIsArray($versions);
        self::assertArrayHasKey('admin', $versions);
        self::assertArrayHasKey('lastwar', $versions);

        self::assertSame('admin', $versions['admin']['key']);
        self::assertNull($versions['admin']['version']);
        self::assertNull($versions['lastwar']['version']);
    }

    /**
     * Tests that updateVersion() updates the version of a module.
     */
    public function testUpdateVersion()
    {
        $this->out->updateVersion('lastwar', '2.0.1');

        $module = $this->out->getModuleByKey('lastwar', true);
        self::assertNotNull($module);
        self::assertSame('2.0.1', $module->getVersion());
    }

    /**
     * Tests inserting a new module via save().
     */
    public function testSaveInsert()
    {
        $module = new ModuleModel();
        $module->setKey('testmodule')
            ->setVersion('1.0')
            ->setAuthor('Test Author')
            ->setLink('https://example.com')
            ->addContent('de_DE', ['name' => 'Testmodule', 'description' => 'A test module']);

        $result = $this->out->save($module);

        self::assertTrue($result);
        self::assertContains('testmodule', $this->out->getKeysInstalledModules());

        $saved = $this->out->getModuleByKey('testmodule', true);
        self::assertNotNull($saved);
        self::assertSame('1.0', $saved->getVersion());
        self::assertSame('Test Author', $saved->getAuthor());
        self::assertSame('https://example.com', $saved->getLink());

        $content = $this->out->getModulesByKey('testmodule', 'de_DE');
        self::assertNotNull($content);
        self::assertSame('Testmodule', $content->getName());
    }

    /**
     * Tests updating an existing module via save().
     */
    public function testSaveUpdate()
    {
        $module = new ModuleModel();
        $module->setKey('lastwar')
            ->setVersion('3.0.0')
            ->setAuthor('New Author')
            ->addContent('de_DE', ['name' => 'Last War (updated)', 'description' => 'Updated description']);

        $result = $this->out->save($module);

        self::assertTrue($result);

        $saved = $this->out->getModuleByKey('lastwar', true);
        self::assertNotNull($saved);
        self::assertSame('3.0.0', $saved->getVersion());
        self::assertSame('New Author', $saved->getAuthor());

        $content = $this->out->getModulesByKey('lastwar', 'de_DE');
        self::assertNotNull($content);
        self::assertSame('Last War (updated)', $content->getName());

        $description = $this->db->select('description')
            ->from('modules_content')
            ->where(['key' => 'lastwar', 'locale' => 'de_DE'])
            ->execute()
            ->fetchCell();
        self::assertSame('Updated description', $description);
    }

    /**
     * Tests that update does not affect other locales or modules.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $module = new ModuleModel();
        $module->setKey('lastwar')
            ->setVersion('3.0.0')
            ->addContent('de_DE', ['name' => 'Changed', 'description' => 'Changed']);

        $this->out->save($module);

        // The en_EN content row must remain untouched.
        $contentEn = $this->out->getModulesByKey('lastwar', 'en_EN');
        self::assertNotNull($contentEn);
        self::assertSame('Last War', $contentEn->getName());

        // The other module must remain untouched.
        $modules = $this->out->getModules();
        self::assertSame('Administration', $modules[0]->getName());
    }

    /**
     * Tests that delete() removes a module and its dependent rows.
     */
    public function testDelete()
    {
        $this->out->delete('lastwar');

        self::assertNull($this->out->getModuleByKey('lastwar', true));
        self::assertNull($this->out->getModulesByKey('lastwar', 'de_DE'));
        self::assertNotContains('lastwar', $this->out->getKeysInstalledModules());

        // Rows in modules_php_extensions and modules_folderrights are removed via FK cascade.
        $extensions = $this->db->select('extension')
            ->from('modules_php_extensions')
            ->where(['key' => 'lastwar'])
            ->execute()
            ->fetchList();
        $folderRights = $this->db->select('folder')
            ->from('modules_folderrights')
            ->where(['key' => 'lastwar'])
            ->execute()
            ->fetchList();

        self::assertCount(0, $extensions);
        self::assertCount(0, $folderRights);
    }

    /**
     * Tests that delete() also removes the menu items of the module.
     */
    public function testDeleteRemovesMenuItems()
    {
        $this->db->insert('menu_items')
            ->values([
                'menu_id' => 1,
                'sort' => 10,
                'parent_id' => 0,
                'page_id' => 0,
                'box_id' => 0,
                'type' => 3,
                'title' => 'Last War',
                'module_key' => 'lastwar',
                'access' => '1,2,3'
            ])
            ->execute();

        $this->out->delete('lastwar');

        $items = $this->db->select('id')
            ->from('menu_items')
            ->where(['module_key' => 'lastwar'])
            ->execute()
            ->fetchList();
        self::assertCount(0, $items);
    }

    /**
     * Tests that getLocalModules() returns the locally available module keys.
     * Requires a real modules directory at ROOT_PATH.
     */
    public function testGetLocalModules()
    {
        $modules = $this->out->getLocalModules();

        self::assertIsArray($modules);
        self::assertContains('admin', $modules);
    }

    /**
     * Tests that getModulesNotInstalled() excludes installed and system modules.
     * Depends on the modules directory at ROOT_PATH.
     */
    public function testGetModulesNotInstalled()
    {
        $modules = $this->out->getModulesNotInstalled();

        self::assertIsArray($modules);

        $keys = [];
        foreach ($modules as $module) {
            self::assertInstanceOf(ModuleModel::class, $module);
            $keys[] = $module->getKey();
        }

        // "admin" is excluded by design, "lastwar" is installed in the database.
        self::assertNotContains('admin', $keys);
        self::assertNotContains('lastwar', $keys);
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
