<?php

/** @var \Ilch\View $this */
?>
<?php if ($this->get('hasReadAccess')) : ?>
    <?php
    /** @var \Modules\Article\Models\Article $article */
    $article = $this->get('article');
    /** @var \Modules\Article\Mappers\Category $categoryMapper */
    $categoryMapper = $this->get('categoryMapper');
    /** @var \Modules\User\Mappers\User $userMapper */
    $userMapper = $this->get('userMapper');
    $content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
    $preview = $this->getRequest()->getParam('preview');
    /** @var \Ilch\Config\Database $config */
    $config = $this->get('config');
    $date = new \Ilch\Date($article->getDateCreated() ?? '');

    $catIds = explode(',', $article->getCatId());
    $categories = '';
    foreach ($catIds as $catId) {
        if ($catId) {
            $articlesCats = $categoryMapper->getCategoryById($catId);
            $categories .= '<a href="'.$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $catId]).'">'.$this->escape($articlesCats->getName()).'</a>, ';
        }
    }
    ?>
    <?php if ($preview): ?>
        <div class="article_preview"></div>
    <?php endif; ?>

    <?php if ($article->getTeaser()): ?>
        <h3><?=$this->escape($article->getTeaser()) ?></h3>
    <?php endif; ?>
    <h2><a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a></h2>
    <?php if (!empty($article->getImage())): ?>
        <figure>
            <img class="article_image" src="<?=$this->getBaseUrl($article->getImage()) ?>" />
            <?php if (!empty($article->getImageSource())): ?>
                <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$this->escape($article->getImageSource()) ?></figcaption>
            <?php endif; ?>
        </figure>
    <?php endif; ?>
    <div class="ck-content"><?=$this->purify($content) ?></div>
    <hr />
    <div>
        <?php
        if ($article->getAuthorId() != ''):
            $user = $userMapper->getUserById($article->getAuthorId());
            if ($user != ''): ?>
                <i class="fa-solid fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
            <?php endif; ?>
        <?php endif; ?>
        <i class="fa-solid fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
        &nbsp;&nbsp;<i class="fa-regular fa-clock" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
        &nbsp;&nbsp;<i class="fa-regular fa-folder-open" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', ') ?>
        &nbsp;&nbsp;<i class="fa-regular fa-comment" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$this->get('commentsCount') ?></a>
        &nbsp;&nbsp;<i class="fa-regular fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
        <?php if ($article->getTopArticle()) : ?>
        &nbsp;&nbsp;<i class="fa-regular fa-star" title="<?=$this->getTrans('topArticle') ?>"></i>
        <?php endif; ?>
        <?php if ($config->get('article_articleRating')) : ?>
            <?php
            $votes = explode(',', $article->getVotes());
            $countOfVotes = count($votes) - 1;
            ?>
            <?php if ($this->getUser() && in_array($this->getUser()->getId(), $votes) == false) : ?>
                <a class="btn btn-sm btn-secondary btn-hover-success" href="<?=$this->getUrl(['id' => $article->getId(), 'action' => 'vote', 'from' => 'show']) ?>" title="<?=$this->getTrans('iLike') ?>">
                    <i class="fa-solid fa-thumbs-up"></i> <?=$countOfVotes ?>
                </a>
            <?php else: ?>
                <button class="btn btn-sm btn-secondary btn-success">
                    <i class="fa-solid fa-thumbs-up"></i> <?=$countOfVotes ?>
                </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($article->getKeywords() != ''): ?>
            <br /><i class="fa-solid fa-hashtag"></i>
            <?php $keywordsList = $article->getKeywords();
            $keywordsListArray = explode(', ', $keywordsList);
            $keywordsList = [];
            foreach ($keywordsListArray as $keyword) {
                $keywordsList[] = '<a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => urlencode($keyword)]).'">'.$this->escape($keyword).'</a>';
            }
            echo implode(', ', $keywordsList); ?>
        <?php endif; ?>
    </div>

    <?php if (empty($preview) && !$article->getCommentsDisabled()): ?>
        <?php
            $commentsClass = new Ilch\Comments();
            echo $commentsClass->getComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()), $article, $this);
        ?>
    <?php endif; ?>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
