<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?php echo $this->trans('name'); ?>:
        </label>
        <div class="col-lg-10">
            <input class="form-control"
                   id="name"
                   name="name"
                   type="text"
                   placeholder="Name"
                   value="<?php if($this->get('name') != ''){ echo $this->escape($this->get('name')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-lg-2 control-label">
            <?php echo $this->trans('link'); ?>:
        </label>
        <div class="col-lg-10">
            <input class="form-control"
                   id="link"
                   name="link"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('link') != ''){ echo $this->escape($this->get('link')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="banner" class="col-lg-2 control-label">
            <?php echo $this->trans('banner'); ?>:
        </label>
        <div class="col-lg-10">
            <input class="form-control"
                   id="banner"
                   name="banner"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('banner') != ''){ echo $this->escape($this->get('banner')); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" name="save" class="btn">
                <?php echo $this->trans('send'); ?>
            </button>
        </div>
    </div>
</form>