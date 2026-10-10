<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Updateservers as UpdateserversMapper;
use Modules\Admin\Models\Updateserver as UpdateserverModel;

class UpdateserversTest extends DatabaseTestCase
{
    protected UpdateserversMapper $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new UpdateserversMapper();
    }

    /**
     * Tests that getUpdateservers() returns all updateservers.
     * No fixture file is needed: the install SQL of the config inserts the two
     * default updateservers into admin_updateservers.
     */
    public function testGetUpdateservers()
    {
        $servers = $this->out->getUpdateservers();

        self::assertIsArray($servers);
        self::assertCount(2, $servers);
        self::assertInstanceOf(UpdateserverModel::class, $servers[0]);
    }

    /**
     * Tests that getUpdateservers() returns the correct fields.
     */
    public function testGetUpdateserversFields()
    {
        $servers = $this->out->getUpdateservers();

        self::assertEquals(1, $servers[0]->getId());
        self::assertEquals('https://www.ilch.de/ilch2_updates/stable/', $servers[0]->getURL());
        self::assertEquals('ilch', $servers[0]->getOperator());
        self::assertEquals('Germany', $servers[0]->getCountry());

        self::assertEquals(2, $servers[1]->getId());
        self::assertEquals('https://updates.nubbys.de/stable/', $servers[1]->getURL());
        self::assertEquals('RTX2070 (ilch-Team)', $servers[1]->getOperator());
        self::assertEquals('Germany', $servers[1]->getCountry());
    }

    /**
     * Tests that getUpdateservers() returns an empty array when no updateservers exist.
     */
    public function testGetUpdateserversEmpty()
    {
        $this->db->delete()->from('admin_updateservers')->execute();

        $servers = $this->out->getUpdateservers();

        self::assertIsArray($servers);
        self::assertCount(0, $servers);
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
