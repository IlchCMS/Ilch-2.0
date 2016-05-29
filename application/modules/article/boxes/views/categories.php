<?php
$articleMapper = $this->get('articleMapper');
$cats = $this->get('cats');
?>

<?php if ($cats != ''): ?>
    <ul class="list-unstyled">
        <?php foreach ($cats as $cat): ?>
            <li>
                <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'cats', 'action' => 'show', 'id' => $cat->getId()]) ?>">
                    <?=$cat->getName() ?>
                </a>
                <span class="badge pull-right" style="margin-top: 7px;"><?=count($articleMapper->getArticlesByCats($cat->getId())) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noCats') ?>
<?php endif; ?>
