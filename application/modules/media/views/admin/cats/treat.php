<legend><?php echo $this->getTrans('treatCat'); ?></legend>
<?php if($this->get('cat')){ ?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="form-group">
        <div class="input-group input-group-option col-lg-6 col-md-6 col-xs-12">
            <input type="text" name="title_treat" class="form-control" placeholder="<?php echo $this->escape($this->get('cat')->getCatName()); ?>">
        </div>
    </div>
    <?=$this->getSaveBar('saveButton')?>
</form>
<?php } else { ?>
<?php echo $this->getTrans('treatCatError'); ?>
<?php } ?>
