<?php
$articles = $this->get('articles');
$commentMapper = new \Modules\Comment\Mappers\Comment();

if (!empty($articles)) {
    foreach($articles as $article) {
        $date = new \Ilch\Date($article->getDateCreated());
        $comments = $commentMapper->getCommentsByKey('article/index/show/id/'.$article->getId());
        $image = $article->getArticleImage();
        $imageSource = $article->getArticleImageSource();
?>
<h4>
    <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId()))?>"><?=$article->getTitle()?></a>
</h4>
<div>
    <?=$date->format(null, true)?></span>
</div>
<div>
    <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId().'#comment'))?>"><i class="fa fa-comment-o"></i> <?=count($comments)?></a> <i class="fa fa-eye"></i> <?=$article->getVisits() ?>
</div>
<?php
if (!empty($image)) {
    echo '<figure><img class="article_image" src="'.$this->getBaseUrl($image).'"/>';
    if (!empty($imageSource)) {
        echo '<figcaption class="article_image_source">'.$this->getTrans('articleImageSource').': '.$imageSource.'</figcaption><figure>';
    }
}
?>
<hr />
<?php
        $content = $article->getContent();

        if (strpos($content, '[PREVIEWSTOP]') !== false) {
            $contentParts = explode('[PREVIEWSTOP]', $content);
            echo reset($contentParts);
            echo '<br /><a href="'.$this->getUrl(array('action' => 'show', 'id' => $article->getId())).'" class="pull-right">'.$this->getTrans('readMore').'</a>';
        } else {
            echo $content;
        }   

        if ($article->getAutorId() != ''){
            $userMapper = new \Modules\User\Mappers\User();
            $user = $userMapper->getUserById($article->getAutorId());
            if ($user != ''){
                echo '<hr />';
                echo $this->getTrans('autor').': <a href="'.$this->getUrl(array('module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId())).'">'.$this->escape($user->getName()).'</a>';
                echo '<hr />';
            }
        }
        echo '<br /><br /><br /><br />';
    }
}
