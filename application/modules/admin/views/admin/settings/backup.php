<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend><?php echo $this->getTrans('systemSettings'); ?></legend>
    <div class="form-group">
        <label for="startPage" class="col-lg-2 control-label">
            <?php echo $this->getTrans('backupStart'); ?>:
        </label>
        <div class="col-lg-8">
            
        </div>
    </div>