<?php
$articleMapper = $this->get('articleMapper');
$keywords = $this->get('keywords');
?>

<h1><?=$this->getTrans('menuKeywords') ?></h1>
<?php if ($keywords != ''): ?>
    <ul class="list-group">
        <?php foreach ($keywords as $keyword => $count): ?>
            <li class="list-group-item">
                <span class="badge"><?=$count ?></span>
                <a href="<?=$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]) ?>"><?=$this->escape($keyword) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noKeywords') ?>
<?php endif; ?>
