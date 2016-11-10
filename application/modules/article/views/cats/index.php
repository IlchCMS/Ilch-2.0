<?php
$articleMapper = $this->get('articleMapper');
$cats = $this->get('cats');
?>

<legend><?=$this->getTrans('menuCats') ?></legend>
<?php if ($cats != ''): ?>
    <ul class="list-group">
        <?php foreach ($cats as $cat): ?>
            <li class="list-group-item">
                <span class="badge"><?=count($articleMapper->getArticlesByCats($cat->getId())) ?></span>
                <a href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $cat->getId()]) ?>"><?=$this->escape($cat->getName()) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noCats') ?>
<?php endif; ?>
