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
 * Search indexer for the article module.
 *
 * Document model: one row per (article_id, locale). The access CSV from
 * articles_access is shared over all locale rows of the same article.
 * access_groups = '' ⇒ admin-only (matches the article runtime semantics).
 */
final class Article implements Indexer
{
    private const MODULE = 'article';

    private Mysql $db;

    public function __construct(?Mysql $db = null)
    {
        $this->db = $db ?? Registry::get('db');
    }

    public function indexDoc(string $docType, int $docId, string $locale = ''): void
    {
        if ($docType !== 'article') {
            // Unknown doc type: drop any stale document rows.
            (new Store($this->db))->deleteDoc(self::MODULE, $docType, $docId, $locale);
            return;
        }
        $this->indexArticle($locale, $docId);
    }

    public function deleteDoc(string $docType, int $docId, string $locale = ''): void
    {
        (new Store($this->db))->deleteDoc(self::MODULE, $docType, $docId, $locale);
    }

    public function reindexScope(string $scopeKey, int $scopeId): void
    {
        $store = new Store($this->db);

        switch ($scopeKey) {
            case 'cat_id':
                // cat_id may be a single id or a CSV; FIND_IN_SET covers both.
                $articleIds = $this->db->queryList(
                    'SELECT id FROM [prefix]_articles WHERE FIND_IN_SET(' . (int)$scopeId . ', cat_id)'
                );
                foreach (array_map('intval', $articleIds) as $articleId) {
                    $this->indexArticle('', $articleId);
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
            case 'cat_id':
                $articleIds = $this->db->queryList(
                    'SELECT id FROM [prefix]_articles WHERE FIND_IN_SET(' . (int)$scopeId . ', cat_id)'
                );
                foreach (array_map('intval', $articleIds) as $articleId) {
                    $store->deleteDoc(self::MODULE, 'article', $articleId);
                }
                break;

            default:
                $store->deleteByScope(self::MODULE, $scopeKey, $scopeId);
        }
    }

    public function backfillBatch(int $frontier, int $limit): int
    {
        $articleIds = $this->db->queryList(
            'SELECT id FROM [prefix]_articles WHERE id > ' . (int)$frontier
            . ' ORDER BY id ASC LIMIT ' . (int)$limit
        );

        $lastId = $frontier;
        foreach (array_map('intval', $articleIds) as $articleId) {
            $this->indexArticle('', $articleId);
            $lastId = $articleId;
        }

        return $lastId;
    }

    // ------------------------------------------------------------------
    // Internals
    // ------------------------------------------------------------------

    /**
     * Index one article (all locales, or a single locale when given).
     * Missing article row ⇒ stale queue race, the documents are deleted.
     *
     * @param string $locale '' = all locales
     * @param int $articleId
     * @return void
     */
    private function indexArticle(string $locale, int $articleId): void
    {
        $store = new Store($this->db);

        $article = $this->db->queryRow(
            'SELECT id, cat_id, date_created FROM [prefix]_articles WHERE id = ' . (int)$articleId
        );
        if ($article === null) {
            $store->deleteDoc(self::MODULE, 'article', $articleId);
            return;
        }

        // Shared over all locales of the article.
        $accessGroups = $this->articleAccessCsv((int)$articleId);

        // First category id (0 when none): cat_id may be a single id or a CSV.
        $catId    = trim((string)$article['cat_id']);
        $catParts = $catId === '' ? [] : explode(',', $catId);
        $scopeId  = $catParts === [] ? 0 : (int)reset($catParts);

        $sql = 'SELECT locale, title, content FROM [prefix]_articles_content WHERE article_id = ' . (int)$articleId;
        if ($locale !== '') {
            $sql .= ' AND locale = ' . $this->db->escape($locale, true);
        }
        $contentRows = $this->db->queryArray($sql);

        if ($locale !== '' && $contentRows === []) {
            // Requested locale no longer has content: drop the stale doc row.
            $store->deleteDoc(self::MODULE, 'article', $articleId, $locale);
            return;
        }

        foreach ($contentRows as $contentRow) {
            $title = self::strip((string)$contentRow['title']);
            $body  = self::strip((string)$contentRow['content']);

            $store->upsert([
                'module'       => self::MODULE,
                'docType'      => 'article',
                'docId'        => (int)$articleId,
                'locale'       => (string)$contentRow['locale'],
                'parentId'     => $scopeId,
                'scopeKey'     => 'cat_id',
                'scopeId'      => $scopeId,
                'title'        => $title,
                'body'         => $body,
                'accessGroups' => $accessGroups,
                'dateActivity' => !empty($article['date_created']) ? (string)$article['date_created'] : null,
                'meta'         => '{}',
                'contentHash'  => md5($title . $body),
            ]);
        }
    }

    /**
     * Article read-access groups as a CSV ('' ⇒ admin-only).
     *
     * @param int $articleId
     * @return string
     */
    private function articleAccessCsv(int $articleId): string
    {
        $row = $this->db->queryRow(
            'SELECT COALESCE(GROUP_CONCAT(group_id ORDER BY group_id), \'\') AS csv'
            . ' FROM [prefix]_articles_access WHERE article_id = ' . (int)$articleId
        );
        return $row === null ? '' : (string)$row['csv'];
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
