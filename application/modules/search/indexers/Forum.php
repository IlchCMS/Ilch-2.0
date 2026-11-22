<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\Indexers;

use Ilch\Database\Mysql;
use Ilch\Registry;
use Ilch\Search\Indexer;
use Ilch\Search\Store;

/**
 * Search indexer for the forum module.
 *
 * Document model:
 * - topic: one row per topic (locale ''), title = topic title, body = '',
 *   scope = forum_id, access = forum read-access CSV.
 * - post: one row per post (locale ''), title = the topic title at index time
 *   (re-written on topic reindex), body = post text, scope = the post's own
 *   forum_id, parent_id = topic id, meta = {"forumId":int,"forumTitle":string}.
 *
 * access_groups = '' ⇒ admin-only (matches the forum runtime semantics).
 */
final class Forum implements Indexer
{
    private const MODULE = 'forum';

    private Mysql $db;

    public function __construct(?Mysql $db = null)
    {
        $this->db = $db ?? Registry::get('db');
    }

    public function indexDoc(string $docType, int $docId, string $locale = ''): void
    {
        $store = new Store($this->db);

        switch ($docType) {
            case 'topic':
                $this->indexTopic($store, $docId);
                break;
            case 'post':
                $this->indexPost($store, $docId);
                break;
            default:
                // Unknown doc type: drop any stale document row.
                $store->deleteDoc(self::MODULE, $docType, $docId);
        }
    }

    public function deleteDoc(string $docType, int $docId, string $locale = ''): void
    {
        (new Store($this->db))->deleteDoc(self::MODULE, $docType, $docId);
    }

    public function reindexScope(string $scopeKey, int $scopeId): void
    {
        $store = new Store($this->db);

        switch ($scopeKey) {
            case 'forum_id':
                // Forum missing ⇒ stale scope, delete its documents.
                $forumRow = $this->db->queryRow(
                    'SELECT id FROM [prefix]_forum_items WHERE id = ' . (int)$scopeId . ' AND type = 1'
                );
                if ($forumRow === null) {
                    $store->deleteByScope(self::MODULE, 'forum_id', $scopeId);
                    return;
                }
                foreach ($this->topicIdsByForumId($scopeId) as $topicId) {
                    $this->indexTopic($store, $topicId);
                }
                foreach ($this->postIdsByForumId($scopeId) as $postId) {
                    $this->indexPost($store, $postId);
                }
                break;

            case 'topic_id':
                // Covers topic title edits and topic moves (posts' forum_id).
                $this->indexTopic($store, $scopeId);
                foreach ($this->postIdsByTopicId($scopeId) as $postId) {
                    $this->indexPost($store, $postId);
                }
                break;

            default:
                $store->deleteByScope(self::MODULE, $scopeKey, $scopeId);
        }
    }

    public function deleteScope(string $scopeKey, int $scopeId): void
    {
        $store = new Store($this->db);

        switch ($scopeKey) {
            case 'forum_id':
                // Removes topic + post documents in one statement.
                $store->deleteByScope(self::MODULE, 'forum_id', $scopeId);
                break;

            case 'topic_id':
                $store->deleteDoc(self::MODULE, 'topic', $scopeId);
                $store->deleteByParent(self::MODULE, 'post', $scopeId);
                break;

            default:
                $store->deleteByScope(self::MODULE, $scopeKey, $scopeId);
        }
    }

    public function backfillBatch(int $frontier, int $limit): int
    {
        $store = new Store($this->db);

        $topicIds = $this->db->queryList(
            'SELECT id FROM [prefix]_forum_topics WHERE id > ' . (int)$frontier . ' ORDER BY id ASC LIMIT ' . (int)$limit
        );

        $lastId = $frontier;
        foreach ($topicIds as $topicId) {
            $this->indexTopic($store, (int)$topicId);
            foreach ($this->postIdsByTopicId((int)$topicId) as $postId) {
                $this->indexPost($store, $postId);
            }
            $lastId = (int)$topicId;
        }

        return $lastId;
    }

    // ------------------------------------------------------------------
    // Internals
    // ------------------------------------------------------------------

    /**
     * Index one topic document. Missing topic row ⇒ stale queue race,
     * the document row is deleted.
     *
     * @param Store $store
     * @param int $topicId
     * @return void
     */
    private function indexTopic(Store $store, int $topicId): void
    {
        $topic = $this->db->queryRow(
            'SELECT id, forum_id, topic_title, date_created, last_post_date'
            . ' FROM [prefix]_forum_topics WHERE id = ' . (int)$topicId
        );
        if ($topic === null) {
            $store->deleteDoc(self::MODULE, 'topic', $topicId);
            return;
        }

        $title = self::strip((string)$topic['topic_title']);

        $store->upsert([
            'module'        => self::MODULE,
            'docType'       => 'topic',
            'docId'         => (int)$topic['id'],
            'locale'        => '',
            'parentId'      => (int)$topic['forum_id'],
            'scopeKey'      => 'forum_id',
            'scopeId'       => (int)$topic['forum_id'],
            'title'         => $title,
            'body'          => '',
            'accessGroups'  => $this->forumAccessCsv((int)$topic['forum_id']),
            'dateActivity'  => !empty($topic['last_post_date']) ? (string)$topic['last_post_date'] : (string)$topic['date_created'],
            'meta'          => '{}',
            'contentHash'   => md5($title . ''),
        ]);
    }

    /**
     * Index one post document. Missing post row ⇒ stale queue race,
     * the document row is deleted.
     *
     * @param Store $store
     * @param int $postId
     * @return void
     */
    private function indexPost(Store $store, int $postId): void
    {
        $post = $this->db->queryRow(
            'SELECT p.id, p.forum_id, p.topic_id, p.text, p.date_created'
            . ' FROM [prefix]_forum_posts p WHERE p.id = ' . (int)$postId
        );
        if ($post === null) {
            $store->deleteDoc(self::MODULE, 'post', $postId);
            return;
        }

        $topic = $this->db->queryRow(
            'SELECT id, forum_id, topic_title FROM [prefix]_forum_topics WHERE id = ' . (int)$post['topic_id']
        );
        if ($topic === null) {
            // Topic gone (orphaned post): drop the document, no title to index.
            $store->deleteDoc(self::MODULE, 'post', $postId);
            return;
        }

        $forumRow = $this->db->queryRow(
            'SELECT title FROM [prefix]_forum_items WHERE id = ' . (int)$post['forum_id']
        );

        $title = self::strip((string)$topic['topic_title']);
        $body  = self::strip((string)$post['text']);

        $store->upsert([
            'module'        => self::MODULE,
            'docType'       => 'post',
            'docId'         => (int)$post['id'],
            'locale'        => '',
            'parentId'      => (int)$post['topic_id'],
            'scopeKey'      => 'forum_id',
            'scopeId'       => (int)$post['forum_id'],
            'title'         => $title,
            'body'          => $body,
            'accessGroups'  => $this->forumAccessCsv((int)$post['forum_id']),
            'dateActivity'  => !empty($post['date_created']) ? (string)$post['date_created'] : null,
            'meta'          => json_encode([
                'forumId'    => (int)$post['forum_id'],
                'forumTitle' => (string)($forumRow['title'] ?? ''),
            ]),
            'contentHash'   => md5($title . $body),
        ]);
    }

    /**
     * Forum read-access groups as a CSV ('' ⇒ admin-only).
     *
     * @param int $forumId
     * @return string
     */
    private function forumAccessCsv(int $forumId): string
    {
        $row = $this->db->queryRow(
            'SELECT COALESCE(GROUP_CONCAT(group_id ORDER BY group_id), \'\') AS csv'
            . ' FROM [prefix]_forum_accesses WHERE item_id = ' . (int)$forumId . ' AND access_type = 0'
        );
        return $row === null ? '' : (string)$row['csv'];
    }

    /**
     * @param int $forumId
     * @return array<int>
     */
    private function topicIdsByForumId(int $forumId): array
    {
        return array_map('intval', $this->db->queryList(
            'SELECT id FROM [prefix]_forum_topics WHERE forum_id = ' . (int)$forumId . ' ORDER BY id ASC'
        ));
    }

    /**
     * @param int $topicId
     * @return array<int>
     */
    private function postIdsByTopicId(int $topicId): array
    {
        return array_map('intval', $this->db->queryList(
            'SELECT id FROM [prefix]_forum_posts WHERE topic_id = ' . (int)$topicId . ' ORDER BY id ASC'
        ));
    }

    /**
     * @param int $forumId
     * @return array<int>
     */
    private function postIdsByForumId(int $forumId): array
    {
        return array_map('intval', $this->db->queryList(
            'SELECT id FROM [prefix]_forum_posts WHERE forum_id = ' . (int)$forumId . ' ORDER BY id ASC'
        ));
    }

    /**
     * Plain-text normalization for indexed content:
     * decode entities, strip tags, collapse whitespace.
     *
     * Entities are decoded before stripping so that escaped markup
     * ("&lt;b&gt;") is removed as well.
     *
     * @param string $text
     * @return string
     */
    private static function strip(string $text): string
    {
        $text = strip_tags((string)html_entity_decode($text, ENT_QUOTES));
        return trim((string)preg_replace('/\s+/', ' ', $text));
    }
}
