<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Birthday\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Birthday\Mappers\Birthday as BirthdayMapper;
use Modules\User\Config\Config as UserConfig;

class BirthdayTest extends DatabaseTestCase
{
    /**
     * @var BirthdayMapper
     */
    protected $out;
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new BirthdayMapper();
    }

    /**
     * Tests that getEntriesBy() returns all users.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(4, $entries);
    }

    /**
     * Tests that getEntriesBy() returns null when no rows match.
     */
    public function testGetEntriesByNoMatch()
    {
        $entries = $this->out->getEntriesBy(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesBy() filters by a WHERE clause.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
    }

    /**
     * Tests that getBirthdayUserList() returns users whose birthday is today.
     *
     * Seeded: user 1 has birthday 09-09 → matches CURDATE() (2026-09-09).
     * Users 2, 3, 4 have different month/day → must be excluded.
     */
    public function testGetBirthdayUserList()
    {
        $users = $this->out->getBirthdayUserList();

        self::assertIsArray($users);
        self::assertCount(1, $users);
        self::assertEquals(1, $users[0]->getId());
    }

    /**
     * Tests that getBirthdayUserList() respects the $limit parameter.
     */
    public function testGetBirthdayUserListWithLimit()
    {
        $users = $this->out->getBirthdayUserList(1);

        self::assertIsArray($users);
        self::assertCount(1, $users);
    }

    /**
     * Tests that getEntriesForJson() returns users within a normal (non-wrapping) range.
     *
     * Range: Jan 1 (day 1) → Jun 1 (day 152)
     * Matches: Dave (day 10), Bob (day 135)
     * Excluded: Carol (day 359), Alice (day 252)
     */
    public function testGetEntriesForJsonInRange()
    {
        $users = $this->out->getEntriesForJson('2026-01-01', '2026-06-01');

        self::assertNotNull($users);
        self::assertCount(2, $users);

        $ids = array_map(fn($u) => $u->getId(), $users);
        self::assertContains(2, $ids); // Bob
        self::assertContains(4, $ids); // Dave
        self::assertNotContains(1, $ids); // Alice
        self::assertNotContains(3, $ids); // Carol
    }

    /**
     * Tests that getEntriesForJson() handles a year-wrapping range (OR condition).
     *
     * Range: Dec 1 (day 335) → Feb 1 (day 32)  — wraps past Jan 1
     * Matches: Carol (day 359), Dave (day 10)
     * Excluded: Alice (day 252), Bob (day 135)
     */
    public function testGetEntriesForJsonYearWrap()
    {
        $users = $this->out->getEntriesForJson('2026-12-01', '2027-02-01');

        self::assertNotNull($users);
        self::assertCount(2, $users);

        $ids = array_map(fn($u) => $u->getId(), $users);
        self::assertContains(3, $ids); // Carol
        self::assertContains(4, $ids); // Dave
        self::assertNotContains(1, $ids); // Alice
        self::assertNotContains(2, $ids); // Bob
    }

    /**
     * Tests that getEntriesForJson() returns null when start or end is empty.
     */
    public function testGetEntriesForJsonEmptyStart()
    {
        self::assertNull($this->out->getEntriesForJson('', '2026-06-01'));
    }

    public function testGetEntriesForJsonEmptyEnd()
    {
        self::assertNull($this->out->getEntriesForJson('2026-01-01', ''));
    }

    /**
     * Tests that getEntriesForJson() returns an empty array when no users match.
     */
    public function testGetEntriesForJsonNoMatch()
    {
        // Narrow range that excludes all seeded birthdays.
        // Jul 1 (day 182) → Jul 31 (day 212): no one has a birthday in July.
        $users = $this->out->getEntriesForJson('2026-07-01', '2026-07-31');

        self::assertIsArray($users);
        self::assertCount(0, $users);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $userConfig = new UserConfig();
        $adminConfig = new \Modules\Admin\Config\Config();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql();
    }
}
