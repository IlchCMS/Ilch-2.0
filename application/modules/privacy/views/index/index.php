<?php if ($this->get('privacyShow') != ''): ?>
    <?php foreach ($this->get('privacy') as $privacy): ?>
        <?php if ($this->escape($privacy->getShow()) == '1'): ?>
            <legend><b><?=$this->escape($privacy->getTitle()) ?></b></legend>
            <?=$privacy->getText() ?><br /><br />
        <?php endif; ?>
    <?php endforeach; ?>

    <b>Quellen: </b>
    <?php foreach ($this->get('privacy') as $privacy): ?>
        <?php if ($this->escape($privacy->getUrlTitle()) != '' AND $this->escape($privacy->getShow()) == '1'): ?>
            <a href="<?=$this->escape($privacy->getUrl()) ?>" target="_blank"><?=$this->escape($privacy->getUrlTitle()) ?></a>,
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <?=$this->getTrans('noPrivacy') ?>
<?php endif; ?>
