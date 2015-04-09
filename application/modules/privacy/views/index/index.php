<?php foreach ($this->get('privacy') as $privacy): ?>
    <legend><b><?=$this->escape($privacy->getTitle()) ?></b></legend>
    <?=$privacy->getText() ?><br /><br />
<?php endforeach; ?>

<b>Quellen: </b>
<?php foreach ($this->get('privacy') as $privacy): ?>
    <?php if ($this->escape($privacy->getUrlTitle()) != ''): ?>
        <a href="<?=$this->escape($privacy->getUrl()) ?>" target="_blank"><?=$this->escape($privacy->getUrlTitle()) ?></a>,
    <?php endif; ?>
<?php endforeach; ?>
