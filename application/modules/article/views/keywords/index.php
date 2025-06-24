<?php

/** @var \Ilch\View $this */

/** @var \Modules\Article\Mappers\Article $articleMapper */
$articleMapper = $this->get('articleMapper');
$keywords = $this->get('keywords');
?>

<h1><?=$this->getTrans('menuKeywords') ?></h1>
<?php if ($keywords != ''): ?>
    <ul class="list-group">
        <?php foreach ($keywords as $keyword => $count): ?>
            <li class="list-group-item">
                <span class="badge bg-secondary"><?=$count ?></span>
                <a href="<?=$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => urlencode($keyword)]) ?>"><?=$this->escape($keyword) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noKeywords') ?>
<?php endif; ?>
