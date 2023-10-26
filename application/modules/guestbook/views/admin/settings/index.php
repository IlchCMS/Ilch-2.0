<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3 <?=$this->validation()->hasError('entrySettings') ? 'has-error' : '' ?>">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('entrySettings') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="setfree-yes" name="entrySettings" value="1" <?=($this->get('setfree') == '1') ? 'checked="checked"' : '' ?> />
                <label for="setfree-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="setfree-no" name="entrySettings" value="0" <?=($this->get('setfree') != '1') ? 'checked="checked"' : '' ?> />
                <label for="setfree-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('notificationOnNewEntry') ? 'has-error' : '' ?>">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('notificationOnNewEntry') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="notificationOnNewEntry-yes" name="notificationOnNewEntry" value="1" <?=($this->get('notificationOnNewEntry') == '1') ? 'checked="checked"' : '' ?> />
                <label for="notificationOnNewEntry-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="notificationOnNewEntry-no" name="notificationOnNewEntry" value="0" <?=($this->get('notificationOnNewEntry') != '1') ? 'checked="checked"' : '' ?> />
                <label for="notificationOnNewEntry-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3 <?=$this->validation()->hasError('entriesPerPage') ? 'has-error' : '' ?>">
        <label for="entriesPerPageInput" class="col-xl-2 control-label">
            <?=$this->getTrans('entriesPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="entriesPerPageInput"
                   name="entriesPerPage"
                   min="1"
                   value="<?=($this->escape($this->get('entriesPerPage')) != '') ? $this->escape($this->get('entriesPerPage')) : $this->escape($this->originalInput('entriesPerPage')) ?>" />
        </div>
    </div>
    <div class="row mb-3 <?= $this->validation()->hasError('welcomeMessage') ? 'has-error' : '' ?>" data-bs-toggle="dropdown" aria-expanded="false">
        <label for="ck_1" class="col-xl-2 control-label">
            <?=$this->getTrans('welcomeMessage') ?>:
        </label>
        <div class="col-xl-8">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      name="welcomeMessage"
                      toolbar="ilch_html"
                      required><?=($this->escape($this->get('welcomeMessage')) != '') ? $this->escape($this->get('welcomeMessage')) : $this->escape($this->originalInput('welcomeMessage')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
