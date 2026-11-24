<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\Controllers;

use Ilch\Pagination;
use Ilch\Search\Engine;
use Ilch\Search\SearchHit;
use Ilch\Search\Store;

/**
 * Frontend search controller.
 *
 * init() runs before the action dispatch and does all the work; the
 * default action name is "index", so the rendered view is
 * views/index/index.php. An empty or missing "search" param renders the
 * view with zero hits (no exception).
 *
 * Protection: the requested page number is clamped (MAX_PAGE) so a
 * hand-crafted ?page=N can never produce an unbounded OFFSET, and
 * searches are rate limited per session (RATE_LIMIT_MAX within
 * RATE_LIMIT_WINDOW seconds). A blocked search renders zero hits with a
 * flash message instead of running the engine.
 *
 * Canonical URL: the search box posts the term and legacy links may
 * still carry it as ?search=...; both are redirected to the path-style
 * URL (…/search/index/index/search/<term>) so the address bar matches
 * Ilch's URL scheme.
 */
class Index extends \Ilch\Controller\Frontend
{
    /** Rows per results page. */
    private const PAGE_SIZE = 20;

    /**
     * Max page number. The pagination links never exceed this for
     * realistic result counts; it clamps hand-crafted ?page=N so it can
     * not turn into an unbounded OFFSET.
     */
    private const MAX_PAGE = 500;

    /** Rate limit: at most MAX searches within WINDOW seconds per session. */
    private const RATE_LIMIT_MAX = 10;
    private const RATE_LIMIT_WINDOW = 60;

    public function init()
    {
        // module_search group access (no groups_access row ⇒ level 1 ⇒
        // allowed for everyone; admins bypass).
        if (!$this->getAccesses()->hasAccess('Module', 'search')) {
            $this->redirect();
        }

        // The search box posts the term; legacy links may still carry it
        // as ?search=... Both are redirected to the canonical path-style
        // URL. Terms containing "/" cannot round-trip through path params,
        // so they keep their current URL (input() still handles them).
        $source = $this->getRequest()->getPost('search')
            ?? $this->getRequest()->getQuery('search');
        $canonical = mb_substr(trim((string)$source, " \f\n\r\t\v\x00"), 0, 100);
        if ($canonical !== '' && strpos($canonical, '/') === false) {
            $this->redirect($this->getLayout()->getUrl([
                'module' => 'search',
                'controller' => 'index',
                'action' => 'index',
                'search' => urlencode($canonical),
            ]));
        }

        $query = mb_substr(trim($this->input('search'), " \f\n\r\t\v\x00"), 0, 100);

        // Module filter; the param is named "filter" (not "module") so it
        // never collides with the URL's own module key when links are rebuilt.
        $filter = $this->input('filter');
        if (!in_array($filter, ['forum', 'article'], true)) {
            $filter = '';
        }

        // Optional locale filter (off by default).
        $locale = trim($this->input('locale'), " \f\n\r\t\v\x00");

        // Optional date range and forum/category scope filters (off by
        // default); the engine validates the values and drops anything
        // it does not understand.
        $from     = $this->input('from');
        $to       = $this->input('to');
        $scopeKey = trim($this->input('scopeKey'), " \f\n\r\t\v\x00");
        $scopeId  = (int)$this->input('scopeId');

        $filters = [
            'module'   => $filter,
            'locale'   => $locale,
            'from'     => $from,
            'to'       => $to,
            'scopeKey' => $scopeKey,
            'scopeId'  => $scopeId,
        ];

        // Rate limit: a sliding window of recent search times in the session.
        // Only non-empty queries count (empty queries return early in the
        // engine without any DB work); a blocked attempt is not recorded, so
        // the window keeps its exact "N per window" pace.
        if ($query !== '') {
            $now = time();
            $recent = array_values(array_filter(
                $_SESSION['search_times'] ?? [],
                static fn ($t): bool => (int)$t >= $now - self::RATE_LIMIT_WINDOW
            ));
            if (count($recent) >= self::RATE_LIMIT_MAX) {
                $_SESSION['search_times'] = $recent;
                $this->addMessage('tooManySearches', 'danger');
                $this->renderEmpty($query, $filters);
                return;
            }
            $recent[] = $now;
            $_SESSION['search_times'] = $recent;
        }

        $userObj = $this->getUser();
        $user = [
            'isAdmin' => $userObj !== null && (bool)$userObj->isAdmin(),
            'groups'  => $userObj !== null
                ? array_map(static fn ($g) => (int)$g->getId(), $userObj->getGroups())
                : [],
        ];

        $pagination = new Pagination();
        $pagination->setRowsPerPage(self::PAGE_SIZE);
        // Clamp the requested page; setPage() already handles the floor.
        $pagination->setPage(min((int)$this->input('page'), self::MAX_PAGE));

        $result = (new Engine())
            ->search(
                $query,
                $filters,
                $user,
                $pagination->getPage(),
                self::PAGE_SIZE
            );
        $pagination->setRows($result['total']);

        // Forum grouping: fetch the CURRENT topic titles for post hits so
        // title edits are never stale.
        $topicDocs = [];
        $parentIds = [];
        foreach ($result['hits'] as $hit) {
            if ($hit->module === 'forum' && $hit->docType === 'post' && $hit->parentId !== null) {
                $parentIds[] = $hit->parentId;
            }
        }
        if ($parentIds !== []) {
            $topicDocs = (new Store())->getDocsByIds('forum', 'topic', array_unique($parentIds));
        }

        // Article dedupe: when multiple locale rows of the same article
        // matched, keep only the best-scoring one per docId (hits are already
        // sorted by score DESC, so the first one wins).
        $hits = $this->dedupeArticles($result['hits']);

        // Tokens for excerpt building (same rule as Engine::search()).
        $tokens = preg_split('/[^\p{L}\p{N}_]+/u', $query, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($tokens)) {
            $tokens = [];
        }
        $tokens = array_values(array_unique(array_map('strval', $tokens)));

        $this->getView()
            ->set('search', $query)
            ->set('filter', $filters['module'])
            ->set('locale', $filters['locale'])
            ->set('from', $filters['from'])
            ->set('to', $filters['to'])
            ->set('scopeKey', $filters['scopeKey'])
            ->set('scopeId', $filters['scopeId'])
            ->set('tokens', $tokens)
            ->set('hits', $hits)
            ->set('total', $result['total'])
            ->set('approximate', $result['approximate'])
            ->set('engine', $result['engine'])
            ->set('topicDocs', $topicDocs)
            ->set('modules', \Ilch\Search\Indexers::modules())
            ->set('pagination', $pagination);
    }

    /**
     * Read an input from the routed path params or the raw query string.
     *
     * The router only converts path segments into request params; query
     * string values (standard GET form submits) are read via getQuery().
     */
    private function input(string $key): string
    {
        return (string)($this->getRequest()->getParam($key)
            ?? $this->getRequest()->getQuery($key)
            ?? '');
    }

    /**
     * Render the results view with zero hits without running the engine
     * (used when the rate limit is exceeded; the flash message explains).
     *
     * @param string $query
     * @param array $filters {module, locale, from, to, scopeKey, scopeId}
     */
    private function renderEmpty(string $query, array $filters): void
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(self::PAGE_SIZE);
        $pagination->setPage(min((int)$this->input('page'), self::MAX_PAGE));

        $this->getView()
            ->set('search', $query)
            ->set('filter', $filters['module'])
            ->set('locale', $filters['locale'])
            ->set('from', $filters['from'])
            ->set('to', $filters['to'])
            ->set('scopeKey', $filters['scopeKey'])
            ->set('scopeId', $filters['scopeId'])
            ->set('tokens', [])
            ->set('hits', [])
            ->set('total', 0)
            ->set('approximate', false)
            ->set('engine', 'like')
            ->set('topicDocs', [])
            ->set('modules', \Ilch\Search\Indexers::modules())
            ->set('pagination', $pagination);
    }

    /**
     * Keep the best-scoring locale row per article docId; forum hits pass
     * through untouched.
     *
     * @param SearchHit[] $hits sorted by score DESC
     * @return SearchHit[]
     */
    private function dedupeArticles(array $hits): array
    {
        $seen = [];
        $result = [];
        foreach ($hits as $hit) {
            if ($hit->module !== 'article') {
                $result[] = $hit;
                continue;
            }
            if (isset($seen[$hit->docId])) {
                continue;
            }
            $seen[$hit->docId] = true;
            $result[] = $hit;
        }
        return $result;
    }
}
