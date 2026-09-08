<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Awards\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Awards\Config\Config as ModuleConfig;
use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\Awards\Models\Recipient as RecipientModel;
use InvalidArgumentException;

class AwardsTest extends DatabaseTestCase
{
    /**
     * @var AwardsMapper
     */
    protected $out;
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new AwardsMapper();
    }

    /**
     * Tests that getEntriesBy() returns all awards with recipients.
     */
    public function testGetEntriesBy()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
        self::assertInstanceOf(AwardsModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns correct fields for the first award.
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy();

        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('2024-01-15', $entries[0]->getDate());
        self::assertEquals(1, $entries[0]->getRank());
        self::assertEquals('trophy.png', $entries[0]->getImage());
        self::assertEquals('Annual Competition', $entries[0]->getEvent());
        self::assertEquals('https://example.com/award1', $entries[0]->getUrl());
    }

    /**
     * Tests that getEntriesBy() correctly attaches recipients.
     */
    public function testGetEntriesByRecipients()
    {
        $entries = $this->out->getEntriesBy();

        // Award 1 has 2 recipients
        self::assertCount(2, $entries[0]->getRecipients());
        self::assertEquals(10, $entries[0]->getRecipients()[0]->getUtId());
        self::assertEquals(0, $entries[0]->getRecipients()[0]->getTyp());
        self::assertEquals(11, $entries[0]->getRecipients()[1]->getUtId());
        self::assertEquals(0, $entries[0]->getRecipients()[1]->getTyp());

        // Award 2 has 1 recipient
        self::assertCount(1, $entries[1]->getRecipients());
        self::assertEquals(12, $entries[1]->getRecipients()[0]->getUtId());
        self::assertEquals(1, $entries[1]->getRecipients()[0]->getTyp());
    }

    /**
     * Tests that getEntriesBy() with a WHERE clause filters correctly.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['a.rank' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesBy() returns null when no results match.
     */
    public function testGetEntriesByNoResults()
    {
        $entries = $this->out->getEntriesBy(['a.id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getAwards() returns all awards.
     */
    public function testGetAwards()
    {
        $awards = $this->out->getAwards();

        self::assertCount(2, $awards);
    }

    /**
     * Tests that getAwards() returns empty array when no results.
     */
    public function testGetAwardsEmpty()
    {
        $awards = $this->out->getAwards(['a.id' => 9999]);

        self::assertIsArray($awards);
        self::assertCount(0, $awards);
    }

    /**
     * Tests that getAwardsById() returns the correct award.
     */
    public function testGetAwardsById()
    {
        $award = $this->out->getAwardsById(1);

        self::assertNotNull($award);
        self::assertEquals(1, $award->getId());
        self::assertEquals('Annual Competition', $award->getEvent());
        self::assertCount(2, $award->getRecipients());
    }

    /**
     * Tests that getAwardsById() returns null for a non-existent id.
     */
    public function testGetAwardsByIdNotFound()
    {
        $award = $this->out->getAwardsById(9999);

        self::assertNull($award);
    }

    /**
     * Tests inserting a new award via save(), then adding recipients.
     */
    public function testSaveInsert()
    {
        $model = new AwardsModel();
        $model->setId(0)
            ->setDate('2024-06-01')
            ->setRank(5)
            ->setImage('gold.png')
            ->setEvent('Summer Finals')
            ->setUrl('https://example.com/summer');

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        // Save recipients now that the award row exists.
        $recipient = new RecipientModel();
        $recipient->setAwardId($id)
            ->setUtId(50)
            ->setTyp(0);

        $affected = $this->out->saveRecipientsMulti($id, [$recipient]);
        self::assertEquals(1, $affected);

        $saved = $this->out->getAwardsById($id);
        self::assertNotNull($saved);
        self::assertEquals('Summer Finals', $saved->getEvent());
        self::assertEquals(5, $saved->getRank());
        self::assertCount(1, $saved->getRecipients());
        self::assertEquals(50, $saved->getRecipients()[0]->getUtId());
    }

    /**
     * Tests updating an existing award via save().
     */
    public function testSaveUpdate()
    {
        $model = new AwardsModel();
        $model->setId(1)
            ->setDate('2024-03-01')
            ->setRank(3)
            ->setImage('bronze.png')
            ->setEvent('Updated Event')
            ->setUrl('https://example.com/updated');

        $id = $this->out->save($model);

        self::assertEquals(1, $id);

        $saved = $this->out->getAwardsById(1);
        self::assertNotNull($saved);
        self::assertEquals('Updated Event', $saved->getEvent());
        self::assertEquals(3, $saved->getRank());
        self::assertEquals('bronze.png', $saved->getImage());
    }

    /**
     * Tests that save() replaces recipients on update.
     */
    public function testSaveReplacesRecipients()
    {
        $model = new AwardsModel();
        $model->setId(1)
            ->setDate('2024-01-15')
            ->setRank(1)
            ->setImage('trophy.png')
            ->setEvent('Annual Competition')
            ->setUrl('https://example.com/award1');

        $newRecipient = new RecipientModel();
        $newRecipient->setAwardId(1)
            ->setUtId(99)
            ->setTyp(1);
        $model->setRecipients([$newRecipient]);

        $this->out->save($model);

        $saved = $this->out->getAwardsById(1);
        self::assertCount(1, $saved->getRecipients());
        self::assertEquals(99, $saved->getRecipients()[0]->getUtId());
        self::assertEquals(1, $saved->getRecipients()[0]->getTyp());
    }

    /**
     * Tests that delete() removes an award.
     */
    public function testDelete()
    {
        $result = $this->out->delete(1);

        self::assertTrue($result);
        self::assertNull($this->out->getAwardsById(1));

        // Recipients should be gone via CASCADE
        $remaining = $this->out->getAwards();
        self::assertCount(1, $remaining);
        self::assertEquals(2, $remaining[0]->getId());
    }

    /**
     * Tests that delete() returns false for a non-existent id.
     */
    public function testDeleteNotFound()
    {
        $result = $this->out->delete(9999);

        self::assertFalse($result);
    }

    /**
     * Tests saveRecipientsMulti() with a valid set of recipients.
     */
    public function testSaveRecipientsMulti()
    {
        $recipients = [];
        for ($i = 1; $i <= 3; $i++) {
            $r = new RecipientModel();
            $r->setAwardId(1)
                ->setUtId(100 + $i)
                ->setTyp(0);
            $recipients[] = $r;
        }

        $affected = $this->out->saveRecipientsMulti(1, $recipients);

        self::assertEquals(3, $affected);

        $award = $this->out->getAwardsById(1);
        self::assertCount(3, $award->getRecipients());
    }

    /**
     * Tests saveRecipientsMulti() with an empty array returns 0.
     */
    public function testSaveRecipientsMultiEmpty()
    {
        $affected = $this->out->saveRecipientsMulti(1, []);

        self::assertEquals(0, $affected);
    }

    /**
     * Tests saveRecipientsMulti() throws on more than 1000 recipients.
     */
    public function testSaveRecipientsMultiExceedsLimit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Too many recipients. There is a limit of 1000.");
        $recipients = [];
        for ($i = 0; $i < 1001; $i++) {
            $r = new RecipientModel();
            $r->setAwardId(1)
                ->setUtId($i)
                ->setTyp(0);
            $recipients[] = $r;
        }

        $this->out->saveRecipientsMulti(1, $recipients);
    }

    /**
     * Tests that existsTable() returns true for a known table.
     */
    public function testExistsTableTrue()
    {
        self::assertTrue($this->out->existsTable('awards'));
    }

    /**
     * Tests that existsTable() returns false for a non-existent table.
     */
    public function testExistsTableFalse()
    {
        self::assertFalse($this->out->existsTable('nonexistent_table_xyz'));
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
