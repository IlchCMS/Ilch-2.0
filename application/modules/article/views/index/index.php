<?php
$articles = $this->get('articles');
$commentMapper = new \Modules\Comment\Mappers\Comment();

if (!empty($articles)) {
    foreach($articles as $article) {
        $date = new \Ilch\Date($article->getDateCreated());
        $comments = $commentMapper->getCommentsByKey('articles_'.$article->getId());
        $image = $this->getBaseUrl($article->getArticleImage());
        $imageSource = $article->getArticleImageSource();
?>
<h4>
    <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId()))?>"><?=$article->getTitle()?></a>
</h4>
<div>
    <span><?=$date->format(null, true)?></span> <i class="fa fa-comment-o"></i> <?=count($comments)?></span>
</div>
<?php
if (!empty($image)) {
    echo '<figure><img class="article_image" src="'.$image.'"/>';
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

        echo '<br /><br /><br /><br />';
    }
}
