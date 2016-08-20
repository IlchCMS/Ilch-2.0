<?php
$articleMapper = $this->get('articleMapper');
$cats = $this->get('cats');
?>

<?php if ($cats != ''): ?>
    <div class="article-box">
        <ul class="list-unstyled">
            <?php foreach ($cats as $cat): ?>
                <li class="ellipsis">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'cats', 'action' => 'show', 'id' => $cat->getId()]) ?>">
                            <?=$this->escape($cat->getName()) ?>
                        </a>
                    </span>
                    <span class="badge">
                        <?=count($articleMapper->getArticlesByCats($cat->getId())) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <?=$this->getTrans('noCats') ?>
<?php endif; ?>
