<legend>
    <?php
    if ($this->get('cat') != '') {
        echo $this->getTrans('edit');
   } else {
        echo $this->getTrans('add');
   }
   ?>
</legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField(); ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-3">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   value="<?php if ($this->get('cat') != '') { echo $this->escape($this->get('cat')->getName()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('cat') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
