<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="downloadsPerPageInput" class="col-xl-2 control-label">
            <?=$this->getTrans('downloadsPerPage') ?>:
        </label>
        <div class="col-xl-1">
            <input type="number"
                   class="form-control"
                   id="downloadsPerPageInput"
                   name="downloadsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('downloadsPerPage')) ?>" />
        </div>
    </div>
    <a href="<?=$this->getUrl(['module' => 'media', 'controller' => 'settings', 'action' => 'index'], 'admin') ?>"><?=$this->getTrans('moreSettings') ?></a>
    <?=$this->getSaveBar() ?>
</form>
