<?php
$articles = $this->get('articles');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
$userMapper = $this->get('userMapper');
?>

<legend><?=$this->getTrans('menuArchives') ?></legend>
<?php if ($articles != ''): ?>
    <ul class="list-group">
        <?php
        foreach ($articles as $article):
            $date = new \Ilch\Date($article->getDateCreated());
            $commentsCount = $commentMapper->getCountComments('article/index/show/id/'.$article->getId());
            $articlesCats = $categoryMapper->getCategoryById($article->getCatId());
        ?>

            <li class="list-group-item">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>"><?=$article->getTitle() ?></a> - 
                <?php if ($article->getAuthorId() != ''): ?>
                    <?php $user = $userMapper->getUserById($article->getAuthorId()); ?>
                    <?php if ($user != ''): ?>
                        <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
                    <?php endif; ?>
                <?php endif; ?>
                <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)]) ?>"><?=$date->format('d. F Y', true) ?></a>
                &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
                &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('menuCats') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId()]) ?>"><?=$articlesCats->getName() ?></a>
                &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
                &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
