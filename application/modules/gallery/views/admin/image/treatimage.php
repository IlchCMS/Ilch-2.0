<legend>
    <?php echo $this->getTrans('treatImage'); ?>
</legend>
<?php 
$image = $this->get('image');
?>
<form class="form-horizontal" method="POST" action="">
    <div id="gallery">
        <div class="row">
            <div class="col-md-4">
                <a href="<?php echo $this->getUrl().'/'.$image->getImageUrl(); ?>">
                    <img class="thumbnail" src="<?php echo $this->getUrl().'/'.$image->getImageUrl(); ?>"/>
                </a>
            </div>
            <div class="col-md-8">
                <?php echo $this->getTokenField(); ?>
                <div class="form-group">
                    <label for="imageTitleInput" class="col-lg-2 control-label">
                        <?php echo $this->getTrans('imageTitle'); ?>:
                    </label>
                    <div class="col-lg-8">
                        <input class="form-control"
                               type="text"
                               name="imageTitle"
                               id="imageTitleInput"
                               value="<?php echo $image->getImageTitle()?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="imageDescInput" class="col-lg-2 control-label">
                        <?php echo $this->getTrans('imageDesc'); ?>:
                    </label>
                    <div class="col-lg-8">
                        <textarea class="form-control"
                                  id="imageDescInput"
                                  rows="8"
                                  name="imageDesc"><?php echo $image->getImageDesc()?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton')?>
</form>