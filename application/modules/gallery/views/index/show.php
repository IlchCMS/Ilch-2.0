<?php $commentMapper = new \Modules\Comment\Mappers\Comment();?>
<div id="gallery">
    <?php foreach ($this->get('image') as $image) : ?>
    <?php $comments = $commentMapper->getCommentsByKey('gallery_'.$image->getId());?>
    <div class="col-lg-3 col-md-4 col-xs-6">
        <a class="thumbnail" href="<?php echo $this->getUrl(array('action' => 'showimage', 'gallery'  => $this->getRequest()->getParam('id'), 'id' => $image->getId())) ; ?>">
            <img class="img-responsive" src="<?php echo $this->getUrl().'/'.$image->getImageThumb(); ?>"/>
        </a>
        <i class="fa fa-comment-o"></i> <?=count($comments)?>
    </div>
    <?php endforeach; ?>
</div>
<?php echo $this->get('pagination')->getHtml($this, array('action' => 'show', 'id' => $this->getRequest()->getParam('id'))); ?>