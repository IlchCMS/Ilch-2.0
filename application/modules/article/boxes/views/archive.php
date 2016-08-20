<?php
$articleMapper = $this->get('articleMapper');
$archive = $this->get('archive');
?>

<?php if (!empty($archive)): ?>
    <div class="article-box">
        <ul class="list-unstyled">
            <?php foreach ($archive as $archiv): ?>
                <?php $date = new \Ilch\Date($archiv->getDateCreated()); ?>
                <li class="ellipsis">
                    <span class="ellipsis-item">
                        <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)]) ?>">
                            <?=$date->format("F Y", true) ?>
                        </a>
                    </span>
                    <span class="badge">
                        <?=$articleMapper->getCountArticlesByMonthYear($archiv->getDateCreated()) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
