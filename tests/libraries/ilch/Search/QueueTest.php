<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Search;

use Modules\Fixturemod\Indexers\Fixturemod;

// The base class lives in this test directory and is not autoloadable
// (same pattern as TestView in PaginationTest).
require_once __DIR__ . '/SearchFixtureTestCase.php';

/**
 * Tests for the queue dispatch of a convention module (fixturemod):
 * enqueue() → inline flush() → Indexers::get() → the indexer's methods.
 */
class QueueTest extends SearchFixtureTestCase
{
    private Queue $queue;
    private Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queue = new Queue($this->db);
        $this->store = new Store($this->db);
    }

    /**
     * Tests that enqueuing a convention module dispatches through the inline
     * flush: the document is immediately searchable and the queue is empty.
     */
    public function testEnqueueDispatchesConventionModule()
    {
        Queue::enqueue(self::FIXTURE_MODULE_KEY, Queue::OP_INDEX, 'entry', 7);

        $docs = $this->store->getDocsByIds(self::FIXTURE_MODULE_KEY, 'entry', [7]);
        self::assertCount(1, $docs);
        self::assertSame('Fixture entry 7', $docs[7]['title']);
        self::assertSame('Fixture body 7', $docs[7]['body']);
        self::assertSame('3', $docs[7]['access_groups']);
        self::assertNull($docs[7]['meta']);
        self::assertSame(0, $this->queue->count());
        self::assertCount(1, Fixturemod::$calls);
    }

    /**
     * Tests that OP_INDEX followed by OP_DELETE leaves no document behind.
     */
    public function testEnqueueIndexThenDelete()
    {
        Queue::enqueue(self::FIXTURE_MODULE_KEY, Queue::OP_INDEX, 'entry', 8);
        Queue::enqueue(self::FIXTURE_MODULE_KEY, Queue::OP_DELETE, 'entry', 8);

        self::assertSame([], $this->store->getDocsByIds(self::FIXTURE_MODULE_KEY, 'entry', [8]));
        self::assertSame(0, $this->queue->count());
    }

    /**
     * Tests that an op for a module without an indexer is discarded
     * (poison-row prevention): the queue ends up empty.
     */
    public function testUnknownModuleOpsAreDiscarded()
    {
        Queue::enqueue('unknownmodule', Queue::OP_INDEX, 'entry', 1);

        self::assertSame(0, $this->queue->count());
        self::assertArrayNotHasKey('unknownmodule', $this->store->countDocumentsByModule());
    }

    /**
     * Tests that a failing op stays queued (the scheduler retry picks it up
     * later), is reported in the admin logs (one row: the two failed
     * dispatches coalesce via the Logs mapper's per-minute dedup), and that
     * re-enqueuing the same op coalesces on the unique key (one row, no
     * duplicate dispatch).
     */
    public function testFailedOpStaysQueuedAndCoalesces()
    {
        Fixturemod::$failOn[] = 9;

        Queue::enqueue(self::FIXTURE_MODULE_KEY, Queue::OP_INDEX, 'entry', 9);
        self::assertSame(1, $this->queue->count());

        Queue::enqueue(self::FIXTURE_MODULE_KEY, Queue::OP_INDEX, 'entry', 9);
        self::assertSame(1, $this->queue->count());
        self::assertSame([], $this->store->getDocsByIds(self::FIXTURE_MODULE_KEY, 'entry', [9]));

        $logs = $this->db->queryList('SELECT info FROM [prefix]_logs');
        self::assertCount(1, $logs);
        self::assertSame(
            'Ilch Search queue: op 1 failed for module=fixturemod doc_type=entry doc_id=9 locale= scope=/0: Fixture index failure for doc id 9',
            $logs[0]
        );
    }

    /**
     * Tests that flush() dispatches queued ops oldest-first (a scope delete
     * and an index) and deletes the processed rows.
     */
    public function testFlushDispatchesQueuedOps()
    {
        $this->db->query(
            'INSERT INTO [prefix]_search_queue (module, op, doc_type, doc_id, locale, scope_key, scope_id, created) VALUES ('
            . $this->db->escape(self::FIXTURE_MODULE_KEY, true) . ', ' . Queue::OP_DELETE_SCOPE . ", '', 0, '', 'cat_id', 5, NOW())"
        );
        $this->db->query(
            'INSERT INTO [prefix]_search_queue (module, op, doc_type, doc_id, locale, scope_key, scope_id, created) VALUES ('
            . $this->db->escape(self::FIXTURE_MODULE_KEY, true) . ', ' . Queue::OP_INDEX . ", 'entry', 12, '', '', 0, NOW())"
        );

        self::assertSame(2, $this->queue->flush());
        self::assertSame(0, $this->queue->count());

        $docs = $this->store->getDocsByIds(self::FIXTURE_MODULE_KEY, 'entry', [12]);
        self::assertSame('Fixture entry 12', $docs[12]['title']);

        $calls = Fixturemod::$calls;
        self::assertSame('deleteScope', $calls[0]['op']);
        self::assertSame('indexDoc', $calls[1]['op']);
    }

    /**
     * Tests that import mode makes enqueue() a pure no-op: no queue row, no
     * dispatch (bulk imports must run at full speed).
     */
    public function testImportModeIsANoOp()
    {
        Queue::setImportMode(true);
        try {
            Queue::enqueue(self::FIXTURE_MODULE_KEY, Queue::OP_INDEX, 'entry', 13);

            self::assertSame(0, $this->queue->count());
            self::assertSame([], $this->store->getDocsByIds(self::FIXTURE_MODULE_KEY, 'entry', [13]));
            self::assertSame([], Fixturemod::$calls);
        } finally {
            Queue::setImportMode(false);
        }
    }

    /**
     * Tests that OP_ENSURE_SCHEMA is never processed by the inline flush
     * (only the search.drain task with a scheduler may run the DDL).
     */
    public function testEnsureSchemaIsNotProcessedByInlineFlush()
    {
        Queue::enqueue('search', Queue::OP_ENSURE_SCHEMA);

        self::assertSame(1, $this->queue->count());
    }
}
