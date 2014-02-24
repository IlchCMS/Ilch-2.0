<?php
$articles = $this->get('articles');

if (!empty($articles)) {
    foreach($articles as $article) {
        $date = new \Ilch\Date($article->getDateCreated());

        echo '<strong>'.$date->format(null, true).'</strong>';
        echo '<hr />'; 
        $content = $article->getContent();
        echo '<h4><a href="'.$this->getUrl(array('action' => 'show', 'id' => $article->getId())).'">'.$article->getTitle().'</a></h4>';
        echo '<br />';

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
