<?php

/** @var \Ilch\View $this */

/** @var string $search */
$search = $this->get('search');
/** @var string $filter */
$filter = $this->get('filter');
/** @var string $locale */
$locale = $this->get('locale');
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
/** @var array $modules */
$modules = $this->get('modules');
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

// Module tabs, one per module that has an indexer (localized label).
$tabLabels = ['' => 'filterAll'];
foreach ($modules as $moduleKey => $indexerClass) {
    $tabLabels[(string)$moduleKey] = 'filter' . ucfirst((string)$moduleKey);
}

// Distinct content locales among the article hits (locale dropdown options).
$locales = [];
foreach ($hits as $hit) {
    if ($hit->module === 'article' && $hit->locale !== '' && !in_array($hit->locale, $locales, true)) {
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
$articleHits = array_values(array_filter($hits, static fn ($hit) => $hit->module === 'article'));
?>

<link href="<?=$this->getModuleUrl('static/css/search.css') ?>" rel="stylesheet">

<h1>
    <?php if ($search === ''): ?>
        <?=$this->getTrans('search') ?>
    <?php else: ?>
        <?=$this->getTrans('resultsFor', $this->escape($search)) ?>
    <?php endif; ?>
</h1>

<?php if ($search === ''): ?>
    <p><?=$this->getTrans('noResults') ?></p>
    <?php return; ?>
<?php endif; ?>

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
                    <?=$this->getTrans($tabLabel) ?>
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

<?php if ($articleHits !== []): ?>
    <h2><?=$this->getTrans('filterArticle') ?></h2>
    <?php foreach ($articleHits as $hit):
        $excerpt = \Ilch\Search\Excerpt::make($hit->body, $tokens);
    ?>
        <div class="search-result">
            <h3>
                <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $hit->docId]) ?>">
                    <?=$this->escape($hit->title) ?>
                </a>
                <?php if ($locale !== '' && $hit->locale !== ''): ?>
                    <span class="badge bg-secondary search-result-locale"><?=$this->escape($hit->locale) ?></span>
                <?php endif; ?>
            </h3>
            <?php if ($excerpt !== ''): ?>
                <p><?=$this->escape($excerpt) ?></p>
            <?php endif; ?>
            <div class="search-result-meta">
                <span class="badge bg-secondary"><?=$this->getTrans('filterArticle') ?></span>
                <?php if ($hit->dateActivity !== null): ?>
                    &middot; <?php $articleDate = new \Ilch\Date($hit->dateActivity); ?>
                    <?=$articleDate->format('d.m.Y - H:i', true) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="float-end">
    <?=$pagination->getHtml($this, $baseParams, 'page') ?>
</div>
