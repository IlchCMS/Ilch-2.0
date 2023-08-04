<h1><?=$this->getTrans('bootstrap') ?></h1>
<p><?=$this->getTrans('bootstrapText') ?></p>
<h4 style="color: red">Bedenken Sie, dass die Version 5.x noch nicht zu 100% auf das Ilch CMS abgestimmt ist.</h4>

<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
<div id="bootstrapVersion" class="form-group">
    <div class="col-lg-2 control-label">
        <?=$this->getTrans('bootstrapVersion') ?>:
    </div>
    <div class="col-lg-4">
            <select class="form-control" name="bootstrapVersion" id="bootstrapVersion">
                <option <?php if ($this->get('bootstrapVersion') == '3') { echo 'selected="selected"'; } ?> value="3"><?=$this->getTrans('bootstrap3') ?></option>
                <option <?php if ($this->get('bootstrapVersion') == '5') { echo 'selected="selected"'; } ?> value="5"><?=$this->getTrans('bootstrap5') ?></option>

            </select>
    </div>
</div>

<?=$this->getSaveBar() ?>
</form>

