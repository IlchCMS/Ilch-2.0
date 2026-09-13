<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Partner\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Partner\Config\Config as ModuleConfig;
use Modules\Partner\Mappers\Partner as PartnerMapper;
use Modules\Partner\Models\Partner as PartnerModel;

class PartnerTest extends DatabaseTestCase
{
    /**
     * @var PartnerMapper
     */
    protected Partner $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new PartnerMapper();
    }

    /**
     * Tests that getEntries() returns all partners ordered by id DESC.
     */
    public function testGetEntries()
    {
        $partners = $this->out->getEntries();

        self::assertIsArray($partners);
        self::assertCount(3, $partners);
        self::assertInstanceOf(PartnerModel::class, $partners[0]);
    }

    /**
     * Tests that getEntries() orders by id DESC.
     */
    public function testGetEntriesOrderDesc()
    {
        $partners = $this->out->getEntries();

        self::assertEquals(3, $partners[0]->getId());
        self::assertEquals(2, $partners[1]->getId());
        self::assertEquals(1, $partners[2]->getId());
    }

    /**
     * Tests that getEntries() returns correct fields for the first entry (id=3, first in DESC order).
     */
    public function testGetEntriesFields()
    {
        $partners = $this->out->getEntries();

        self::assertEquals(3, $partners[0]->getId());
        self::assertEquals('Tech Partner', $partners[0]->getName());
        self::assertEquals('https://techpartner.com/banner.png', $partners[0]->getBanner());
        self::assertEquals('https://techpartner.com', $partners[0]->getLink());
        self::assertEquals(0, $partners[0]->getTarget());
        self::assertEquals(1, $partners[0]->getFree());
    }

    /**
     * Tests that getEntries() returns correct fields for the second entry (id=2).
     */
    public function testGetEntriesSecond()
    {
        $partners = $this->out->getEntries();

        self::assertEquals(2, $partners[1]->getId());
        self::assertEquals('Example Corp', $partners[1]->getName());
        self::assertEquals(1, $partners[1]->getTarget());
        self::assertEquals(0, $partners[1]->getFree());
    }

    /**
     * Tests that getEntries() returns correct fields for the third entry (id=1).
     */
    public function testGetEntriesThird()
    {
        $partners = $this->out->getEntries();

        self::assertEquals(1, $partners[2]->getId());
        self::assertEquals('ilch', $partners[2]->getName());
        self::assertEquals('https://www.ilch.de/include/images/linkus/88x31.png', $partners[2]->getBanner());
        self::assertEquals('https://ilch.de', $partners[2]->getLink());
        self::assertEquals(0, $partners[2]->getTarget());
        self::assertEquals(1, $partners[2]->getFree());
    }

    /**
     * Tests that getEntries() returns an empty array when no partners exist.
     */
    public function testGetEntriesEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $partners = $this->out->getEntries();

        self::assertIsArray($partners);
        self::assertCount(0, $partners);
    }

    /**
     * Tests that getEntries() filters by a where clause.
     */
    public function testGetEntriesWithWhere()
    {
        $partners = $this->out->getEntries(['name' => 'ilch']);

        self::assertCount(1, $partners);
        self::assertEquals(1, $partners[0]->getId());
        self::assertEquals('ilch', $partners[0]->getName());
    }

    /**
     * Tests that getEntries() filters by setfree.
     */
    public function testGetEntriesWhereSetfree()
    {
        $partners = $this->out->getEntries(['setfree' => 1]);

        self::assertCount(2, $partners);
        self::assertEquals(3, $partners[0]->getId());
        self::assertEquals(1, $partners[1]->getId());
    }

    /**
     * Tests that getEntries() returns empty array when no rows match.
     */
    public function testGetEntriesWhereNoMatch()
    {
        $partners = $this->out->getEntries(['id' => 9999]);

        self::assertIsArray($partners);
        self::assertCount(0, $partners);
    }

    /**
     * Tests that getPartnersBy() returns all partners ordered by id ASC (default).
     */
    public function testGetPartnersByDefaultOrderAsc()
    {
        $partners = $this->out->getPartnersBy();

        self::assertIsArray($partners);
        self::assertCount(3, $partners);
        self::assertEquals(1, $partners[0]->getId());
        self::assertEquals(2, $partners[1]->getId());
        self::assertEquals(3, $partners[2]->getId());
    }

    /**
     * Tests that getPartnersBy() applies custom ordering.
     */
    public function testGetPartnersByCustomOrder()
    {
        $partners = $this->out->getPartnersBy([], ['pos' => 'DESC']);

        self::assertCount(3, $partners);
        self::assertEquals(3, $partners[0]->getId());
        self::assertEquals(2, $partners[1]->getId());
        self::assertEquals(1, $partners[2]->getId());
    }

    /**
     * Tests that getPartnersBy() filters by where clause.
     */
    public function testGetPartnersByWithWhere()
    {
        $partners = $this->out->getPartnersBy(['target' => 1]);

        self::assertCount(1, $partners);
        self::assertEquals(2, $partners[0]->getId());
        self::assertEquals('Example Corp', $partners[0]->getName());
    }

    /**
     * Tests that getPartnersBy() returns empty array when no rows match.
     */
    public function testGetPartnersByEmpty()
    {
        $partners = $this->out->getPartnersBy(['id' => 9999]);

        self::assertIsArray($partners);
        self::assertCount(0, $partners);
    }

    /**
     * Tests that getEntriesBy() returns null when no partners exist.
     */
    public function testGetEntriesByReturnsNullWhenEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $result = $this->out->getEntriesBy();

        self::assertNull($result);
    }

    /**
     * Tests that getEntriesBy() returns partners with default id ASC order.
     */
    public function testGetEntriesByDefaultOrder()
    {
        $partners = $this->out->getEntriesBy();

        self::assertNotNull($partners);
        self::assertCount(3, $partners);
        self::assertEquals(1, $partners[0]->getId());
        self::assertEquals(2, $partners[1]->getId());
        self::assertEquals(3, $partners[2]->getId());
    }

    /**
     * Tests that getEntriesBy() with where clause returns subset.
     */
    public function testGetEntriesByWithWhere()
    {
        $partners = $this->out->getEntriesBy(['setfree' => 0]);

        self::assertNotNull($partners);
        self::assertCount(1, $partners);
        self::assertEquals(2, $partners[0]->getId());
    }

    /**
     * Tests that getPartnerById() returns the correct partner.
     */
    public function testGetPartnerById()
    {
        $partner = $this->out->getPartnerById(1);

        self::assertNotNull($partner);
        self::assertInstanceOf(PartnerModel::class, $partner);
        self::assertEquals(1, $partner->getId());
        self::assertEquals('ilch', $partner->getName());
        self::assertEquals('https://www.ilch.de/include/images/linkus/88x31.png', $partner->getBanner());
        self::assertEquals('https://ilch.de', $partner->getLink());
        self::assertEquals(0, $partner->getTarget());
        self::assertEquals(1, $partner->getFree());
    }

    /**
     * Tests that getPartnerById() returns a different partner.
     */
    public function testGetPartnerByIdSecond()
    {
        $partner = $this->out->getPartnerById(2);

        self::assertNotNull($partner);
        self::assertEquals(2, $partner->getId());
        self::assertEquals('Example Corp', $partner->getName());
        self::assertEquals(1, $partner->getTarget());
        self::assertEquals(0, $partner->getFree());
    }

    /**
     * Tests that getPartnerById() returns null for a non-existent id.
     */
    public function testGetPartnerByIdNotFound()
    {
        $partner = $this->out->getPartnerById(9999);

        self::assertNull($partner);
    }

    /**
     * Tests that save() inserts a new partner when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new PartnerModel();
        $model->setId(0)
            ->setName('New Partner')
            ->setBanner('https://newpartner.com/banner.png')
            ->setLink('https://newpartner.com')
            ->setTarget(1)
            ->setFree(1);

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);

        $partner = $this->out->getPartnerById($newId);
        self::assertNotNull($partner);
        self::assertEquals('New Partner', $partner->getName());
        self::assertEquals('https://newpartner.com/banner.png', $partner->getBanner());
        self::assertEquals('https://newpartner.com', $partner->getLink());
        self::assertEquals(1, $partner->getTarget());
        self::assertEquals(1, $partner->getFree());
    }

    /**
     * Tests that save() insert increases the total count.
     */
    public function testSaveInsertIncreasesCount()
    {
        $before = $this->out->getEntries();
        self::assertCount(3, $before);

        $model = new PartnerModel();
        $model->setId(0)
            ->setName('Another Partner')
            ->setBanner('https://another.com/banner.png')
            ->setLink('https://another.com')
            ->setTarget(0)
            ->setFree(0);

        $this->out->save($model);

        $after = $this->out->getEntries();
        self::assertCount(4, $after);
    }

    /**
     * Tests that save() updates an existing partner when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new PartnerModel();
        $model->setId(1)
            ->setName('Updated ilch')
            ->setBanner('https://updated-ilch.com/banner.png')
            ->setLink('https://updated-ilch.com')
            ->setTarget(1)
            ->setFree(0);

        $returnedId = $this->out->save($model);

        self::assertEquals(1, $returnedId);

        $partner = $this->out->getPartnerById(1);
        self::assertNotNull($partner);
        self::assertEquals(1, $partner->getId());
        self::assertEquals('Updated ilch', $partner->getName());
        self::assertEquals('https://updated-ilch.com/banner.png', $partner->getBanner());
        self::assertEquals('https://updated-ilch.com', $partner->getLink());
        self::assertEquals(1, $partner->getTarget());
        self::assertEquals(0, $partner->getFree());
    }

    /**
     * Tests that save() update does not affect other partners.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new PartnerModel();
        $model->setId(1)
            ->setName('Changed')
            ->setBanner('https://changed.com/banner.png')
            ->setLink('https://changed.com')
            ->setTarget(1)
            ->setFree(0);

        $this->out->save($model);

        $other = $this->out->getPartnerById(2);
        self::assertNotNull($other);
        self::assertEquals('Example Corp', $other->getName());
        self::assertEquals('https://example.com/banners/example.png', $other->getBanner());
        self::assertEquals('https://example.com', $other->getLink());
        self::assertEquals(1, $other->getTarget());
        self::assertEquals(0, $other->getFree());
    }

    /**
     * Tests that save() update preserves the pos field (not included in update fields).
     */
    public function testSaveUpdatePreservesPosition()
    {
        $model = new PartnerModel();
        $model->setId(1)
            ->setName('Name Only Change')
            ->setBanner('https://www.ilch.de/include/images/linkus/88x31.png')
            ->setLink('https://ilch.de')
            ->setTarget(0)
            ->setFree(1);

        $this->out->save($model);

        // Position should still be 1 (from seed data)
        $partners = $this->out->getPartnersBy(['id' => 1], ['pos' => 'ASC']);
        self::assertCount(1, $partners);
        self::assertEquals(1, $partners[0]->getId());
        self::assertEquals('Name Only Change', $partners[0]->getName());
    }

    /**
     * Tests that updatePositionById() changes the pos value.
     */
    public function testUpdatePositionById()
    {
        $this->out->updatePositionById(1, 10);

        // Verify by ordering and checking position
        $partners = $this->out->getPartnersBy([], ['pos' => 'ASC']);
        self::assertCount(3, $partners);

        // Partner 1 should now be last (pos=10)
        self::assertEquals(1, $partners[2]->getId());
    }

    /**
     * Tests that updatePositionById() reorders partners by pos.
     */
    public function testUpdatePositionByIdReorders()
    {
        // Move partner 3 to position 0 (first)
        $this->out->updatePositionById(3, 0);

        $partners = $this->out->getPartnersBy([], ['pos' => 'ASC']);
        self::assertEquals(3, $partners[0]->getId());
        self::assertEquals(1, $partners[1]->getId());
        self::assertEquals(2, $partners[2]->getId());
    }

    /**
     * Tests that updatePositionById() does not affect other fields.
     */
    public function testUpdatePositionByIdDoesNotChangeOtherFields()
    {
        $this->out->updatePositionById(1, 99);

        $partner = $this->out->getPartnerById(1);
        self::assertNotNull($partner);
        self::assertEquals('ilch', $partner->getName());
        self::assertEquals('https://www.ilch.de/include/images/linkus/88x31.png', $partner->getBanner());
        self::assertEquals('https://ilch.de', $partner->getLink());
    }

    /**
     * Tests that delete() removes a partner.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        self::assertNull($this->out->getPartnerById(1));

        $partners = $this->out->getEntries();
        self::assertCount(2, $partners);
    }

    /**
     * Tests that delete() on a non-existent id does not remove other partners.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $partners = $this->out->getEntries();
        self::assertCount(3, $partners);
    }

    /**
     * Tests that multiple deletes remove all partners.
     */
    public function testDeleteAll()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);

        $partners = $this->out->getEntries();
        self::assertCount(0, $partners);

        self::assertNull($this->out->getEntriesBy());
    }

    /**
     * Tests that getEntries() with target filter works.
     */
    public function testGetEntriesWhereTarget()
    {
        $partners = $this->out->getEntries(['target' => 1]);

        self::assertCount(1, $partners);
        self::assertEquals(2, $partners[0]->getId());
        self::assertEquals('Example Corp', $partners[0]->getName());
    }

    /**
     * Tests that save() after delete re-inserts correctly.
     */
    public function testSaveAfterDelete()
    {
        $this->out->delete(1);
        self::assertCount(2, $this->out->getEntries());

        $model = new PartnerModel();
        $model->setId(0)
            ->setName('Replacement')
            ->setBanner('https://replacement.com/banner.png')
            ->setLink('https://replacement.com')
            ->setTarget(0)
            ->setFree(1);

        $this->out->save($model);

        $partners = $this->out->getEntries();
        self::assertCount(3, $partners);

        self::assertNull($this->out->getPartnerById(1));
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
