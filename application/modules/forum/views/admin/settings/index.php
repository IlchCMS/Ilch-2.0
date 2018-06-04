<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="threadsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('threadsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="threadsPerPageInput"
                   name="threadsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('threadsPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="postsPerPageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('postsPerPage') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="postsPerPageInput"
                   name="postsPerPage"
                   min="1"
                   value="<?=$this->escape($this->get('postsPerPage')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('postVoting') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="postVoting-on" name="postVoting" value="1" <?php if ($this->get('postVoting') == '1') { echo 'checked="checked"'; } ?> />
                <label for="postVoting-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="postVoting-off" name="postVoting" value="0" <?php if ($this->get('postVoting') != '1') { echo 'checked="checked"'; } ?> />
                <label for="postVoting-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
