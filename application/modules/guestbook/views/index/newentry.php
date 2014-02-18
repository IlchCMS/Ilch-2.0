<legend><?php echo $this->getTrans('entry'); ?></legend>
<form action="" method="POST" class="form-horizontal">
    <?php echo $this->getTokenField(); ?>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('name'); ?>*
        </label>
        <div class="col-lg-10">
            <input type="text" 
               name="name" 
               class="form-control" 
               placeholder="Name"
               required />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('email'); ?>*
        </label>
        <div class="col-lg-10">
            <input type="text" 
               name="email" 
               class="form-control" 
               placeholder="E-Mail" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('page'); ?>
        </label>
        <div class="col-lg-10">
           <input type="text" 
               name="homepage" 
               class="form-control" 
               placeholder="<?php echo $this->getTrans('page'); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">
            <?=$this->getTrans('message'); ?>*
        </label>
        <div class="col-lg-10">
            <textarea id="ilch_bbcode"
                      name="text"
                      required>
            </textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
           <input type="submit" 
               name="saveEntry" 
               class="btn btn-small btn-primary" 
               value="<?php echo $this->getTrans('submit'); ?>" />
        </div>
    </div>
</form>

