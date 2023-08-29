<?php

/** @var \Ilch\View $this */

/** @var Modules\Privacy\Models\Privacy $privacies */
$privacies = $this->get('privacies');

if ($privacies) : ?>
    <?php
    $sources = [];
    foreach ($privacies as $privacy) :
        $link = '<a href="' . $this->escape($privacy->getUrl()) . '" target="_blank" rel="noopener">' . $this->escape($privacy->getUrlTitle()) . '</a>';
        if ($privacy->getUrlTitle() != '' && $privacy->getShow() == '1' && !in_array($link, $sources)) {
            $sources[] = $link;
        }
        ?>
        <h1><b><?=$this->escape($privacy->getTitle()) ?></b></h1>
        <p><?=$this->purify($privacy->getText()) ?><br /></p>
    <?php endforeach; ?>
    <?php if (count($sources) > 0) : ?>
        <b><?=$this->getTrans('source') ?>:</b>
        <?=implode(', ', $sources); ?>
    <?php endif; ?>
<?php else : ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
