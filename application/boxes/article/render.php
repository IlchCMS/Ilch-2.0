<?php $articles = $this->get('articles');?>
<?php
if (!empty($articles)) {
    echo '<ul class="list-unstyled">';
    foreach($articles as $article) {
        echo '<li><a href="'.$this->getUrl(array('module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $article->getId())).'">'
            .$this->escape($article->getTitle())
            .'</a></li>';
    }
    echo '</ul>';
}
?>