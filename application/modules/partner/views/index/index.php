<form method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <div class="ilch_form_group">
        <label for="name">
            <?php echo $this->getTrans('name'); ?>:
        </label>
        <div class="controls">
            <input id="name"
                   name="name"
                   type="text"
                   placeholder="Name"
                   value="<?php if($this->get('name') != ''){ echo $this->escape($this->get('name')); } ?>" />
        </div>
    </div>
    <div class="ilch_form_group">
        <label for="link">
            <?php echo $this->getTrans('link'); ?>:
        </label>
        <div class="controls">
            <input id="link"
                   name="link"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('link') != ''){ echo $this->escape($this->get('link')); } ?>" />
        </div>
    </div>
    <div class="ilch_form_group">
        <label for="banner">
            <?php echo $this->getTrans('banner'); ?>:
        </label>
        <div class="controls">
            <input id="banner"
                   name="banner"
                   type="text"
                   placeholder="http://"
                   value="<?php if($this->get('banner') != ''){ echo $this->escape($this->get('banner')); } ?>" />
        </div>
    </div>
   <div class="ilch_form_group">
        <div class="controls">
            <button type="submit" name="save">
                <?php echo $this->getTrans('send'); ?>
            </button>
        </div>
    </div>
</form>