<legend><?=$this->getTrans('treatCat'); ?></legend>
<?php if($this->get('cat')){ ?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="form-group">
        <div class="input-group input-group-option col-lg-6 col-md-6 col-xs-12">
            <input type="text" name="title_treat" class="form-control" placeholder="<?=$this->escape($this->get('cat')->getCatName()); ?>">
        </div>
    </div>
    <?=$this->getSaveBar('saveButton')?>
</form>
<?php } else { ?>
<?=$this->getTrans('treatCatError'); ?>
<?php } ?>
