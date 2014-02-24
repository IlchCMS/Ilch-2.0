<?php
$article = $this->get('article');
$content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
echo '<h4>'.$article->getTitle().'</h4>';
echo '<br />';

echo $content;
