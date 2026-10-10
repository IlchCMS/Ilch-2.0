<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Infos as InfosMapper;
use Modules\Admin\Models\Infos as InfosModel;

class InfosTest extends DatabaseTestCase
{
    /**
     * @var InfosMapper
     */
    protected Infos $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new InfosMapper();
    }

    /**
     * Tests that getModulesFolderRights() returns all folder rights.
     */
    public function testGetModulesFolderRights()
    {
        $rights = $this->out->getModulesFolderRights();

        self::assertNotNull($rights);
        self::assertCount(3, $rights);
        self::assertInstanceOf(InfosModel::class, $rights[0]);

        $found = [];
        foreach ($rights as $right) {
            $found[] = $right->getKey() . ':' . $right->getFolder();
        }
        sort($found);
        self::assertEquals(['admin:backup', 'lastwar:downloads', 'lastwar:uploads'], $found);
    }

    /**
     * Tests that getModulesFolderRights() returns null if no rights match the where clause.
     */
    public function testGetModulesFolderRightsEmpty()
    {
        self::assertNull($this->out->getModulesFolderRights(['key' => 'contact']));
    }

    /**
     * Tests that getModulesFolderRightByKey() returns only the rights of the given module.
     */
    public function testGetModulesFolderRightByKey()
    {
        $rights = $this->out->getModulesFolderRightByKey('lastwar');

        self::assertNotNull($rights);
        self::assertCount(2, $rights);

        $folders = [];
        foreach ($rights as $right) {
            self::assertEquals('lastwar', $right->getKey());
            $folders[] = $right->getFolder();
        }
        sort($folders);
        self::assertEquals(['downloads', 'uploads'], $folders);
    }

    /**
     * Tests that getModulesFolderRightByKey() returns null for an unknown module.
     */
    public function testGetModulesFolderRightByKeyNotFound()
    {
        self::assertNull($this->out->getModulesFolderRightByKey('contact'));
    }

    /**
     * Tests that getModulesPHPExtensions() returns all php extensions.
     */
    public function testGetModulesPHPExtensions()
    {
        $extensions = $this->out->getModulesPHPExtensions();

        self::assertNotNull($extensions);
        self::assertCount(2, $extensions);
        self::assertInstanceOf(InfosModel::class, $extensions[0]);

        $found = [];
        foreach ($extensions as $extension) {
            $found[] = $extension->getKey() . ':' . $extension->getExtension();
        }
        sort($found);
        self::assertEquals(['lastwar:jpg', 'lastwar:png'], $found);
    }

    /**
     * Tests that getModulesPHPExtensions() returns null if nothing matches.
     */
    public function testGetModulesPHPExtensionsEmpty()
    {
        self::assertNull($this->out->getModulesPHPExtensions(['key' => 'contact']));
    }

    /**
     * Tests that getModulesPHPExtensionsByKey() returns the php extensions of the given module.
     *
     * NOTE: This test FAILS with the current implementation. getModulesPHPExtensionsByKey()
     * delegates to getModulesFolderRights() and therefore reads the modules_folderrights
     * table instead of the modules_php_extensions table (the models come back with a
     * null extension). See the "Issues" section in the accompanying report.
     */
    public function testGetModulesPHPExtensionsByKey()
    {
        $extensions = $this->out->getModulesPHPExtensionsByKey('lastwar');

        self::assertNotNull($extensions);
        self::assertCount(2, $extensions);

        $found = [];
        foreach ($extensions as $extension) {
            $found[] = $extension->getKey() . ':' . $extension->getExtension();
        }
        sort($found);
        self::assertEquals(['lastwar:jpg', 'lastwar:png'], $found);
    }

    /**
     * Tests that saveModulesFolderRights() replaces the folderrights of a module.
     */
    public function testSaveModulesFolderRights()
    {
        $this->out->saveModulesFolderRights('lastwar', ['newfolder' => 1, 'otherfolder' => 1]);

        $rights = $this->out->getModulesFolderRightByKey('lastwar');
        self::assertNotNull($rights);
        self::assertCount(2, $rights);

        $folders = [];
        foreach ($rights as $right) {
            $folders[] = $right->getFolder();
        }
        sort($folders);
        self::assertEquals(['newfolder', 'otherfolder'], $folders);

        // Other modules are unaffected
        $other = $this->out->getModulesFolderRightByKey('admin');
        self::assertNotNull($other);
        self::assertCount(1, $other);
        self::assertEquals('backup', $other[0]->getFolder());
    }

    /**
     * Tests that saveModulesFolderRights() performs a plain insert when the module
     * has no folderrights yet.
     */
    public function testSaveModulesFolderRightsInsert()
    {
        // Remove the existing folderrights of the admin module to test the insert path.
        $this->db->delete('modules_folderrights')->where(['key' => 'admin'])->execute();

        $this->out->saveModulesFolderRights('admin', ['uploads' => 1]);

        $rights = $this->out->getModulesFolderRightByKey('admin');
        self::assertNotNull($rights);
        self::assertCount(1, $rights);
        self::assertEquals('uploads', $rights[0]->getFolder());
    }

    /**
     * Tests that saveModulesFolderRights() is chainable (returns $this).
     */
    public function testSaveModulesFolderRightsReturnsSelf()
    {
        self::assertSame($this->out, $this->out->saveModulesFolderRights('admin', ['uploads' => 1]));
    }

    /**
     * Tests that saveModulesPHPExtensions() replaces the php extensions of a module.
     */
    public function testSaveModulesPHPExtensions()
    {
        $this->out->saveModulesPHPExtensions('lastwar', ['gif' => 1]);

        $extensions = $this->out->getModulesPHPExtensions(['key' => 'lastwar']);
        self::assertNotNull($extensions);
        self::assertCount(1, $extensions);
        self::assertEquals('gif', $extensions[0]->getExtension());
    }

    /**
     * Tests that saveModulesPHPExtensions() also replaces existing extensions when the
     * module has no folderrights.
     *
     * NOTE: This test FAILS with the current implementation: saveModulesPHPExtensions()
     * decides whether to delete the old rows based on getModulesPHPExtensionsByKey(),
     * which (due to the bug above) actually reads the modules_folderrights table.
     * Without folderrights the old extensions are never deleted and every save adds
     * duplicates.
     */
    public function testSaveModulesPHPExtensionsWithoutFolderrights()
    {
        $this->db->insert('modules')
            ->values(['key' => 'testmodule', 'icon_small' => ''])
            ->execute();
        $this->db->insert('modules_php_extensions')
            ->values(['key' => 'testmodule', 'extension' => 'jpg'])
            ->execute();

        $this->out->saveModulesPHPExtensions('testmodule', ['gif' => 1]);

        $extensions = $this->out->getModulesPHPExtensions(['key' => 'testmodule']);
        self::assertNotNull($extensions);
        self::assertCount(1, $extensions);
        self::assertEquals('gif', $extensions[0]->getExtension());
    }

    /**
     * Tests that saveModulesPHPExtensions() is chainable (returns $this).
     */
    public function testSaveModulesPHPExtensionsReturnsSelf()
    {
        self::assertSame($this->out, $this->out->saveModulesPHPExtensions('lastwar', ['gif' => 1]));
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
