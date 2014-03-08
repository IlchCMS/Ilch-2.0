<form method="POST" class="form-horizontal" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->getTrans('name'); ?>:
        </label>
        <div class="col-lg-6">
            <input id="name"
                   class="form-control"
                   name="name"
                   type="text"
                   placeholder="Name"
                   value="<?php if($this->get('name') != ''){ echo $this->escape($this->get('name')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-lg-2 control-label">
            <?php echo $this->getTrans('link'); ?>:
        </label>
        <div class="col-lg-6">
            <input id="link"
                   class="form-control"
                   name="link"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('link') != ''){ echo $this->escape($this->get('link')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="banner" class="col-lg-2 control-label">
            <?php echo $this->getTrans('banner'); ?>:
        </label>
        <div class="col-lg-6">
            <input id="banner"
                   class="form-control"
                   name="banner"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('banner') != ''){ echo $this->escape($this->get('banner')); } ?>" />
        </div>
    </div>
   <div class="form-group">
        <div class="col-lg-offset-2 col-lg-6">
            <button type="submit" class="btn" name="save">
                <?php echo $this->getTrans('send'); ?>
            </button>
        </div>
    </div>
</form>