<?php
$articleMapper = new \Modules\Article\Mappers\Article();
$archive = $this->get('archive')
?>

<?php if (!empty($archive)): ?>
    <ul class="list-unstyled">
        <?php foreach ($archive as $archiv): ?>
            <?php $date = new \Ilch\Date($archiv->getDateCreated()); ?>
            <li>
                <a href="<?=$this->getUrl(array('module' => 'article', 'controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>">
                    <?=$date->format("F Y", true) ?>
                </a>
                <span class="badge pull-right" style="margin-top: 7px;"><?=$articleMapper->getCountArticlesByMonthYear($archiv->getDateCreated()) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
