<?php
$articles = $this->get('articles');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
$userMapper = $this->get('userMapper');

$adminAccess = null;
if ($this->getUser()) {
    $adminAccess = $this->getUser()->isAdmin();
}
?>

<h1><?=$this->getTrans('menuArchives') ?></h1>
<?php if ($articles != ''): ?>
    <ul class="list-group">
        <?php
        $displayedArticles = 0;

        foreach ($articles as $article):
            if (!is_in_array($this->get('readAccess'), explode(',', $article->getReadAccess())) && $adminAccess == false) {
                continue;
            }

            $displayedArticles++;

            $date = new \Ilch\Date($article->getDateCreated());
            $commentsCount = $commentMapper->getCountComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()));

            $catIds = explode(",", $article->getCatId());
            $categories = '';
            foreach ($catIds as $catId) {
                $articlesCats = $categoryMapper->getCategoryById($catId);
                $categories .= '<a href="'.$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $catId]).'">'.$articlesCats->getName().'</a>, ';
            }
        ?>
            <li class="list-group-item">
                <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a> - 
                <?php if ($article->getAuthorId() != ''): ?>
                    <?php $user = $userMapper->getUserById($article->getAuthorId()); ?>
                    <?php if ($user != ''): ?>
                        <i class="fa fa-user" title="<?=$this->getTrans('author') ?>"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) ?>"><?=$this->escape($user->getName()) ?></a>&nbsp;&nbsp;
                    <?php endif; ?>
                <?php endif; ?>
                <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'year' => $date->format("Y", true), 'month' => $date->format("m", true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
                &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
                &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', '); ?>
                &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
                &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
                <?php if ($article->getKeywords() != ''): ?>
                    <i class="fa fa-hashtag"></i> <?=$article->getKeywords() ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($displayedArticles > 0) : ?>
        <div class="pull-right">
            <?=$this->get('pagination')->getHtml($this, ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]) ?>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noArticles') ?>
    <?php endif; ?>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
