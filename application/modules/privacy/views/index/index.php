<?php if ($this->get('privacy') != ''): ?>
    <?php foreach ($this->get('privacy') as $privacy): ?>
        <legend><b><?=$this->escape($privacy->getTitle()) ?></b></legend>
        <p><?=$privacy->getText() ?><br /></p>
    <?php endforeach; ?>

    <b><?=$this->getTrans('source') ?>:</b>
    <?php
    $quellen = [];
    foreach ($this->get('privacy') as $privacy) {
        if ($privacy->getUrlTitle() != '' AND $privacy->getShow() == '1') {
            $quellen[] = '<a href="'.$this->escape($privacy->getUrl()).'" target="_blank">'.$this->escape($privacy->getUrlTitle()).'</a>';
        }
    }

    echo implode(", ", $quellen);
    ?>
<?php else: ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
