<?php
$articles = $this->get('articles')
?>

<?php if (!empty($articles)): ?>
    <ul class="list-unstyled">
        <?php foreach ($articles as $article): ?>
            <li>
                <a href="<?=$this->getUrl(array('module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $article->getId())) ?>">
                    <?=$article->getTitle() ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?=$this->getTrans('noArticles') ?>
<?php endif; ?>
