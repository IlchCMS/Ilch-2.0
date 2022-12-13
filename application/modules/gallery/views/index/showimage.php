<?php
$image = $this->get('image');
$commentsClass = new Ilch\Comments();
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/venobox/venobox.min.css') ?>" media="screen" rel="stylesheet">

<div id="gallery">
    <div class="row">
        <div class="col-md-6">
            <a class="venobox" href="<?=$this->getUrl().'/'.$image->getImageUrl() ?>">
                <img src="<?=$this->getUrl().'/'.$image->getImageUrl() ?>" alt="<?=$this->escape($image->getImageTitle()) ?>"/>
            </a>
        </div>
    </div>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $image, $this) ?>

<script src="<?=$this->getModuleUrl('static/venobox/venobox.min.js') ?>"></script>
<script>
    new VenoBox({
        selector: '.venobox',
        numeration: true,
        share: true,
        navTouch: true,
        spinner: 'pulse',
    })
</script>
