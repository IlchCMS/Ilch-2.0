<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

/**
 * One search result returned by \Ilch\Search\Engine::search().
 *
 * Plain value object; no database or layout access. The document URL is not
 * stored here: the controller/view builds it with getUrl() at render time.
 */
final class SearchHit
{
    /**
     * @param string $module       source module key ('forum' | 'article')
     * @param string $docType      'topic' | 'post' | 'article'
     * @param int $docId           id in the source module
     * @param string $locale       content locale ('' for forum documents)
     * @param int|null $parentId   post → topic id, article → first category id (0 if none)
     * @param string $title        document title (forum post: the topic title at index time)
     * @param string $body         indexed plain-text body
     * @param float $score         engine score (relative within one result set)
     * @param string|null $dateActivity 'Y-m-d H:i:s' or null
     * @param array $meta          decoded meta JSON (assoc array, may be empty)
     */
    public function __construct(
        public readonly string $module,
        public readonly string $docType,
        public readonly int $docId,
        public readonly string $locale,
        public readonly ?int $parentId,
        public readonly string $title,
        public readonly string $body,
        public readonly float $score,
        public readonly ?string $dateActivity,
        public readonly array $meta,
    ) {
    }
}
