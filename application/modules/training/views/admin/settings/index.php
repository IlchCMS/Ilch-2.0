<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>

    <h1><?=$this->getTrans('boxSettings') ?></h1>
    <div class="form-group<?=in_array('boxNexttrainingLimit', $this->get('errorFields')) ? ' has-error' : '' ?>">
        <label for="limitNextTrainingInput" class="col-lg-2 control-label">
            <?=$this->getTrans('nextTrainingLimit') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="limitNextTrainingInput"
                   name="boxNexttrainingLimit"
                   min="1"
                   value="<?=(empty($this->get('errorFields'))) ? $this->escape($this->get('boxNexttrainingLimit')) : $this->get('post')['boxNexttrainingLimit'] ?>" />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
