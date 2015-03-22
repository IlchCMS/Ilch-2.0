<?php $commentMapper = new \Modules\Comment\Mappers\Comment();?>
<div id="gallery">
    <?php foreach ($this->get('file') as $file) : ?>
    <?php $comments = $commentMapper->getCommentsByKey('gallery_'.$file->getId());?>
    <?php $image = '' ?>
    <?php if($file->getFileImage() != ''): ?>
        <?php $image = $this->getBaseUrl($file->getFileImage()) ?>
    <?php else: ?>
        <?php $image = $this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>
    <?php endif; ?>
    <div class="col-xs-6 col-md-4 col-lg-3 col-sm-4">
            <div class="panel panel-default">
                <div class="panel-image thumbnail">
                    <a href="<?=$this->getUrl(array('action' => 'showfile', 'downloads'  => $this->getRequest()->getParam('id'), 'id' => $file->getId())) ; ?>">
                        <img src="<?=$image ?>" class="panel-image-preview" alt="<?=$file->getFileTitle();?>" />
                    </a>
                </div>
                <div class="panel-footer text-center">
                    <i class="fa fa-comment-o"></i> <?=count($comments)?>
                    <i class="fa fa-eye"> <?=$file->getVisits()?></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?=$this->get('pagination')->getHtml($this, array('action' => 'show', 'id' => $this->getRequest()->getParam('id'))); ?>
<style>
    @media (max-width: 990px) {
        #gallery > [class*="col-"] {
            padding: 0px !important;
        }
    }

    .panel-heading ~ .panel-image img.panel-image-preview {
        border-radius: 0px;
    }

    .panel-body {
        overflow: hidden;
    }

    .panel-image ~ .panel-footer a {
        padding: 0px 10px;
        font-size: 1.3em;
        color: rgb(100, 100, 100);
    }

    .panel-footer{
        padding: 5px !important;
        color: #BBB;
    }

    .panel-footer:hover{
        color: #000;
    }

    .thumbnail {
        position:relative;
        overflow:hidden;
        margin-bottom: 0px !important;
    }
    #gallery img{
        min-height: 20px;
    }
</style>
