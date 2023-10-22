<?php

use Ilch\Comments;
use Ilch\Date;

$file = $this->get('file');
$nowDate = new Date();
$commentsClass = new Comments();
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
        <div class="col-lg-6">
            <a href="<?=$this->getUrl().'/'.$file->getFileUrl() ?>">
                <img class="img-thumbnail" src="<?=$image ?>" alt="<?=$this->escape($file->getFileTitle()) ?>"/>
            </a>
        </div>
        <div class="col-lg-6">
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
