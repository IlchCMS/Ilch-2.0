<?php
$articles = $this->get('articles');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
$userMapper = $this->get('userMapper');
?>

<h1><?=$this->getTrans('menuArchives') ?></h1>
<?php if ($articles != ''): ?>
    <ul class="list-group">
        <?php
        foreach ($articles as $article):
            $date = new \Ilch\Date($article->getDateCreated());
            $commentsCount = $commentMapper->getCountComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()));

            $catIds = explode(',', $article->getCatId());
            $categories = '';
            foreach ($catIds as $catId) {
                $articlesCats = $categoryMapper->getCategoryById($catId);
                $categories .= '<a href="'.$this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $catId]).'">'.$this->escape($articlesCats->getName()).'</a>, ';
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
                <i class="fa fa-calendar" title="<?=$this->getTrans('date') ?>"></i> <a href="<?=$this->getUrl(['action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a>
                &nbsp;&nbsp;<i class="fa fa-clock-o" title="<?=$this->getTrans('time') ?>"></i> <?=$date->format('H:i', true) ?>
                &nbsp;&nbsp;<i class="fa fa-folder-open-o" title="<?=$this->getTrans('cats') ?>"></i> <?=rtrim($categories, ', ') ?>
                &nbsp;&nbsp;<i class="fa fa-comment-o" title="<?=$this->getTrans('comments') ?>"></i> <a href="<?=$this->getUrl(['controller' => 'index', 'action' => 'show', 'id' => $article->getId().'#comment']) ?>"><?=$commentsCount ?></a>
                &nbsp;&nbsp;<i class="fa fa-eye" title="<?=$this->getTrans('hits') ?>"></i> <?=$article->getVisits() ?>
                <?php if ($this->get('article_articleRating')) : ?>
                    <?php
                    $votes = explode(',', $article->getVotes());
                    $countOfVotes = count($votes) - 1;
                    ?>
                    <?php if ($this->getUser() && in_array($this->getUser()->getId(), $votes) == false) : ?>
                        <a class="btn btn-sm btn-outline-secondary btn-hover-success" href="<?=$this->getUrl(['id' => $article->getId(), 'action' => 'vote', 'from' => 'index']) ?>" title="<?=$this->getTrans('iLike') ?>">
                            <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary btn-success">
                            <i class="fa fa-thumbs-up"></i> <?=$countOfVotes ?>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($article->getKeywords() != ''): ?>
                    <br /><i class="fa fa-hashtag"></i>
                    <?php $keywordsList = $article->getKeywords();
                    $keywordsListArray = explode(', ', $keywordsList);
                    $keywordsList = [];
                    foreach ($keywordsListArray as $keyword) {
                        $keywordsList[] = '<a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => urlencode($keyword)]).'">'.$this->escape($keyword).'</a>';
                    }
                    echo implode(', ', $keywordsList); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="float-end">
        <?=$this->get('pagination')->getHtml($this, ['action' => 'index', 'id' => $this->getRequest()->getParam('id')]) ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
