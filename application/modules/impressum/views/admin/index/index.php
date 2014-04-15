<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName(), 'id' => 1)); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php echo $this->getTrans('manageImpressum'); ?>
    </legend>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="paragraph"
                   id="paragraph"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getParagraph()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="company"
                   id="company"
                   placeholder="<?php echo $this->getTrans('company'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getCompany()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="<?php echo $this->getTrans('name'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="address"
                   id="address"
                   placeholder="<?php echo $this->getTrans('address'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getAddress()); } ?>" />
        </div>
    </div>
    <br />
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="city"
                   id="city"
                   placeholder="<?php echo $this->getTrans('city'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getCity()); } ?>" />
        </div>
    </div>
    <br />
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="phone"
                   id="phone"
                   placeholder="<?php echo $this->getTrans('phone'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getPhone()); } ?>" />
        </div>
    </div>
    <br />
    <div class="form-group">
        <div class="col-lg-4">
            <textarea class="form-control"
                      id="ilch_html"
                      name="disclaimer" 
                      cols="60" 
                      rows="5"><?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getDisclaimer()); } ?></textarea>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton')?>
</form>
