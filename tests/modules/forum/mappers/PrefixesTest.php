<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Forum\Config\Config as ModuleConfig;
use Modules\Forum\Mappers\Prefixes as PrefixesMapper;
use Modules\Forum\Models\Prefix as PrefixModel;

class PrefixesTest extends DatabaseTestCase
{
    /**
     * @var PrefixesMapper
     */
    protected Prefixes $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PrefixesMapper();
    }

    /**
     * Tests that getPrefixes() returns all prefixes.
     */
    public function testGetPrefixes()
    {
        $prefixes = $this->out->getPrefixes();

        self::assertNotNull($prefixes);
        self::assertCount(2, $prefixes);
        self::assertInstanceOf(PrefixModel::class, $prefixes[1]);
    }

    /**
     * Tests that getPrefixes() returns correct fields for the first prefix.
     * Note: the result array is keyed by the prefix id.
     */
    public function testGetPrefixesFields()
    {
        $prefixes = $this->out->getPrefixes();

        self::assertEquals(1, $prefixes[1]->getId());
        self::assertEquals('Pinned', $prefixes[1]->getPrefix());
    }

    /**
     * Tests that getPrefixes() returns correct fields for the second prefix.
     */
    public function testGetPrefixesSecond()
    {
        $prefixes = $this->out->getPrefixes();

        self::assertEquals(2, $prefixes[2]->getId());
        self::assertEquals('Solved', $prefixes[2]->getPrefix());
    }

    /**
     * Tests that getPrefixes() returns null when no prefixes exist.
     */
    public function testGetPrefixesEmpty()
    {
        $this->out->deleteById(1);
        $this->out->deleteById(2);

        self::assertNull($this->out->getPrefixes());
    }

    /**
     * Tests that getPrefixById() returns the correct prefix.
     */
    public function testGetPrefixById()
    {
        $prefix = $this->out->getPrefixById(1);

        self::assertNotNull($prefix);
        self::assertEquals(1, $prefix->getId());
        self::assertEquals('Pinned', $prefix->getPrefix());
    }

    /**
     * Tests that getPrefixById() returns null for a non-existent id.
     */
    public function testGetPrefixByIdNotFound()
    {
        self::assertNull($this->out->getPrefixById(9999));
    }

    /**
     * Tests inserting a new prefix via save().
     */
    public function testSaveInsert()
    {
        $model = new PrefixModel();
        $model->setId(0)
            ->setPrefix('Announcement');

        $this->out->save($model);

        $prefixes = $this->out->getPrefixes();
        self::assertCount(3, $prefixes);

        // Find the new entry (result is keyed by id, which is auto-incremented).
        $new = null;
        foreach ($prefixes as $prefix) {
            if ($prefix->getPrefix() === 'Announcement') {
                $new = $prefix;
            }
        }

        self::assertNotNull($new);
        self::assertGreaterThan(2, $new->getId());
    }

    /**
     * Tests updating an existing prefix via save().
     */
    public function testSaveUpdate()
    {
        $model = new PrefixModel();
        $model->setId(1)
            ->setPrefix('Pinned Topic');

        $this->out->save($model);

        $prefix = $this->out->getPrefixById(1);
        self::assertNotNull($prefix);
        self::assertEquals(1, $prefix->getId());
        self::assertEquals('Pinned Topic', $prefix->getPrefix());
    }

    /**
     * Tests that updating a prefix does not affect other prefixes.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new PrefixModel();
        $model->setId(1)
            ->setPrefix('Changed');

        $this->out->save($model);

        $other = $this->out->getPrefixById(2);
        self::assertNotNull($other);
        self::assertEquals('Solved', $other->getPrefix());
    }

    /**
     * Tests that deleteById() removes a prefix.
     */
    public function testDeleteById()
    {
        $this->out->deleteById(1);

        self::assertNull($this->out->getPrefixById(1));

        // Remaining prefix should still be present
        $prefixes = $this->out->getPrefixes();
        self::assertCount(1, $prefixes);
        self::assertEquals(2, $prefixes[2]->getId());
    }

    /**
     * Tests that deleteById() on a non-existent id does not throw.
     */
    public function testDeleteByIdNotFound()
    {
        $this->out->deleteById(9999);

        // Existing prefixes should be unaffected
        self::assertCount(2, $this->out->getPrefixes());
    }

    /**
     * Tests that save() with id 0 performs an insert, not an update.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getPrefixes();
        self::assertCount(2, $before);

        $model = new PrefixModel();
        $model->setId(0)
            ->setPrefix('New Entry');

        $this->out->save($model);

        $after = $this->out->getPrefixes();
        self::assertCount(3, $after);

        // Original prefixes untouched
        self::assertEquals('Pinned', $after[1]->getPrefix());
        self::assertEquals('Solved', $after[2]->getPrefix());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}
