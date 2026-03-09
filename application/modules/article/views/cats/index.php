<?php

/** @var \Ilch\View $this */

/** @var \Modules\Article\Mappers\Article $articleMapper */
$articleMapper = $this->get('articleMapper');
/** @var \Modules\Article\Models\Category[]|null $cats */
$cats = $this->get('cats');
?>

<h1><?=$this->getTrans('menuCats') ?></h1>
<?php if ($cats != ''): ?>
    <ul class="list-group">
        <?php foreach ($cats as $cat): ?>
            <?php
            $count = $articleMapper->getCountArticlesByCatIdAccess($cat->getId(), $this->get('readAccess'));

            if ($count === 0) {
                continue;
            }
            ?>
            <li class="list-group-item">
                <span class="badge bg-secondary"><?=$count ?></span>
                <a href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $cat->getId()]) ?>"><?=$this->escape($cat->getName()) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noCats') ?>
<?php endif; ?>
