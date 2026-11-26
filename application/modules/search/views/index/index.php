<?php

/** @var \Ilch\View $this */

/** @var string $search */
$search = $this->get('search');
/** @var string $filter */
$filter = $this->get('filter');
/** @var string $locale */
$locale = $this->get('locale');
/** @var string $from */
$from = $this->get('from');
/** @var string $to */
$to = $this->get('to');
/** @var string $scopeKey */
$scopeKey = $this->get('scopeKey');
/** @var int $scopeId */
$scopeId = (int)$this->get('scopeId');
/** @var array $tokens */
$tokens = $this->get('tokens');
/** @var \Ilch\Search\SearchHit[] $hits */
$hits = $this->get('hits');
/** @var int $total */
$total = $this->get('total');
/** @var bool $approximate */
$approximate = $this->get('approximate');
/** @var string $engine */
$engine = $this->get('engine');
/** @var array $topicDocs */
$topicDocs = $this->get('topicDocs');
/** @var array<string, string> $moduleLabels */
$moduleLabels = $this->get('moduleLabels');
/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');

// Params shared by the module tabs, the locale dropdown and the pagination
// links. The module filter uses the "filter" key so it never collides with
// the URL's own "module" key when links are rebuilt.
$baseParams = [
    'module' => 'search',
    'controller' => 'index',
    'action' => 'index',
    'search' => $search,
];
if ($filter !== '') {
    $baseParams['filter'] = $filter;
}
if ($locale !== '') {
    $baseParams['locale'] = $locale;
}
if ($from !== '') {
    $baseParams['from'] = $from;
}
if ($to !== '') {
    $baseParams['to'] = $to;
}
if ($scopeKey !== '' && $scopeId > 0) {
    $baseParams['scopeKey'] = $scopeKey;
    $baseParams['scopeId'] = (string)$scopeId;
}

// Module tabs, one per module that has an indexer (localized name from
// the module table; the '' tab is the unfiltered "All" tab).
$tabLabels = ['' => $this->getTrans('filterAll')];
foreach ($moduleLabels as $moduleKey => $label) {
    $tabLabels[(string)$moduleKey] = (string)$label;
}

// Distinct content locales among all hits (locale dropdown options).
$locales = [];
foreach ($hits as $hit) {
    if ($hit->locale !== '' && !in_array($hit->locale, $locales, true)) {
        $locales[] = $hit->locale;
    }
}
sort($locales);
if ($locale !== '' && !in_array($locale, $locales, true)) {
    $locales[] = $locale;
    sort($locales);
}

// Group the forum hits by topic, keeping the engine's score order (the first
// hit of a topic decides its position); posts are nested under their topic.
$forumGroups = [];
foreach ($hits as $hit) {
    if ($hit->module === 'forum' && $hit->docType === 'topic') {
        $forumGroups[$hit->docId] = ['topic' => $hit, 'posts' => []];
    } elseif ($hit->module === 'forum' && $hit->docType === 'post') {
        $topicId = (int)($hit->parentId ?? 0);
        if (!isset($forumGroups[$topicId])) {
            $forumGroups[$topicId] = ['topic' => null, 'posts' => []];
        }
        $forumGroups[$topicId]['posts'][] = $hit;
    }
}
// All non-forum hits grouped by module key (the forum hits are rendered in
// the topic-grouped block below).
$moduleHits = [];
foreach ($hits as $hit) {
    if ($hit->module !== 'forum') {
        $moduleHits[$hit->module][] = $hit;
    }
}
?>

<link href="<?=$this->getModuleUrl('static/css/search.css') ?>" rel="stylesheet">

<h1>
    <?php if ($search === ''): ?>
        <?=$this->getTrans('search') ?>
    <?php else: ?>
        <?=$this->getTrans('resultsFor', $this->escape($search)) ?>
    <?php endif; ?>
</h1>

<form method="get" action="<?=$this->getUrl(['module' => 'search', 'controller' => 'index', 'action' => 'index']) ?>" class="search-form">
    <div class="input-group">
        <input type="text" class="form-control" name="search" value="<?=$this->escape($search) ?>" placeholder="<?=$this->getTrans('searchPlaceholder') ?>" aria-label="<?=$this->getTrans('search') ?>">
        <button type="submit" class="btn btn-secondary btn-hover-success">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
    <?php if ($filter !== ''): ?>
        <input type="hidden" name="filter" value="<?=$this->escape($filter) ?>">
    <?php endif; ?>
    <?php if ($locale !== ''): ?>
        <input type="hidden" name="locale" value="<?=$this->escape($locale) ?>">
    <?php endif; ?>
    <?php if ($from !== ''): ?>
        <input type="hidden" name="from" value="<?=$this->escape($from) ?>">
    <?php endif; ?>
    <?php if ($to !== ''): ?>
        <input type="hidden" name="to" value="<?=$this->escape($to) ?>">
    <?php endif; ?>
    <?php if ($scopeKey !== '' && $scopeId > 0): ?>
        <input type="hidden" name="scopeKey" value="<?=$this->escape($scopeKey) ?>">
        <input type="hidden" name="scopeId" value="<?=$scopeId ?>">
    <?php endif; ?>
</form>

<?php if ($search !== ''): ?>

<p>
    <?php if ($total > 0): ?>
        <?php if ($approximate): ?>
            <span class="badge bg-secondary"><?=$this->getTrans('approxResults') ?></span>
            <?=$this->getTrans('resultsApprox', number_format($total)) ?>
        <?php else: ?>
            <?=$this->getTrans('resultsFound', number_format($total)) ?>
        <?php endif; ?>
    <?php else: ?>
        <?=$this->getTrans('noResults') ?>
    <?php endif; ?>
</p>

<div class="search-filters">
    <ul class="nav nav-tabs">
        <?php foreach ($tabLabels as $tabValue => $tabLabel):
            $tabParams = $baseParams;
            if ($tabValue === '') {
                unset($tabParams['filter']);
            } else {
                $tabParams['filter'] = $tabValue;
            }
        ?>
            <li class="nav-item">
                <a class="nav-link<?= ($tabValue === $filter) ? ' active' : '' ?>" href="<?=$this->getUrl($tabParams) ?>">
                    <?=$this->escape($tabLabel) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($locales !== []): ?>
        <form method="get" action="<?=$this->getUrl($baseParams) ?>" class="search-locale-form">
            <label for="searchLocale"><?=$this->getTrans('locale') ?>:</label>
            <select id="searchLocale" name="locale" onchange="this.form.submit()">
                <option value=""><?=$this->getTrans('localeAny') ?></option>
                <?php foreach ($locales as $localeOption): ?>
                    <option value="<?=$this->escape($localeOption) ?>"<?= ($localeOption === $locale) ? ' selected' : '' ?>><?=$this->escape($localeOption) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>
    <form method="get" action="<?=$this->getUrl($baseParams) ?>" class="search-date-form">
        <label for="searchFrom"><?=$this->getTrans('from') ?>:</label>
        <input type="date" id="searchFrom" name="from" value="<?=$this->escape($from) ?>" onchange="this.form.submit()">
        <label for="searchTo"><?=$this->getTrans('to') ?>:</label>
        <input type="date" id="searchTo" name="to" value="<?=$this->escape($to) ?>" onchange="this.form.submit()">
    </form>
</div>

<?php if ($forumGroups !== []): ?>
    <h2><?=$this->getTrans('filterForum') ?></h2>
    <?php foreach ($forumGroups as $topicId => $group):
        $topicHit = $group['topic'];
        // Live topic document wins (title edits are never stale); fall back
        // to the indexed title if the document row is gone.
        $topicTitle = isset($topicDocs[$topicId])
            ? (string)$topicDocs[$topicId]['title']
            : (string)($topicHit !== null ? $topicHit->title : '');
        $topicUrl = $this->getUrl([
            'module' => 'forum',
            'controller' => 'showposts',
            'action' => 'index',
            'topicid' => $topicId,
        ]);
        // Forum title lives in the post meta; show it once per topic group.
        $forumTitle = '';
        foreach ($group['posts'] as $groupPost) {
            if (!empty($groupPost->meta['forumTitle'])) {
                $forumTitle = (string)$groupPost->meta['forumTitle'];
                break;
            }
        }
        $groupDate = null;
        if ($topicHit !== null && $topicHit->dateActivity !== null) {
            $groupDate = $topicHit->dateActivity;
        } elseif ($group['posts'] !== [] && $group['posts'][0]->dateActivity !== null) {
            $groupDate = $group['posts'][0]->dateActivity;
        }
    ?>
        <div class="search-result">
            <h3><a href="<?=$topicUrl ?>"><?=$this->escape($topicTitle) ?></a></h3>
            <div class="search-result-meta">
                <span class="badge bg-secondary"><?=$this->getTrans('filterForum') ?></span>
                <?php if ($forumTitle !== ''): ?>
                    <?=$this->getTrans('inForum', $this->escape($forumTitle)) ?> ·
                <?php endif; ?>
                <?php if ($groupDate !== null): ?>
                    <?php $groupDateObj = new \Ilch\Date($groupDate); ?>
                    <?=$groupDateObj->format('d.m.Y - H:i', true) ?>
                <?php endif; ?>
            </div>
            <?php foreach ($group['posts'] as $post):
                $excerpt = \Ilch\Search\Excerpt::make($post->body, $tokens);
                $postUrl = $topicUrl . '#' . $post->docId;
            ?>
                <div class="search-result-post">
                    <?php if ($excerpt !== ''): ?>
                        <p><?=$this->escape($excerpt) ?></p>
                    <?php endif; ?>
                    <div class="search-result-meta">
                        <a href="<?=$postUrl ?>"><?=$this->escape($topicTitle) ?></a>
                        <?php if (!empty($post->meta['forumTitle'])): ?>
                            &middot; <?=$this->getTrans('inForum', $this->escape((string)$post->meta['forumTitle'])) ?>
                        <?php endif; ?>
                        <?php if ($post->dateActivity !== null): ?>
                            &middot; <?php $postDate = new \Ilch\Date($post->dateActivity); ?>
                            <?=$postDate->format('d.m.Y - H:i', true) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php foreach ($moduleHits as $moduleKey => $moduleHitList):
    $label = $moduleLabels[$moduleKey] ?? (string)$moduleKey;
?>
    <h2><?=$this->escape($label) ?></h2>
    <?php foreach ($moduleHitList as $hit):
        $excerpt = \Ilch\Search\Excerpt::make($hit->body, $tokens);
        // Indexers store the document's URL params in meta['url'] (an assoc
        // array for getUrl()); a missing or malformed value renders the
        // title without a link.
        $hitUrl = isset($hit->meta['url']) && is_array($hit->meta['url'])
            ? $this->getUrl($hit->meta['url'])
            : null;
    ?>
        <div class="search-result">
            <h3>
                <?php if ($hitUrl !== null): ?>
                    <a href="<?=$hitUrl ?>"><?=$this->escape($hit->title) ?></a>
                <?php else: ?>
                    <?=$this->escape($hit->title) ?>
                <?php endif; ?>
                <?php if ($locale !== '' && $hit->locale !== ''): ?>
                    <span class="badge bg-secondary search-result-locale"><?=$this->escape($hit->locale) ?></span>
                <?php endif; ?>
            </h3>
            <?php if ($excerpt !== ''): ?>
                <p><?=$this->escape($excerpt) ?></p>
            <?php endif; ?>
            <div class="search-result-meta">
                <span class="badge bg-secondary"><?=$this->escape($label) ?></span>
                <?php if ($hit->dateActivity !== null): ?>
                    &middot; <?php $hitDate = new \Ilch\Date($hit->dateActivity); ?>
                    <?=$hitDate->format('d.m.Y - H:i', true) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<div class="float-end">
    <?=$pagination->getHtml($this, $baseParams, 'page') ?>
</div>

<?php endif; ?>
