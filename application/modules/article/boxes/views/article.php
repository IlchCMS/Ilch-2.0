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
                $date = new \Ilch\Date($article->getDateCreated());
            ?>
                <li class="ellipsis" style="line-height: 25px;">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>">
                            <?=$this->escape($article->getTitle()) ?>
                        </a>
                        <?php if (!empty($article->getAuthorName())) : ?>
                            <br>
                        <?=$this->getTrans('by') ?> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $article->getAuthorId()]) ?>"><?=$this->escape($article->getAuthorName()) ?></a>
                        <?php endif; ?>
                        <br>
                        <small><?=$date->format('d.m.y - H:i', true) ?> <?=$this->getTrans('clock') ?></small>
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
