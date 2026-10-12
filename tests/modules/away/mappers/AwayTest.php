<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Away\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Away\Config\Config as ModuleConfig;
use Modules\Away\Mappers\Away as AwayMapper;
use Modules\Away\Models\Away as AwayModel;
use Modules\User\Config\Config as UserConfig;

class AwayTest extends DatabaseTestCase
{
    /**
     * @var AwayMapper
     */
    protected AwayMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new AwayMapper();
    }

    /**
     * Tests that getAway() returns all entries ordered by start ASC.
     */
    public function testGetAway()
    {
        $aways = $this->out->getAway();

        self::assertIsArray($aways);
        self::assertCount(2, $aways);
        self::assertInstanceOf(AwayModel::class, $aways[0]);

        self::assertEquals(1, $aways[0]->getId());
        self::assertEquals(2, $aways[1]->getId());
    }

    /**
     * Tests that getAway() returns correct fields for the first entry.
     */
    public function testGetAwayFields()
    {
        $aways = $this->out->getAway();

        self::assertEquals(1, $aways[0]->getId());
        self::assertEquals(1, $aways[0]->getUserId());
        self::assertEquals('Vacation', $aways[0]->getReason());
        self::assertEquals('2025-03-01', $aways[0]->getStart());
        self::assertEquals('2025-03-15', $aways[0]->getEnd());
        self::assertEquals('On holiday', $aways[0]->getText());
        self::assertEquals(2, $aways[0]->getStatus());
        self::assertEquals(1, $aways[0]->getShow());
    }

    /**
     * Tests that getAway() returns correct fields for the second entry.
     */
    public function testGetAwaySecond()
    {
        $aways = $this->out->getAway();

        self::assertEquals(2, $aways[1]->getId());
        self::assertEquals(2, $aways[1]->getUserId());
        self::assertEquals('Sick leave', $aways[1]->getReason());
        self::assertEquals('2025-04-01', $aways[1]->getStart());
        self::assertEquals('2025-04-02', $aways[1]->getEnd());
        self::assertEquals('Out sick', $aways[1]->getText());
        self::assertEquals(1, $aways[1]->getStatus());
        self::assertEquals(0, $aways[1]->getShow());
    }

    /**
     * Tests that getAway() respects the where conditions.
     */
    public function testGetAwayWithWhere()
    {
        $aways = $this->out->getAway(['status' => 1]);

        self::assertCount(1, $aways);
        self::assertEquals(2, $aways[0]->getId());
    }

    /**
     * Tests that getAway() returns an empty array when no entries exist.
     */
    public function testGetAwayEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        $aways = $this->out->getAway();

        self::assertIsArray($aways);
        self::assertEmpty($aways);
    }

    /**
     * Tests that getAwayById() returns the correct entry.
     */
    public function testGetAwayById()
    {
        $away = $this->out->getAwayById(1);

        self::assertInstanceOf(AwayModel::class, $away);
        self::assertEquals(1, $away->getId());
        self::assertEquals(1, $away->getUserId());
        self::assertEquals('Vacation', $away->getReason());
        self::assertEquals('2025-03-01', $away->getStart());
        self::assertEquals('2025-03-15', $away->getEnd());
        self::assertEquals('On holiday', $away->getText());
        self::assertEquals(2, $away->getStatus());
        self::assertEquals(1, $away->getShow());
    }

    /**
     * Tests that getAwayById() returns null for a non-existent id.
     *
     * NOTE: This test FAILS with a TypeError as long as the bug in
     * getAwayById() is not fixed (see "Issues found" in the report).
     */
    public function testGetAwayByIdNotFound()
    {
        $away = $this->out->getAwayById(9999);

        self::assertNull($away);
    }

    /**
     * Tests inserting a new entry via save().
     */
    public function testSaveInsert()
    {
        $model = new AwayModel();
        $model->setId(0)
            ->setUserId(3)
            ->setReason('Conference')
            ->setStart('2025-05-01')
            ->setEnd('2025-05-02')
            ->setText('At a conference')
            ->setStatus(2)
            ->setShow(1);

        $this->out->save($model);

        $aways = $this->out->getAway();
        self::assertCount(3, $aways);

        // The new entry should be the last one (latest start / auto-increment id)
        $new = $aways[2];
        self::assertGreaterThan(2, $new->getId());
        self::assertEquals(3, $new->getUserId());
        self::assertEquals('Conference', $new->getReason());
        self::assertEquals('2025-05-01', $new->getStart());
        self::assertEquals('2025-05-02', $new->getEnd());
        self::assertEquals('At a conference', $new->getText());
        self::assertEquals(2, $new->getStatus());
        self::assertEquals(1, $new->getShow());
    }

    /**
     * Tests that saving an entry with status 0 falls back to the DB default.
     *
     * Documents current (buggy) behaviour: a falsy status (0 = declined) is
     * skipped in save(), so the column default (2 = reported) is applied.
     */
    public function testSaveInsertStatusZeroUsesDbDefault()
    {
        $model = new AwayModel();
        $model->setId(0)
            ->setUserId(3)
            ->setReason('Declined entry')
            ->setStart('2025-06-01')
            ->setEnd('2025-06-02')
            ->setText('Should be declined')
            ->setStatus(0);

        $this->out->save($model);

        $aways = $this->out->getAway();
        $new = $aways[2];

        self::assertEquals(2, $new->getStatus());
    }

    /**
     * Tests updating an existing entry via save().
     */
    public function testSaveUpdate()
    {
        $model = new AwayModel();
        $model->setId(1)
            ->setUserId(1)
            ->setReason('Extended vacation')
            ->setStart('2025-03-01')
            ->setEnd('2025-03-31')
            ->setText('Extended')
            ->setStatus(1)
            ->setShow(1);

        $this->out->save($model);

        $away = $this->out->getAwayById(1);
        self::assertInstanceOf(AwayModel::class, $away);
        self::assertEquals(1, $away->getId());
        self::assertEquals(1, $away->getUserId());
        self::assertEquals('Extended vacation', $away->getReason());
        self::assertEquals('2025-03-01', $away->getStart());
        self::assertEquals('2025-03-31', $away->getEnd());
        self::assertEquals('Extended', $away->getText());
        self::assertEquals(1, $away->getStatus());
        self::assertEquals(1, $away->getShow());
    }

    /**
     * Tests that update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new AwayModel();
        $model->setId(1)
            ->setUserId(1)
            ->setReason('Changed')
            ->setStart('2025-03-01')
            ->setEnd('2025-03-15')
            ->setText('Changed text')
            ->setStatus(1)
            ->setShow(1);

        $this->out->save($model);

        $other = $this->out->getAwayById(2);
        self::assertEquals(2, $other->getId());
        self::assertEquals(2, $other->getUserId());
        self::assertEquals('Sick leave', $other->getReason());
        self::assertEquals('2025-04-01', $other->getStart());
        self::assertEquals('2025-04-02', $other->getEnd());
        self::assertEquals('Out sick', $other->getText());
        self::assertEquals(1, $other->getStatus());
        self::assertEquals(0, $other->getShow());
    }

    /**
     * Tests that updating an entry with status 0 keeps the old status.
     *
     * Documents current (buggy) behaviour: a falsy status is skipped on update
     * as well, so "declined" (0) can never be stored through save().
     */
    public function testSaveUpdateStatusZeroIsIgnored()
    {
        $model = new AwayModel();
        $model->setId(2)
            ->setUserId(2)
            ->setReason('Sick leave')
            ->setStart('2025-04-01')
            ->setEnd('2025-04-02')
            ->setText('Out sick')
            ->setStatus(0)
            ->setShow(0);

        $this->out->save($model);

        $away = $this->out->getAwayById(2);

        self::assertEquals(1, $away->getStatus());
    }

    /**
     * Tests that delete() removes an entry.
     */
    public function testDelete()
    {
        $this->out->delete(1);

        $aways = $this->out->getAway(['id' => 1]);
        self::assertEmpty($aways);

        // Remaining entry should still be present
        $aways = $this->out->getAway();
        self::assertCount(1, $aways);
        self::assertEquals(2, $aways[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $aways = $this->out->getAway();
        self::assertCount(2, $aways);
    }

    /**
     * Tests that update() toggles a reported (2) entry to approved (1).
     */
    public function testUpdateReportedToApproved()
    {
        // Entry 1 has status 2 (reported)
        $this->out->update(1);

        $away = $this->out->getAwayById(1);
        self::assertEquals(1, $away->getStatus());
    }

    /**
     * Tests that update() toggles an approved (1) entry to declined (0).
     */
    public function testUpdateApprovedToDeclined()
    {
        // Entry 2 has status 1 (approved)
        $this->out->update(2);

        $away = $this->out->getAwayById(2);
        self::assertEquals(0, $away->getStatus());
    }

    /**
     * Tests that update() toggles a declined (0) entry back to approved (1).
     */
    public function testUpdateDeclinedToApproved()
    {
        $this->out->update(2); // 1 -> 0
        $this->out->update(2); // 0 -> 1

        $away = $this->out->getAwayById(2);
        self::assertEquals(1, $away->getStatus());
    }

    /**
     * Tests that update() on a non-existent id does not throw.
     */
    public function testUpdateNotFound()
    {
        $this->out->update(9999);

        $aways = $this->out->getAway();
        self::assertCount(2, $aways);
    }

    /**
     * Tests that getEntriesForJson() returns visible entries fully inside the range.
     */
    public function testGetEntriesForJson()
    {
        $entries = $this->out->getEntriesForJson('2025-03-01', '2025-03-31');

        self::assertIsArray($entries);
        self::assertCount(1, $entries);
        self::assertInstanceOf(AwayModel::class, $entries[0]);

        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('Vacation', $entries[0]->getReason());
        self::assertEquals('2025-03-01', $entries[0]->getStart());
        self::assertEquals('2025-03-15', $entries[0]->getEnd());
        self::assertEquals(2, $entries[0]->getStatus());
        self::assertEquals(1, $entries[0]->getShow());
    }

    /**
     * Tests that getEntriesForJson() excludes hidden entries (show = 0).
     */
    public function testGetEntriesForJsonExcludesHidden()
    {
        // Entry 2 lies fully inside the range but has show = 0
        $entries = $this->out->getEntriesForJson('2025-04-01', '2025-04-02');

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesForJson() excludes entries not fully inside the range.
     */
    public function testGetEntriesForJsonExcludesPartiallyContained()
    {
        // Entry 1 ends on 2025-03-15, after the range end 2025-03-10
        $entries = $this->out->getEntriesForJson('2025-03-01', '2025-03-10');

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesForJson() returns null when the range matches nothing.
     */
    public function testGetEntriesForJsonNoMatch()
    {
        $entries = $this->out->getEntriesForJson('2020-01-01', '2020-12-31');

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesForJson() returns null for empty dates.
     */
    public function testGetEntriesForJsonEmptyDates()
    {
        self::assertNull($this->out->getEntriesForJson('', ''));
    }

    /**
     * Tests that existsTable() returns true for existing tables.
     */
    public function testExistsTableTrue()
    {
        self::assertTrue($this->out->existsTable('away'));
        self::assertTrue($this->out->existsTable('away_groups'));
    }

    /**
     * Tests that existsTable() returns false for a non-existent table.
     */
    public function testExistsTableFalse()
    {
        self::assertFalse($this->out->existsTable('not_a_table'));
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
