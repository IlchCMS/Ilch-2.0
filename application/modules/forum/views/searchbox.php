<?php

/**
 * Forum-scoped search box, included from every forum browsing view.
 *
 * Submits a GET form to the search module. The hidden "scopeKey" plus the
 * forum select ("scopeId") restrict the results to the chosen forum; the
 * "All forums" option sends an empty scopeId, which the engine ignores.
 * The current forum is preselected when browsing a forum (forumid) or a
 * topic (topicid), provided the user has read access to it.
 *
 * @var \Ilch\View $this
 */

use Modules\Forum\Mappers\Forum as ForumMapper;

$forumMapper = new ForumMapper();
$user = $this->getUser();

// Only actual forums (type 1) in the same order as the forum index view.
$forums = $forumMapper->getForumListUser($user);

// Nothing searchable for this user: stay out of the way.
if ($forums === []) {
    return;
}

$currentForumId = 0;
$forumId = $this->getRequest()->getParam('forumid');
if ($forumId !== null && (int)$forumId > 0) {
    $currentForumId = (int)$forumId;
} else {
    $topicId = $this->getRequest()->getParam('topicid');
    if ($topicId !== null && (int)$topicId > 0) {
        $topicForum = $forumMapper->getForumByTopicIdUser((int)$topicId, $user);
        if ($topicForum !== null && $topicForum->getType() === 1) {
            $currentForumId = $topicForum->getId();
        }
    }
}

// Drop the preselection if the user cannot read that forum.
$readableIds = [];
foreach ($forums as $forum) {
    $readableIds[] = $forum->getId();
}
if (!in_array($currentForumId, $readableIds, true)) {
    $currentForumId = 0;
}
?>

<div class="forum-searchbox">
    <form method="get" action="<?=$this->getUrl(['module' => 'search', 'controller' => 'index', 'action' => 'index']) ?>">
        <input type="hidden" name="scopeKey" value="forum_id">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="<?=$this->getTrans('searchInForum') ?>" value="" aria-label="<?=$this->getTrans('searchInForum') ?>">
            <select class="form-select forum-searchbox-scope" name="scopeId" aria-label="<?=$this->getTrans('allForums') ?>">
                <option value=""><?=$this->getTrans('allForums') ?></option>
                <?php foreach ($forums as $forum): ?>
                    <option value="<?=$forum->getId() ?>"<?= ($currentForumId === $forum->getId()) ? ' selected' : '' ?>>
                        <?=$this->escape($forum->getTitle()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>
</div>
