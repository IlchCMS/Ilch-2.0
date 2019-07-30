<?php
$announcement = $this->get('announcement');
?>

<h1><?php echo $this->getTrans('add'); ?></h1>
<form class="form" method="POST" action=""> 
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('answer') ? 'has-error' : '' ?>">
        <label for="ck_1" class=" control-label">
            Announcement-Content:
        </label>
        <div>
            <textarea class="form-control ckeditor"
                id="ck_1"
                name="content"
                cols="45"
                rows="3"
                toolbar="ilch_html"><?php echo $announcement->getContent(); ?>
            </textarea>
        </div>
    </div>
    <?php 
        echo $this->getSaveBar('saveButton');          
    ?>
</form>