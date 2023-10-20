<h1>
    <?=$this->getTrans('add') ?>
    <a class="badge rounded-pill bg-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">
        <i class="fa-solid fa-info"></i>
    </a>
</h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="compress" class="col-xl-2 control-label">
            <?=$this->getTrans('compress') ?>:
        </label>
        <div class="col-xl-2">
            <select class="form-control" id="compress" name="compress">
                <option><?=$this->getTrans('compressNone') ?></option>
                <option value="gzip">Gzip</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('skipComments') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="skipComments-on" name="skipComments" value="1" checked="checked" />
                <label for="skipComments-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="skipComments-off" name="skipComments" value="0" />
                <label for="skipComments-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('addDatabases') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="addDatabases-on" name="addDatabases" value="1" />
                <label for="addDatabases-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="addDatabases-off" name="addDatabases" value="0" checked="checked" />
                <label for="addDatabases-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-2 control-label">
            <?=$this->getTrans('dropTable') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="dropTable-on" name="dropTable" value="1" />
                <label for="dropTable-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('yes') ?></label>
                <input type="radio" class="flipswitch-input" id="dropTable-off" name="dropTable" value="0" checked="checked" />
                <label for="dropTable-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('no') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('add') ?>
</form>
<?=$this->getDialog('infoModal', $this->getTrans('info'), $this->getTrans('createBackupInfoText')) ?>
