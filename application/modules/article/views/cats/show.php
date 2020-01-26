<?php
$articles = $this->get('articles');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
$articlesCats = $categoryMapper->getCategoryById($this->getRequest()->getParam('id'));

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<h1><?=$this->getTrans('catArchives') ?>: <i><?=$this->escape($articlesCats->getName()) ?></i></h1>
<?php if ($articles != ''):
    $displayedArticles = 0;

    foreach ($articles as $article):
        if (!is_in_array($this->get('readAccess'), explode(',', $article->getReadAccess())) && $adminAccess == false) {
            continue;
        }

        $displayedArticles++;

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
        <h2><a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a></h2>
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
            <?=reset($contentParts) ?>
            <br />
            <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>" class="pull-right"><?=$this->getTrans('readMore') ?></a>
        <?php else: ?>
            <?=$content ?>
        <?php endif; ?>
        <hr />
        <div>
            <?php if ($article->getAuthorId() != ''): ?>
                <?php if ($article->getAuthorName() != ''): ?>
                    <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $article->getAuthorId()]) ?>"><?=$this->escape($article->getAuthorName()) ?></a>&nbsp;&nbsp;
                <?php endif; ?>
            <?php endif; ?>
            <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
            &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
            &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', ') ?>
            &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
            &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
            <?php if ($article->getTopArticle()) : ?>
            &nbsp;&nbsp;<i class="fa fa-star-o" title="<?=$this->getTrans('topArticle') ?>"></i>
            <?php endif; ?>
            <?php if ($this->get('article_articleRating')) : ?>
                <?php
                $votes = explode(',', $article->getVotes());
                $countOfVotes = count($votes) - 1;
                ?>
                <?php if ($this->getUser() && in_array($this->getUser()->getId(), $votes) == false) : ?>
                    <a class="btn btn-sm btn-default btn-hover-success" href="<?=$this->getUrl(['id' => $article->getId(), 'action' => 'vote', 'from' => 'show', 'catId' => $this->getRequest()->getParam('id')]) ?>" title="<?=$this->getTrans('iLike') ?>">
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
                echo implode(', ',$keywordsList); ?>
            <?php endif; ?>
        </div>
        <br /><br /><br />
    <?php endforeach; ?>
    <?php if ($displayedArticles > 0) : ?>
        <div class="pull-right">
            <?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noArticles') ?>
    <?php endif; ?>

<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
