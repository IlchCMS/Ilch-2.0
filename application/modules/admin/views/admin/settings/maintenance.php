<h1><?=$this->getTrans('menuMaintenance') ?></h1>
<form method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <div class="col-xl-2 col-form-label">
            <?=$this->getTrans('maintenanceMode') ?>:
        </div>
        <div class="col-xl-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="maintenanceMode-on" name="maintenanceMode" value="1" <?php if ($this->get('maintenanceMode') == '1') { echo 'checked="checked"'; } ?> />
                <label for="maintenanceMode-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="maintenanceMode-off" name="maintenanceMode" value="0" <?php if ($this->get('maintenanceMode') != '1') { echo 'checked="checked"'; } ?> />
                <label for="maintenanceMode-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="maintenanceEndDateTime" class="col-lg-2 col-form-label">
            <?=$this->getTrans('maintenanceEndDateTime') ?>:
        </label>
        <div class="col-xl-2">
            <input type="datetime-local"
                   class="form-control"
                   id="maintenanceEndDateTime"
                   name="maintenanceDateTime"
                   value="<?=date('Y-m-d\TH:i', strtotime($this->get('maintenanceDate'))) ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label for="maintenanceStatus" class="col-lg-2 col-form-label">
            <?=$this->getTrans('maintenanceStatus') ?>:
        </label>
        <div class="col-xl-4">
            <div class="range">
                <input type="range" class="form-range" id="maintenanceStatus" name="maintenanceStatus" min="0" max="100" value="<?=$this->escape($this->get('maintenanceStatus')) ?>" onchange="range.value=value">
                <output id="range"><?=$this->escape($this->get('maintenanceStatus')) ?></output>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="maintenanceText" class="col-xl-2 col-form-label">
            <?=$this->getTrans('maintenanceText') ?>:
        </label>
        <div class="col-xl-10">
            <textarea class="form-control ckeditor"
                      id="ck_1"
                      id="maintenanceText"
                      name="maintenanceText"
                      toolbar="ilch_html"
                      rows="5"><?=$this->escape($this->get('maintenanceText')) ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
