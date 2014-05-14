<div id="gallery">
    <?php foreach ($this->get('image') as $image) : ?>
    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
        <a class="thumbnail" href="<?php echo $this->getUrl().'/'.$image->getImageId(); ?>">
            <img class="img-responsive" src="<?php echo $this->getUrl().'/'.$image->getImageThumb(); ?>"/>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php echo $this->get('pagination')->getHtml($this, array('action' => 'show', 'id' => $this->getRequest()->getParam('id'))); ?>