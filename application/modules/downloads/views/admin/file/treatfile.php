<legend>
    <?=$this->getTrans('treatFile'); ?>
</legend>
<?php 
$file = $this->get('file');
?>
<form class="form-horizontal" method="POST" action="">
    <div id="gallery">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$this->getUrl().'/'.$file->getFileUrl(); ?>">
                    <img class="thumbnail" src="<?=$this->getUrl().'/'.$file->getFileUrl(); ?>"/>
                </a>
            </div>
            <div class="col-md-8">
                <?=$this->getTokenField(); ?>
                <div class="form-group">
                    <label for="fileTitleInput" class="col-lg-2 control-label">
                        <?=$this->getTrans('fileTitle'); ?>:
                    </label>
                    <div class="col-lg-8">
                        <input class="form-control"
                               type="text"
                               name="fileTitle"
                               id="fileTitleInput"
                               value="<?=$file->getFileTitle()?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="fileDescInput" class="col-lg-2 control-label">
                        <?=$this->getTrans('fileDesc'); ?>:
                    </label>
                    <div class="col-lg-8">
                        <textarea class="form-control"
                                  id="fileDescInput"
                                  rows="8"
                                  name="fileDesc"><?=$file->getFileDesc()?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton')?>
</form>