<?php

/** @var \Ilch\View $this */

/** @var \Modules\War\Models\Maps $entry */
$entry = $this->get('map');
?>
<h1><?=$this->getTrans(!$entry->getId() ? 'manageNewMaps' : 'treatMaps') ?></h1>
<form id="article_form" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('mapsName') ? ' has-error' : '' ?>">
        <label for="mapsNameInput" class="col-xl-2 col-form-label">
            <?=$this->getTrans('mapsName') ?>:
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="mapsNameInput"
                   name="mapsName"
                   value="<?=$this->originalInput('mapsName', $entry->getName(), true) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar($entry->getId() ? 'updateButton' : 'addButton') ?>
</form>
