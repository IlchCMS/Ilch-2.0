<?php
$file = $this->get('file');
$nowDate = new \Ilch\Date();
$commentsClass = new \Ilch\Comments();
$image = '';
if (!empty($file)) {
    if ($file->getFileImage() != '') {
        $image = $this->getBaseUrl($file->getFileImage());
    } else {
        $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png');
    }
}
?>

<link href="<?=$this->getModuleUrl('../comment/static/css/comment.css') ?>" rel="stylesheet">

<?php if (!empty($file)) : ?>
<div id="downloads">
    <div class="row">
        <div class="col-md-6">
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>">
                <img class="thumbnail" src="<?=$image ?>" alt="<?=$this->escape($file->getFileTitle()) ?>"/>
            </a>
        </div>
        <div class="col-md-6">
            <h3><?=$this->escape($file->getFileTitle()) ?></h3>
            <p><?=$this->escape($file->getFileDesc()) ?></p>
            <?php $extension = pathinfo($file->getFileUrl(), PATHINFO_EXTENSION);
            $extension = (empty($extension)) ? '': '.'.$extension; ?>
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>" class="btn btn-primary pull-right" download="<?=$this->escape($file->getFileTitle()).$extension ?>"><?=$this->getTrans('download') ?></a>
        </div>
    </div>
</div>

<?= $commentsClass->getComments($this->get('commentsKey'), $file, $this) ?>

<?php else : ?>
    <?=$this->getTrans('downloadNotFound') ?>
<?php endif; ?>
