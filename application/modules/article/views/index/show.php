<?php if ($this->get('hasReadAccess')) : ?>
    <?php
    $article = $this->get('article');
    $categoryMapper = $this->get('categoryMapper');
    $userMapper = $this->get('userMapper');
    $content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
    $preview = $this->getRequest()->getParam('preview');
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
    <?=$this->purify($content) ?>
    <hr />
    <div>
        <?php
        if ($article->getAuthorId() != ''):
            $user = $userMapper->getUserById($article->getAuthorId());
            if ($user != ''): ?>
                <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
            <?php endif; ?>
        <?php endif; ?>
        <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
        &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
        &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', ') ?>
        &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$this->get('commentsCount') ?></a>
        &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
        <?php if ($article->getTopArticle()) : ?>
        &nbsp;&nbsp;<i class="fa fa-star-o" title="<?=$this->getTrans('topArticle') ?>"></i>
        <?php endif; ?>
        <?php if ($config->get('article_articleRating')) : ?>
            <?php
            $votes = explode(',', $article->getVotes());
            $countOfVotes = count($votes) - 1;
            ?>
            <?php if ($this->getUser() && in_array($this->getUser()->getId(), $votes) == false) : ?>
                <a class="btn btn-sm btn-default btn-hover-success" href="<?=$this->getUrl(['id' => $article->getId(), 'action' => 'vote', 'from' => 'show']) ?>" title="<?=$this->getTrans('iLike') ?>">
                    <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                </a>
            <?php else: ?>
                <button class="btn btn-sm btn-default btn-success">
                    <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($article->getKeywords() != ''): ?>
            <br /><i class="fa fa-hashtag"></i>
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
