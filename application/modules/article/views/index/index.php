<?php
$articles = $this->get('articles');
$pages = $this->get('pageCount');
$categoryMapper = new \Modules\Article\Mappers\Category();
$commentMapper = new \Modules\Comment\Mappers\Comment();
$config = \Ilch\Registry::get('config');
?>

<?php if ($articles != ''): ?>
    <?php foreach($articles as $article): ?>
        <?php $date = new \Ilch\Date($article->getDateCreated()); ?>
        <?php $commentsCount = $commentMapper->getCountComments('article/index/show/id/'.$article->getId()); ?>
        <?php $image = $article->getArticleImage(); ?>
        <?php $imageSource = $article->getArticleImageSource(); ?>
        <?php $articlesCats = $categoryMapper->getCategoryById($article->getCatId()); ?>

        <div class="col-lg-12 hidden-xs" style="padding-left: 0px;">
            <div class="col-lg-8" style="padding-left: 0px;">
                <h4><a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId())) ?>"><?=$articlesCats->getName() ?></a></h4>
            </div>
            <div class="col-lg-4 text-right" style="padding-right: 0px;">
                <h4><a href="<?=$this->getUrl(array('controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>"><?=$date->format('d. F Y', true) ?></a></h4>
            </div>
        </div>
        <h3><a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId())) ?>"><?=$article->getTitle() ?></a></h3>
        <?php if (!empty($image)): ?>
            <figure>
                <img class="article_image" src="<?=$this->getBaseUrl($image) ?>">
                <?php if (!empty($imageSource)): ?>
                    <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$imageSource ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <?php $content = $article->getContent(); ?>

        <?php if (strpos($content, '[PREVIEWSTOP]') !== false): ?>
            <?php $contentParts = explode('[PREVIEWSTOP]', $content); ?>
            <?=reset($contentParts) ?>
            <br />
            <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId())) ?>" class="pull-right"><?=$this->getTrans('readMore') ?></a>
        <?php else: ?>
            <?=$content ?>
        <?php endif; ?>
        <hr />
        <div>
            <?php if ($article->getAuthorId() != ''): ?>
                <?php $userMapper = new \Modules\User\Mappers\User(); ?>
                <?php $user = $userMapper->getUserById($article->getAuthorId()); ?>
                <?php if ($user != ''): ?>
                    <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
                <?php endif; ?>
            <?php endif; ?>
            <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'archive', 'action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true))) ?>"><?=$date->format('d. F Y', true) ?></a>
            &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
            &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <a href="<?=$this->getUrl(array('controller' => 'cats', 'action' => 'show', 'id' => $article->getCatId())) ?>"><?=$articlesCats->getName() ?></a>
            &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId().'#comment')) ?>"><?=$commentsCount ?></a>
            &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
        </div>
        <br /><br /><br />
    <?php endforeach; ?>
    <div class="col-lg-12 col-md-12 col-xs-12">
    <center>
    <ul class="pagination pagination-lg">
        <?php if ($this->getRequest()->getParam('page') == 0): ?>
            <li><a class="btn disabled" href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'page' => $this->getRequest()->getParam('page'))) ?>">&laquo;</a></li>
        <?php else: ?>
            <li><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'page' => $this->getRequest()->getParam('page') - 1)) ?>">&laquo;</a></li>
        <?php endif; ?>
        <?php for ($i = 0; $i <= floor($pages[0]->getPageCount() /  $config->get('article_p_page')); $i++): ?>
            <?php if ($this->getRequest()->getParam('page') == $i): ?>
                <li class="active"><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'page' => $i)) ?>"><?php echo $i+1; ?></a></li>
            <?php else: ?>
                <li><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'page' => $i)) ?>"><?php echo $i+1; ?></a></li>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($this->getRequest()->getParam('page') == floor($pages[0]->getPageCount() /  $config->get('article_p_page'))): ?>
            <li><a class="btn disabled" href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'page' => $this->getRequest()->getParam('page'))) ?>">&raquo;</a></li>
        <?php else: ?>
            <li><a href="<?=$this->getUrl(array('controller' => 'index', 'action' => 'index', 'page' => $this->getRequest()->getParam('page') + 1)) ?>">&raquo;</a></li>
        <?php endif; ?>   
    </ul>
    </center>
    </div>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
