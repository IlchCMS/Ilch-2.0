<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Awards\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Awards\Config\Config as ModuleConfig;
use Modules\Awards\Mappers\Recipients as RecipientsMapper;
use Modules\Awards\Models\Recipient as RecipientModel;
use InvalidArgumentException;

class RecipientsTest extends DatabaseTestCase
{
    /**
     * @var RecipientsMapper
     */
    protected Recipients $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new RecipientsMapper();
    }

    /**
     * Tests that getRecipients() returns all recipients from the seed data.
     */
    public function testGetRecipients()
    {
        $recipients = $this->out->getRecipients();

        self::assertIsArray($recipients);
        self::assertCount(3, $recipients);
        self::assertInstanceOf(RecipientModel::class, $recipients[0]);
    }

    /**
     * Tests that getRecipients() populates model fields correctly.
     */
    public function testGetRecipientsFields()
    {
        $recipients = $this->out->getRecipients();

        self::assertEquals(1, $recipients[0]->getAwardId());
        self::assertEquals(10, $recipients[0]->getUtId());
        self::assertEquals(0, $recipients[0]->getTyp());

        self::assertEquals(1, $recipients[1]->getAwardId());
        self::assertEquals(11, $recipients[1]->getUtId());
        self::assertEquals(0, $recipients[1]->getTyp());

        self::assertEquals(2, $recipients[2]->getAwardId());
        self::assertEquals(12, $recipients[2]->getUtId());
        self::assertEquals(1, $recipients[2]->getTyp());
    }

    /**
     * Tests that getRecipients() filters by award_id.
     */
    public function testGetRecipientsWithWhere()
    {
        $recipients = $this->out->getRecipients(['award_id' => 2]);

        self::assertCount(1, $recipients);
        self::assertEquals(2, $recipients[0]->getAwardId());
        self::assertEquals(12, $recipients[0]->getUtId());
        self::assertEquals(1, $recipients[0]->getTyp());
    }

    /**
     * Tests that getRecipients() returns an empty array when no rows match.
     */
    public function testGetRecipientsEmpty()
    {
        $recipients = $this->out->getRecipients(['award_id' => 9999]);

        self::assertIsArray($recipients);
        self::assertCount(0, $recipients);
    }

    /**
     * Tests that saveMulti() inserts recipients for an award that has none yet.
     */
    public function testSaveMultiInsert()
    {
        // Award 3 exists in the seed but has no recipients.
        $recipients = [];
        for ($i = 1; $i <= 3; $i++) {
            $r = new RecipientModel();
            $r->setAwardId(3)
                ->setUtId(200 + $i)
                ->setTyp(0);
            $recipients[] = $r;
        }

        $affected = $this->out->saveMulti($recipients);

        self::assertEquals(3, $affected);

        $saved = $this->out->getRecipients(['award_id' => 3]);
        self::assertCount(3, $saved);
        self::assertEquals(201, $saved[0]->getUtId());
        self::assertEquals(203, $saved[2]->getUtId());
    }

    /**
     * Tests that saveMulti() replaces existing recipients for the same award_id.
     */
    public function testSaveMultiReplaces()
    {
        // Seed data has 2 recipients for award_id=1 (ut_id 10 and 11).
        $newRecipients = [];
        for ($i = 1; $i <= 2; $i++) {
            $r = new RecipientModel();
            $r->setAwardId(1)
                ->setUtId(500 + $i)
                ->setTyp(1);
            $newRecipients[] = $r;
        }

        $affected = $this->out->saveMulti($newRecipients);

        self::assertEquals(2, $affected);

        // Old recipients (10, 11) should be gone; only new ones remain.
        $saved = $this->out->getRecipients(['award_id' => 1]);
        self::assertCount(2, $saved);
        self::assertEquals(501, $saved[0]->getUtId());
        self::assertEquals(502, $saved[1]->getUtId());
        self::assertEquals(1, $saved[0]->getTyp());
    }

    /**
     * Tests that saveMulti() only affects recipients of the target award_id.
     */
    public function testSaveMultiPreservesOtherAwards()
    {
        $newRecipients = [];
        for ($i = 1; $i <= 2; $i++) {
            $r = new RecipientModel();
            $r->setAwardId(1)
                ->setUtId(600 + $i)
                ->setTyp(0);
            $newRecipients[] = $r;
        }

        $this->out->saveMulti($newRecipients);

        // Award 2's recipient should be untouched.
        $award2 = $this->out->getRecipients(['award_id' => 2]);
        self::assertCount(1, $award2);
        self::assertEquals(12, $award2[0]->getUtId());
        self::assertEquals(1, $award2[0]->getTyp());
    }

    /**
     * Tests that saveMulti() throws when exceeding the 1000-recipient limit.
     */
    public function testSaveMultiExceedsLimit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Too many recipients. There is a limit of 1000.');

        $recipients = [];
        for ($i = 0; $i < 1001; $i++) {
            $r = new RecipientModel();
            $r->setAwardId(1)
                ->setUtId($i)
                ->setTyp(0);
            $recipients[] = $r;
        }

        $this->out->saveMulti($recipients);
    }

    /**
     * Tests that saveMulti() returns 0 for an empty array without error.
     */
    public function testSaveMultiEmptyArray()
    {
        $affected = $this->out->saveMulti([]);

        self::assertEquals(0, $affected);
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
