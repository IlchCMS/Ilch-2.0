<h1><?=$this->getTrans('manage') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('siteStatistic') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('siteStatistic') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="siteStatistic-on" name="siteStatistic" value="1" <?php if ($this->get('siteStatistic') == '1') { echo 'checked="checked"'; } ?> />
                <label for="siteStatistic-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="siteStatistic-off" name="siteStatistic" value="0" <?php if ($this->get('siteStatistic') != '1') { echo 'checked="checked"'; } ?> />
                <label for="siteStatistic-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('ilchVersionStatistic') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('ilchVersionStatistic') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="ilchVersionStatistic-on" name="ilchVersionStatistic" value="1" <?php if ($this->get('ilchVersionStatistic') == '1') { echo 'checked="checked"'; } ?> />
                <label for="ilchVersionStatistic-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="ilchVersionStatistic-off" name="ilchVersionStatistic" value="0" <?php if ($this->get('ilchVersionStatistic') != '1') { echo 'checked="checked"'; } ?> />
                <label for="ilchVersionStatistic-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('modulesStatistic') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('modulesStatistic') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="modulesStatistic-on" name="modulesStatistic" value="1" <?php if ($this->get('modulesStatistic') == '1') { echo 'checked="checked"'; } ?> />
                <label for="modulesStatistic-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="modulesStatistic-off" name="modulesStatistic" value="0" <?php if ($this->get('modulesStatistic') != '1') { echo 'checked="checked"'; } ?> />
                <label for="modulesStatistic-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('visitsStatistic') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('visitsStatistic') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="visitsStatistic-on" name="visitsStatistic" value="1" <?php if ($this->get('visitsStatistic') == '1') { echo 'checked="checked"'; } ?> />
                <label for="visitsStatistic-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="visitsStatistic-off" name="visitsStatistic" value="0" <?php if ($this->get('visitsStatistic') != '1') { echo 'checked="checked"'; } ?> />
                <label for="visitsStatistic-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('browserStatistic') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('browserStatistic') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="browserStatistic-on" name="browserStatistic" value="1" <?php if ($this->get('browserStatistic') == '1') { echo 'checked="checked"'; } ?> />
                <label for="browserStatistic-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="browserStatistic-off" name="browserStatistic" value="0" <?php if ($this->get('browserStatistic') != '1') { echo 'checked="checked"'; } ?> />
                <label for="browserStatistic-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('osStatistic') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('osStatistic') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="osStatistic-on" name="osStatistic" value="1" <?php if ($this->get('osStatistic') == '1') { echo 'checked="checked"'; } ?> />
                <label for="osStatistic-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="osStatistic-off" name="osStatistic" value="0" <?php if ($this->get('osStatistic') != '1') { echo 'checked="checked"'; } ?> />
                <label for="osStatistic-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('#siteStatistic-off').click(function(){
        $('#ilchVersionStatistic-off').click();
        $('#modulesStatistic-off').click();
    });

    $('#ilchVersionStatistic-on').click(function(){
        $('#siteStatistic-on').click();
    });

    $('#modulesStatistic-on').click(function(){
        $('#siteStatistic-on').click();
    });
</script>
