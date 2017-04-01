<link href="<?=$this->getBoxUrl('static/css/article.css') ?>" rel="stylesheet">

<?php if (!empty($this->get('keywordsList'))): ?>
    <div class="article-keywords-box">
        <?php foreach ($this->get('keywordsList') as $keyword => $count) {
            if ($count >= 5) {
                echo '<span style="font-size:'.$this->get('keywordsH5').'px">'.$keyword.'</span> ';
            } elseif ($count == 4) {
                echo '<span style="font-size:'.$this->get('keywordsH4').'px">'.$keyword.'</span> ';
            } elseif ($count == 3) {
                echo '<span style="font-size:'.$this->get('keywordsH3').'px">'.$keyword.'</span> ';
            } elseif ($count == 2) {
                echo '<span style="font-size:'.$this->get('keywordsH2').'px">'.$keyword.'</span> ';
            } else {
                echo $keyword.' ';
            }
        } ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noKeywords') ?>
<?php endif; ?>
