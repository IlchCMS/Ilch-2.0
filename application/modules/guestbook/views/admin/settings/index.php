<h1><?=$this->getTrans('settings') ?></h1>
<?php if (!empty($this->get('errors'))): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=in_array('entrySettings', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('entrySettings') ?>:
        </div>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="setfree-yes" name="entrySettings" value="1" <?php if ($this->get('setfree') == '1') { echo 'checked="checked"'; } ?> />
                <label for="setfree-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="setfree-no" name="entrySettings" value="0" <?php if ($this->get('setfree') != '1') { echo 'checked="checked"'; } ?> />  
                <label for="setfree-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="form-group <?=in_array('entriesPerPage', $this->get('errorFields')) ? 'has-error' : '' ?>">
        <label for="entriesPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('entriesPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="entriesPerPageInput"
                   name="entriesPerPage"
                   min="1"
                   value="<?=($this->escape($this->get('entriesPerPage')) != '') ? $this->escape($this->get('entriesPerPage')) : $this->escape($this->get('post')['entriesPerPage']) ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
