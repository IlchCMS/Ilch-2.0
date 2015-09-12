<?php
$articles = $this->get('articles');
$categoryMapper = new \Modules\Article\Mappers\Category();
$commentMapper = new \Modules\Comment\Mappers\Comment();
?>

<legend><?=$this->getTrans('menuArchives') ?></legend>
<?php if ($articles != ''): ?>
<ul class="list-group">
    <?php foreach($articles as $article): ?>
        <?php $date = new \Ilch\Date($article->getDateCreated()); ?>
        <?php $comments = $commentMapper->getCommentsByKey('article/index/show/id/'.$article->getId()); ?>
        <?php $articlesCats = $categoryMapper->getCategoryById($article->getCatId()); ?>

        <li class="list-group-item">
            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId())) ?>"><?=$article->getTitle() ?></a> - 
            <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(array('action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>"><?=$date->format('d. F Y', true) ?></a>
            &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('clock') ?>"></i> <?=$date->format('H:i', true) ?>
            &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('menuCats') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId())) ?>"><?=$articlesCats->getName() ?></a>
            &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment')) ?>"><?=count($comments) ?></a>
            &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
