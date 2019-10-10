<link href="<?=$this->getBoxUrl('static/css/article.css') ?>" rel="stylesheet">

<?php if (!empty($this->get('keywordsList'))): ?>
    <div class="article-keywords-box">
        <?php foreach ($this->get('keywordsList') as $keyword => $count) {
            $keyword = $this->escape($keyword);
            if ($count >= 5) {
                echo '<span style="font-size:'.$this->get('keywordsH5').'px"><a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]).'">'.$keyword.'</a></span> ';
            } elseif ($count == 4) {
                echo '<span style="font-size:'.$this->get('keywordsH4').'px"><a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]).'">'.$keyword.'</a></span> ';
            } elseif ($count == 3) {
                echo '<span style="font-size:'.$this->get('keywordsH3').'px"><a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]).'">'.$keyword.'</a></span> ';
            } elseif ($count == 2) {
                echo '<span style="font-size:'.$this->get('keywordsH2').'px"><a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]).'">'.$keyword.'</a></span> ';
            } else {
                echo '<a href="'.$this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]).'">'.$keyword.'</a> ';
            }
        } ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noKeywords') ?>
<?php endif; ?>
