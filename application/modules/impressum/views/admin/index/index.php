<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName(), 'id' => 1)); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php echo $this->trans('manageImpressum'); ?>
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
                   placeholder="<?php echo $this->trans('company'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getCompany()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   placeholder="<?php echo $this->trans('name'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   name="address"
                   id="address"
                   placeholder="<?php echo $this->trans('address'); ?>"
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
                   placeholder="<?php echo $this->trans('city'); ?>"
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
                   placeholder="<?php echo $this->trans('phone'); ?>"
                   value="<?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getPhone()); } ?>" />
        </div>
    </div>
    <br />
    <div class="form-group">
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="disclaimer" 
                      cols="60" 
                      rows="5"><?php if ($this->get('impressum') != '') { echo $this->escape($this->get('impressum')->getDisclaimer()); } ?></textarea>
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php echo $this->trans('editButton'); ?>
        </button>
    </div>
</form>

<script type="text/javascript" src="<?php echo $this->staticUrl('js/tinymce/tinymce.min.js') ?>"></script>
<script>
    tinymce.init
    (
        {
            height: 300,
            width: 500,
            selector: "textarea",
        }
    );
</script>
