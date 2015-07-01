<?php
$articles = $this->get('articles');
$categoryMapper = new \Modules\Article\Mappers\Category();
$commentMapper = new \Modules\Comment\Mappers\Comment();
$articlesCats = $categoryMapper->getCategoryById($this->getRequest()->getParam('id'));
?>

<legend><?=$this->getTrans('catArchives') ?>: <i><?=$articlesCats->getName() ?></i></legend>
<?php if ($articles != ''): ?>
    <?php foreach($articles as $article): ?>
        <?php $date = new \Ilch\Date($article->getDateCreated()); ?>
        <?php $comments = $commentMapper->getCommentsByKey('article/index/show/id/'.$article->getId()); ?>
        <?php $image = $article->getArticleImage(); ?>
        <?php $imageSource = $article->getArticleImageSource(); ?>

        <h4>
            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId()))?>"><?=$article->getTitle()?></a>
        </h4>
        <div>
            <i class="fa fa-clock-o" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>"><?=$date->format('d. F Y', true) ?></a>
            &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('menuCats') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId())) ?>"><?=$articlesCats->getName() ?></a>
            &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment')) ?>"><?=count($comments) ?></a>
            &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('show') ?>"></i> <?=$article->getVisits() ?>
        </div>
        <?php if (!empty($image)): ?>
            <figure>
                <img class="article_image" src="<?=$this->getBaseUrl($image) ?>">
                <?php if (!empty($imageSource)): ?>
                    <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$imageSource ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>
        <hr />
        <?php $content = $article->getContent(); ?>

        <?php if (strpos($content, '[PREVIEWSTOP]') !== false): ?>
            <?php $contentParts = explode('[PREVIEWSTOP]', $content); ?>
            <?=reset($contentParts) ?>
            <br />
            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId())) ?>" class="pull-right"><?=$this->getTrans('readMore') ?></a>
        <?php else: ?>
            <?=$content ?>
        <?php endif; ?>

        <?php if ($article->getAuthorId() != ''): ?>
            <?php $userMapper = new \Modules\User\Mappers\User(); ?>
            <?php $user = $userMapper->getUserById($article->getAuthorId()); ?>
            <?php if ($user != ''): ?>
                <hr />
                <?=$this->getTrans('author') ?>: <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>
            <?php endif; ?>
        <?php endif; ?>
        <br /><br /><br />
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>        
<?php endif; ?>
