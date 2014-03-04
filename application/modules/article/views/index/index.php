<?php
$articles = $this->get('articles');
$commentMapper = new \Comment\Mappers\Comment();

if (!empty($articles)) {
    foreach($articles as $article) {
        $date = new \Ilch\Date($article->getDateCreated());
        $comments = $commentMapper->getCommentsByKey('articles_'.$article->getId());
?>
<h4>
    <a href="<?=$this->getUrl(array('action' => 'show', 'id' => $article->getId()))?>"><?=$article->getTitle()?></a>
</h4>
<div>
    <span><?=$date->format(null, true)?></span> <i class="fa fa-comment-o"></i> <?=count($comments)?></span>
</div>
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
