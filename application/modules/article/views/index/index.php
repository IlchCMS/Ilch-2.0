<?php
$articles = $this->get('articles');

if (!empty($articles)) {
    foreach($articles as $article) {
        echo '<h4>'.$article->getTitle().'</h4>';
        echo '<br />';
        echo $article->getContent();
        echo '<hr /><br />'; 
    }
}