<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend><?php echo $this->trans('layouts'); ?></legend>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-1 control-label">
            <?php echo $this->trans('search'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   name="search"
                   type="text"
                   value="<?php echo $this->escape($this->get('pageTitle')); ?>" />
        </div>
    </div>
</form>