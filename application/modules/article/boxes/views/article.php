<?php $articles = $this->get('articles'); ?>

<link href="<?=$this->getBoxUrl('static/css/article.css') ?>" rel="stylesheet">

<?php if (!empty($articles)): ?>
    <div class="article-box">
        <ul class="list-unstyled">
            <?php foreach ($articles as $article): ?>
                <li class="ellipsis">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>">
                            <?=$this->escape($article->getTitle()) ?>
                        </a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
