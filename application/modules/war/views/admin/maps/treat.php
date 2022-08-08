<?php $entrie = $this->get('maps'); ?>
<h1><?=(!$entrie->getId()) ? $this->getTrans('manageNewMaps') : $this->getTrans('treatMaps') ?></h1>
<form id="article_form" class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group<?=$this->validation()->hasError('mapsName') ? ' has-error' : '' ?>">
        <label for="mapsNameInput" class="col-lg-2 control-label">
            <?=$this->getTrans('mapsName') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="mapsNameInput"
                   name="mapsName"
                   value="<?=$this->escape($this->originalInput('mapsName', ($entrie->getId()?$entrie->getName():''))) ?>" />
        </div>
    </div>
    <?=($entrie->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
