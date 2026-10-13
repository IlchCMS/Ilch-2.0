<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\War\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\War\Models\Accept as AcceptModel;

class AcceptTest extends DatabaseTestCase
{
    /**
     * @var Accept
     */
    protected Accept $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new Accept();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all entries ordered by id DESC.
     */
    public function testGetEntriesByReturnsAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
        self::assertInstanceOf(AcceptModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns correct fields (DESC order: id 2 first).
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy();

        self::assertSame(2, $entries[0]->getId());
        self::assertSame(1, $entries[0]->getWarId());
        self::assertSame(2, $entries[0]->getUserId());
        self::assertSame(2, $entries[0]->getAccept());
        self::assertSame('', $entries[0]->getComment());
        self::assertSame('2021-05-09 08:10:38', $entries[0]->getDateCreated());

        self::assertSame(1, $entries[1]->getId());
        self::assertSame(1, $entries[1]->getUserId());
        self::assertSame(1, $entries[1]->getAccept());
    }

    /**
     * Tests that getEntriesBy() filters by where conditions.
     */
    public function testGetEntriesByWhere()
    {
        $entries = $this->out->getEntriesBy(['user_id' => 1]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertSame(1, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesBy() supports a custom order.
     */
    public function testGetEntriesByOrderAsc()
    {
        $entries = $this->out->getEntriesBy([], ['id' => 'ASC']);

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
        self::assertSame(1, $entries[0]->getId());
        self::assertSame(2, $entries[1]->getId());
    }

    /**
     * Tests that getEntriesBy() returns null when no entries match.
     */
    public function testGetEntriesByNoMatch()
    {
        self::assertNull($this->out->getEntriesBy(['war_id' => 9999]));
    }

    /**
     * Tests that getEntriesBy() returns null when no entries exist.
     */
    public function testGetEntriesByEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        self::assertNull($this->out->getEntriesBy());
    }

    /**
     * Tests that getEntriesBy() limits results when a pagination is given (page 1).
     */
    public function testGetEntriesByWithPagination()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(1);

        $entries = $this->out->getEntriesBy([], ['id' => 'ASC'], $pagination);

        self::assertIsArray($entries);
        self::assertCount(1, $entries);
        self::assertSame(1, $entries[0]->getId());

        // The mapper must have stored the total found rows via setRows().
        self::assertSame(2, $pagination->getRows());
    }

    /**
     * Tests that getEntriesBy() applies the pagination offset (page 2).
     */
    public function testGetEntriesByWithPaginationSecondPage()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(1);
        $pagination->setPage(2);

        $entries = $this->out->getEntriesBy([], ['id' => 'ASC'], $pagination);

        self::assertIsArray($entries);
        self::assertCount(1, $entries);
        self::assertSame(2, $entries[0]->getId());

        self::assertSame(2, $pagination->getRows());
    }


    /**
     * Tests that getAcceptByWhere() returns all entries without a where condition.
     */
    public function testGetAcceptByWhereAll()
    {
        $entries = $this->out->getAcceptByWhere();

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
    }

    /**
     * Tests that getAcceptByWhere() filters correctly.
     */
    public function testGetAcceptByWhereMatch()
    {
        $entries = $this->out->getAcceptByWhere(['accept' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertSame(2, $entries[0]->getId());
        self::assertSame(2, $entries[0]->getUserId());
    }

    /**
     * Tests that getAcceptByWhere() returns null when no entries match.
     */
    public function testGetAcceptByWhereNoMatch()
    {
        self::assertNull($this->out->getAcceptByWhere(['war_id' => 9999]));
    }

    /**
     * Tests that getAcceptListByGroupId() returns the group members with their accept data.
     *
     * Group 3 contains users 1 and 2, both of which accepted war 1.
     */
    public function testGetAcceptListByGroupId()
    {
        $entries = $this->out->getAcceptListByGroupId(3, 1);

        self::assertIsArray($entries);
        self::assertCount(2, $entries);

        // Rows are ordered by user_id ASC, but matching by user id is more explicit.
        $byUserId = [];
        foreach ($entries as $entry) {
            $byUserId[$entry->getUserId()] = $entry;
        }

        self::assertArrayHasKey(1, $byUserId);
        self::assertArrayHasKey(2, $byUserId);

        self::assertSame(1, $byUserId[1]->getWarId());
        self::assertSame(1, $byUserId[1]->getAccept());
        self::assertSame('2021-05-09 08:10:38', $byUserId[1]->getDateCreated());
        self::assertSame(2, $byUserId[2]->getAccept());
    }

    /**
     * Tests that getAcceptListByGroupId() returns an empty array (not null) when nothing matches.
     *
     * Group 2 has no members in users_groups.
     */
    public function testGetAcceptListByGroupIdWithoutMembers()
    {
        $entries = $this->out->getAcceptListByGroupId(2, 1);

        self::assertIsArray($entries);
        self::assertCount(0, $entries);
    }

    /**
     * Tests that getAcceptListByGroupId() includes members without an accept entry.
     *
     * Group 1 contains only user 1 and there is no accept entry for war 2.
     * The war_id filter is part of the LEFT JOIN condition, so user 1 is
     * still returned.
     *
     * Note: the Accept model uses non-nullable int defaults (0) and an
     * isset()-based setByArray(), so a missing accept row maps to the model
     * defaults (0 / '') instead of null.
     */
    public function testGetAcceptListByGroupIdIncludesMembersWithoutAccept()
    {
        $entries = $this->out->getAcceptListByGroupId(1, 2);

        self::assertIsArray($entries);
        self::assertCount(1, $entries);
        self::assertSame(1, $entries[0]->getUserId());

        self::assertSame(0, $entries[0]->getAccept());
        self::assertSame(0, $entries[0]->getId());
        self::assertSame('', $entries[0]->getComment());
        self::assertSame('', $entries[0]->getDateCreated());
    }

    /**
     * Tests inserting a new entry via save() (id 0 => insert).
     */
    public function testSaveInsert()
    {
        $model = new AcceptModel();
        $model->setWarId(2)
            ->setUserId(1)
            ->setAccept(1)
            ->setComment('I will join')
            ->setDateCreated('2021-05-09 09:10:38');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(2, $newId);

        $entries = $this->out->getEntriesBy(['id' => $newId]);
        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertSame(2, $entries[0]->getWarId());
        self::assertSame(1, $entries[0]->getUserId());
        self::assertSame(1, $entries[0]->getAccept());
        self::assertSame('I will join', $entries[0]->getComment());
    }

    /**
     * Tests that an insert does not affect existing entries.
     */
    public function testSaveInsertDoesNotAffectOthers()
    {
        $model = new AcceptModel();
        $model->setWarId(2)
            ->setUserId(2)
            ->setAccept(2)
            ->setComment('')
            ->setDateCreated('2021-05-09 09:10:38');

        $this->out->save($model);

        $entries = $this->out->getEntriesBy();
        self::assertCount(3, $entries);

        $existing = $this->out->getAcceptByWhere(['id' => 1]);
        self::assertNotNull($existing);
        self::assertSame(1, $existing[0]->getAccept());
        self::assertSame('2021-05-09 08:10:38', $existing[0]->getDateCreated());
    }

    /**
     * Tests updating an existing entry via save() and the returned id.
     */
    public function testSaveUpdate()
    {
        $model = new AcceptModel();
        $model->setId(1)
            ->setWarId(1)
            ->setUserId(1)
            ->setAccept(2)
            ->setComment('Changed my mind')
            ->setDateCreated('2021-05-09 10:10:38');

        $result = $this->out->save($model);

        self::assertSame(1, $result);

        $entries = $this->out->getAcceptByWhere(['id' => 1]);
        self::assertNotNull($entries);
        self::assertSame(2, $entries[0]->getAccept());
        self::assertSame('Changed my mind', $entries[0]->getComment());
        self::assertSame('2021-05-09 10:10:38', $entries[0]->getDateCreated());
    }

    /**
     * Tests that update does not affect other entries.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new AcceptModel();
        $model->setId(1)
            ->setWarId(1)
            ->setUserId(1)
            ->setAccept(2)
            ->setComment('Changed')
            ->setDateCreated('2021-05-09 10:10:38');

        $this->out->save($model);

        $other = $this->out->getAcceptByWhere(['id' => 2]);
        self::assertNotNull($other);
        self::assertSame(2, $other[0]->getUserId());
        self::assertSame(2, $other[0]->getAccept());
        self::assertSame('', $other[0]->getComment());
    }

    /**
     * Tests that delete() removes an entry by id.
     */
    public function testDelete()
    {
        self::assertTrue($this->out->delete(1));

        self::assertNull($this->out->getAcceptByWhere(['id' => 1]));

        $entries = $this->out->getEntriesBy();
        self::assertCount(1, $entries);
        self::assertSame(2, $entries[0]->getId());
    }

    /**
     * Tests that delete() also accepts a model instance.
     */
    public function testDeleteWithModel()
    {
        $model = new AcceptModel();
        $model->setId(2);

        self::assertTrue($this->out->delete($model));

        self::assertNull($this->out->getAcceptByWhere(['id' => 2]));

        $entries = $this->out->getEntriesBy();
        self::assertCount(1, $entries);
        self::assertSame(1, $entries[0]->getId());
    }

    /**
     * Tests that delete() on a non-existent id returns false and does not throw.
     */
    public function testDeleteNotFound()
    {
        self::assertFalse($this->out->delete(9999));

        $entries = $this->out->getEntriesBy();
        self::assertCount(2, $entries);
    }

    /**
     * Tests that getJson() returns all entries as JSON.
     *
     * The mapper uses getArray(false), so the id key is intentionally omitted.
     * Default order is id DESC, so the entry with id 2 (user 2) comes first.
     */
    public function testGetJson()
    {
        $decoded = json_decode($this->out->getJson(), true);

        self::assertIsArray($decoded);
        self::assertCount(2, $decoded);

        self::assertSame(
            [
                'war_id' => 1,
                'user_id' => 2,
                'accept' => 2,
                'comment' => '',
                'date_created' => '2021-05-09 08:10:38',
            ],
            $decoded[0]
        );
        self::assertArrayNotHasKey('id', $decoded[0]);

        self::assertSame(
            [
                'war_id' => 1,
                'user_id' => 1,
                'accept' => 1,
                'comment' => '',
                'date_created' => '2021-05-09 08:10:38',
            ],
            $decoded[1]
        );
    }

    /**
     * Tests that getJson() returns an empty JSON array when no entries exist.
     */
    public function testGetJsonEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);

        self::assertSame([], json_decode($this->out->getJson(), true));
    }

    /**
     * Tests that getJson() accepts and applies the json_encode options.
     */
    public function testGetJsonWithOptions()
    {
        $decoded = json_decode($this->out->getJson(JSON_PRETTY_PRINT), true);

        self::assertIsArray($decoded);
        self::assertCount(2, $decoded);
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
