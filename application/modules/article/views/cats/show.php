<?php
$articles = $this->get('articles');
$categoryMapper = new \Modules\Article\Mappers\Category();
$commentMapper = new \Modules\Comment\Mappers\Comment();
?>

<?php if ($articles != ''): ?>
    <?php foreach($articles as $article): ?>
        <?php $date = new \Ilch\Date($article->getDateCreated()); ?>
        <?php $comments = $commentMapper->getCommentsByKey('article/index/show/id/'.$article->getId()); ?>
        <?php $image = $article->getArticleImage(); ?>
        <?php $imageSource = $article->getArticleImageSource(); ?>
        <?php $articlesCats = $categoryMapper->getCategoryById($article->getCatId()); ?>

        <h4>
            <a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId()))?>"><?=$articlesCats->getName()?></a>: <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId()))?>"><?=$article->getTitle()?></a>
        </h4>
        <div>
            <?=$date->format(null, true)?></span>
        </div>
        <div>
            <a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment'))?>"><i class="fa fa-comment-o"></i> <?=count($comments)?></a> <i class="fa fa-eye"></i> <?=$article->getVisits() ?>
        </div>
        <?php if (!empty($image)): ?>
            <figure><img class="article_image" src="'.$this->getBaseUrl($image).'"/>
            <?php if (!empty($imageSource)): ?>
                <figcaption class="article_image_source">'.$this->getTrans('articleImageSource').': '.$imageSource.'</figcaption><figure>
            <?php endif; ?>
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
