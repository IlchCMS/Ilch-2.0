<?php

/** @var \Ilch\View $this */

/** @var \Modules\Shoutbox\Models\Shoutbox $entry */
$entry = $this->get('entry');

$date = new \Ilch\Date($entry->getTime());
?>
<h1><?=$this->getTrans('editEntry') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label class="col-xl-2 col-form-label" for="shoutboxName">
            <?=$this->getTrans('from') ?>
        </label>
        <div class="col-xl-4">
            <?php if ($entry->getUid()) : ?>
                <p class="form-control-plaintext">
                    <a href="<?=$this->getUrl('user/profil/index/user/' . $entry->getUid()) ?>" target="_blank"><?=$this->escape($this->get('authorName')) ?></a>
                </p>
            <?php else : ?>
                <input type="text"
                       class="form-control"
                       id="shoutboxName"
                       name="shoutbox_name"
                       maxlength="100"
                       value="<?=$this->escape($this->originalInput('shoutbox_name', $entry->getName())) ?>"
                       required>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-xl-2 col-form-label">
            <?=$this->getTrans('date') ?>
        </label>
        <div class="col-xl-4">
            <p class="form-control-plaintext"><?=$date->format('d.m.Y H:i', true) ?></p>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-xl-2 col-form-label" for="shoutboxTextarea">
            <?=$this->getTrans('message') ?>
        </label>
        <div class="col-xl-8">
            <textarea class="form-control"
                      style="resize: vertical"
                      id="shoutboxTextarea"
                      name="shoutbox_textarea"
                      rows="5"
                      required><?=$this->escape($this->originalInput('shoutbox_textarea', $entry->getTextarea())) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
