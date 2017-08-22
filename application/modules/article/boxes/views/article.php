<?php
$articles = $this->get('articles');

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}

$displayedArticles = 0;
?>

<link href="<?=$this->getBoxUrl('static/css/article.css') ?>" rel="stylesheet">

<?php if (!empty($articles)): ?>
    <div class="article-box">
        <ul class="list-unstyled">
            <?php foreach ($articles as $article):
                if (!is_in_array($this->get('readAccess'), explode(',', $article->getReadAccess())) && $adminAccess == false) {
                    continue;
                }

                $displayedArticles++;
            ?>
                <li class="ellipsis">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>">
                            <?=$this->escape($article->getTitle()) ?>
                        </a>
                    </span>
                </li>
            <?php endforeach; ?>
            <?php if ($displayedArticles == 0) : ?>
                <?=$this->getTrans('noArticles') ?>
            <?php endif; ?>
        </ul>
    </div>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
