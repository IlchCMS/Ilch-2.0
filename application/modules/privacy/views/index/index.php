<?php if ($this->get('privacy') != ''): ?>
    <?php foreach ($this->get('privacy') as $privacy): ?>
        <h1><b><?=$this->escape($privacy->getTitle()) ?></b></h1>
        <p><?=$this->purify($privacy->getText()) ?><br /></p>
    <?php endforeach; ?>

    <b><?=$this->getTrans('source') ?>:</b>
    <?php
    $sources = [];
    foreach ($this->get('privacy') as $privacy) {
        if ($privacy->getUrlTitle() != '' && $privacy->getShow() == '1') {
            $sources[] = '<a href="'.$this->escape($privacy->getUrl()).'" target="_blank" rel="noopener">'.$this->escape($privacy->getUrlTitle()).'</a>';
        }
    }

    echo implode(', ', $sources);
    ?>
<?php else: ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
