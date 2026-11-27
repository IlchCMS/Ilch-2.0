<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Ilch\Accesses;
use Modules\Admin\Mappers\Module as ModuleMapper;

/**
 * Admin navigation search controller.
 *
 * Activates the search box in the admin layout: instead of the global
 * content search it searches the admin navigation targets (the module
 * dropdown entries and the fixed top navbar entries). init() runs before
 * the action dispatch and does all the work; the default action name is
 * "index", so the rendered view is views/admin/nav/index.php.
 *
 * Canonical URL: the box POSTs the term; a posted or query (?search=...)
 * value is redirected to the path-style URL (…/admin/nav/index/index/
 * search/<term>). A path param means the URL is already canonical, so it
 * never triggers the redirect. Terms containing "/" cannot round-trip
 * through path params, so they keep their current URL (the term is still
 * read below).
 */
class Nav extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();

        // The search box POSTs the term; legacy links may still carry it
        // as ?search=.... Both are redirected to the canonical path-style
        // URL. Only POST/query values trigger the redirect - a path param
        // means the URL is already canonical, so redirecting on it would
        // loop. Terms containing "/" cannot round-trip through path params,
        // so they keep their current URL (the term is still read below).
        $source = $this->getRequest()->getPost('search')
            ?? $this->getRequest()->getQuery('search');
        $canonical = mb_substr(trim((string)$source, " \f\n\r\t\v\x00"), 0, 100);
        if ($canonical !== '' && strpos($canonical, '/') === false) {
            $this->redirect($this->getLayout()->getUrl([
                'module' => 'admin',
                'controller' => 'nav',
                'action' => 'index',
                'search' => urlencode($canonical),
            ]));
        }

        // The canonical URL carries the term as a path param; POST and
        // query terms (including "/"-containing ones that skipped the
        // redirect) are read from the source.
        $term = mb_substr(trim((string)($this->getRequest()->getParam('search')
            ?? $source
            ?? ''), " \f\n\r\t\v\x00"), 0, 100);

        $this->getView()
            ->set('term', $term)
            ->set('results', $this->searchTargets($term));
    }

    /**
     * Collects the admin navigation targets the current user may open
     * (the layout's access conditions are mirrored verbatim) and ranks
     * the ones whose label or module key matches the query.
     *
     * @param string $term
     * @return array each entry is ['label' => string, 'url' => string,
     *         'hint' => string, 'newTab' => bool]
     */
    private function searchTargets(string $term): array
    {
        if ($term === '') {
            return [];
        }

        $user = $this->getUser();
        $accesses = $this->getAccesses();
        $isAdmin = $user !== null && $user->isAdmin();
        $termLower = mb_strtolower($term);

        $results = [];

        // Module targets, same checks as the layout's module dropdown.
        $moduleMapper = new ModuleMapper();
        foreach ($moduleMapper->searchModules($term) as $match) {
            /** @var \Modules\Admin\Models\Module $module */
            $module = $match['module'];
            $key = (string)$module->getKey();

            if (!$user->hasAccess('module_' . $key)
                && !($key === 'article' && $accesses->hasAccess('Admin', null, Accesses::TYPE_ARTICLE))) {
                continue;
            }
            if (!$accesses->hasAccess('Admin_' . $key, $key)) {
                continue;
            }

            $results[] = [
                'rank' => $match['rank'],
                'label' => $module->getName(),
                'url' => $this->getLayout()->getUrl(['module' => $key, 'controller' => 'index', 'action' => 'index']),
                'hint' => $key,
                'newTab' => false,
            ];
        }

        // Fixed top navbar targets, in the order of the layout.
        $fixedTargets = [
            ['label' => 'navigation', 'url' => ['module' => 'admin', 'controller' => 'menu', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'overview', 'url' => ['module' => 'admin', 'controller' => 'modules', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuSites', 'url' => ['module' => 'admin', 'controller' => 'page', 'action' => 'index'], 'allowed' => $accesses->hasAccess('Admin', null, Accesses::TYPE_PAGE), 'newTab' => false],
            ['label' => 'menuBoxes', 'url' => ['module' => 'admin', 'controller' => 'boxes', 'action' => 'index'], 'allowed' => $accesses->hasAccess('Admin', null, Accesses::TYPE_BOX), 'newTab' => false],
            ['label' => 'menuLayouts', 'url' => ['module' => 'admin', 'controller' => 'layouts', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuSettings', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            // Settings section sidebar entries (same section as menuSettings,
            // gated by the same isAdmin check), in the order of the menu.
            ['label' => 'menuMaintenance', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'maintenance'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuCustomCSS', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'customcss'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuHtaccess', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'htaccess'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuBackup', 'url' => ['module' => 'admin', 'controller' => 'backup', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuUpdate', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'update'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuNotifications', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'notifications'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuEmails', 'url' => ['module' => 'admin', 'controller' => 'emails', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuMail', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'mail'], 'allowed' => $isAdmin, 'newTab' => false],
            // Dedicated HTMLPurifier settings page (not part of the sidebar
            // menu, reached via a button on the settings page).
            ['label' => 'menuHtmlPurifier', 'url' => ['module' => 'admin', 'controller' => 'settings', 'action' => 'htmlpurifier'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuInfos', 'url' => ['module' => 'admin', 'controller' => 'infos', 'action' => 'index'], 'allowed' => $isAdmin, 'newTab' => false],
            ['label' => 'menuFrontend', 'url' => $this->getLayout()->getUrl(), 'allowed' => true, 'newTab' => true],
        ];

        foreach ($fixedTargets as $target) {
            if (!$target['allowed']) {
                continue;
            }
            $label = $this->getTranslator()->trans($target['label']);
            $rank = $this->matchRank($termLower, mb_strtolower($label));
            if ($rank === 0) {
                continue;
            }

            $url = $target['url'];
            if (is_array($url)) {
                $url = $this->getLayout()->getUrl($url);
            }

            $results[] = [
                'rank' => $rank,
                'label' => $label,
                'url' => $url,
                'hint' => '',
                'newTab' => $target['newTab'],
            ];
        }

        usort($results, function (array $a, array $b): int {
            if ($a['rank'] !== $b['rank']) {
                return $a['rank'] <=> $b['rank'];
            }
            if (strcasecmp($a['label'], $b['label']) !== 0) {
                return strcasecmp($a['label'], $b['label']);
            }
            return strcasecmp($a['hint'], $b['hint']);
        });

        // The rank is only needed for sorting; the view gets the rest.
        return array_map(static fn (array $result): array => [
            'label' => $result['label'],
            'url' => $result['url'],
            'hint' => $result['hint'],
            'newTab' => $result['newTab'],
        ], $results);
    }

    /**
     * Match rank of needle in haystack: 1 for an exact match, 2 for a
     * prefix, 3 for a plain substring, 0 for no match. Both values are
     * expected to be lower-cased (same rule as ModuleMapper).
     *
     * @param string $needle
     * @param string $haystack
     * @return int
     */
    private function matchRank(string $needle, string $haystack): int
    {
        if ($needle === $haystack) {
            return 1;
        }
        if (str_starts_with($haystack, $needle)) {
            return 2;
        }
        if (str_contains($haystack, $needle)) {
            return 3;
        }
        return 0;
    }
}
