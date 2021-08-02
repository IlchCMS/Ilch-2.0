<?php
$articleMapper = $this->get('articleMapper');
$cats = $this->get('cats');
?>

<link href="<?=$this->getBoxUrl('static/css/article.css') ?>" rel="stylesheet">

<?php if ($cats != ''): ?>
    <div class="article-box">
        <ul class="list-unstyled">
            <?php foreach ($cats as $cat): ?>
                <?php
                    $count = $articleMapper->getCountArticlesByCatIdAccess($cat->getId(), $this->get('readAccess'));

                    if ($count === 0) {
                        continue;
                    }
                ?>
                <li class="ellipsis">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'cats', 'action' => 'show', 'id' => $cat->getId()]) ?>">
                            <?=$this->escape($cat->getName()) ?>
                        </a>
                    </span>
                    <span class="badge">
                        <?=$count ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <?=$this->getTrans('noCats') ?>
<?php endif; ?>
