<?php
$articles = $this->get('articles');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
?>

<h1><?=$this->getTrans('menuArticle') ?></h1>
<?php if ($articles != ''):
    foreach ($articles as $article):
        $date = new \Ilch\Date($article->getDateCreated());
        $commentsCount = $commentMapper->getCountComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()));
        $image = $article->getImage();
        $imageSource = $article->getImageSource();

        $catIds = explode(',', $article->getCatId());
        $categories = '';
        foreach ($catIds as $catId) {
            $articlesCats = $categoryMapper->getCategoryById($catId);
            $categories .= '<a href="'.$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $catId]).'">'.$this->escape($articlesCats->getName()).'</a>, ';
        }
    ?>
        <?php if ($article->getTeaser()): ?>
            <h3><?=$this->escape($article->getTeaser()) ?></h3>
        <?php endif; ?>
        <h2><a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a></h2>
        <?php if (!empty($image)): ?>
            <figure>
                <img class="article_image" src="<?=$this->getBaseUrl($image) ?>">
                <?php if (!empty($imageSource)): ?>
                    <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$this->escape($imageSource) ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <?php $content = $article->getContent(); ?>

        <?php if (strpos($content, '[PREVIEWSTOP]') !== false): ?>
            <?php $contentParts = explode('[PREVIEWSTOP]', $content); ?>
            <?=$this->purify(reset($contentParts)) ?>
            <br />
            <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>" class="float-end"><?=$this->getTrans('readMore') ?></a>
        <?php else: ?>
            <?=$this->purify($content) ?>
        <?php endif; ?>
        <hr />
        <div>
            <?php if ($article->getAuthorId() != ''): ?>
                <?php if ($article->getAuthorName() != ''): ?>
                    <i class="fa-solid fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $article->getAuthorId()]) ?>"><?=$this->escape($article->getAuthorName()) ?></a>&nbsp;&nbsp;
                <?php endif; ?>
            <?php endif; ?>
            <i class="fa-solid fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
            &nbsp;&nbsp;<i class="fa-regular fa-clock" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
            &nbsp;&nbsp;<i class="fa-regular fa-folder-open" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', ') ?>
            &nbsp;&nbsp;<i class="fa-regular fa-comment" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
            &nbsp;&nbsp;<i class="fa-regular fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
            <?php if ($article->getTopArticle()) : ?>
            &nbsp;&nbsp;<i class="fa-regular fa-star" title="<?=$this->getTrans('topArticle') ?>"></i>
            <?php endif; ?>
            <?php if ($this->get('article_articleRating')) : ?>
                <?php
                $votes = explode(',', $article->getVotes());
                $countOfVotes = count($votes) - 1;
                ?>
                <?php if ($this->getUser() && in_array($this->getUser()->getId(), $votes) == false) : ?>
                    <a class="btn btn-sm btn-secondary btn-hover-success" href="<?=$this->getUrl(['id' => $article->getId(), 'action' => 'vote', 'from' => 'index']) ?>" title="<?=$this->getTrans('iLike') ?>">
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
        <br /><br /><br />
    <?php endforeach; ?>
        <div class="float-end">
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
        </div>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
