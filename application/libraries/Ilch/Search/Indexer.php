<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

/**
 * Contract for module-specific search indexers.
 *
 * An indexer knows how to read one source module and to upsert/delete the
 * corresponding rows in the shared [prefix]_search_documents table (via
 * \Ilch\Search\Store). It is created on demand by \Ilch\Search\Indexers.
 *
 * Modules: deliver the class as
 * Modules\<Key>\Indexers\<Key> in application/modules/<key>/indexers/
 * (the module key decides the class name) and it is discovered
 * automatically — no MAP entry, no registration.
 */
interface Indexer
{
    /**
     * Read the source row(s) and upsert document(s).
     *
     * @param string $docType  module-specific document type
     * @param int $docId       source row id
     * @param string $locale   content locale ('' when the module has none)
     * @return void Missing source row(s) ⇒ the stale document(s) are deleted.
     */
    public function indexDoc(string $docType, int $docId, string $locale = ''): void;

    /**
     * Delete the indexed document(s) for one source row (all locales when
     * $locale is '').
     *
     * @return void
     */
    public function deleteDoc(string $docType, int $docId, string $locale = ''): void;

    /**
     * Reindex every document of one scope (e.g. a forum or a category).
     *
     * @param string $scopeKey  scope type name (e.g. 'forum_id', 'topic_id', 'cat_id')
     * @param int $scopeId      scope id
     * @return void
     */
    public function reindexScope(string $scopeKey, int $scopeId): void;

    /**
     * Delete every document of one scope.
     *
     * @param string $scopeKey  scope type name
     * @param int $scopeId      scope id
     * @return void
     */
    public function deleteScope(string $scopeKey, int $scopeId): void;

    /**
     * Index all source rows with id > $frontier (at most $limit rows).
     *
     * Used by the initial/bulk backfill (search.backfill scheduler task and
     * the importer's final search step). Returns the id of the last processed
     * source row (the new frontier); $frontier itself when no rows were found.
     *
     * @param int $frontier last processed source row id (0 on a fresh install)
     * @param int $limit    maximum number of source rows to process
     * @return int
     */
    public function backfillBatch(int $frontier, int $limit): int;
}
