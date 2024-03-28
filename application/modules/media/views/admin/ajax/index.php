<?php if ($this->get('medias') != ''): ?>
    <?php if ($this->getRequest()->getParam('type') === 'imageckeditor' || $this->getRequest()->getParam('type') === 'single') : ?>
        <?php foreach ($this->get('medias') as $media) : ?>
            <?php if (in_array($media->getEnding(), explode(' ', $this->get('media_ext_img')))) : ?>
                <div id="<?=$media->getId() ?>" class="col-lg-2 col-sm-3 col-xs-4 media_loader">
                    <img class="image img-thumbnail img-fluid thumbnail"
                         data-url="<?=$media->getUrl() ?>"
                         src="<?=$this->getBaseUrl($media->getUrlThumb()) ?>"
                         alt="<?=$media->getName() ?>">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($this->getRequest()->getParam('type') === 'media' || $this->getRequest()->getParam('type') === 'videockeditor') : ?>
        <?php foreach ($this->get('medias') as $media) : ?>
            <?php if (in_array($media->getEnding(), explode(' ', $this->get('media_ext_video')))) : ?>
                <div id="<?=$media->getId() ?>" class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb media_loader">
                    <img class="image img-thumbnail img-fluid thumbnail"
                         data-url="<?=$media->getUrl() ?>"
                         src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                         alt="<?=$media->getName() ?>">
                    <div class="text-end">
                        <small class="text-info"><?=substr($media->getName(), 0, 20) ?></small>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($this->getRequest()->getParam('type') === 'file' || $this->getRequest()->getParam('type') === 'fileckeditor') : ?>
        <?php foreach ($this->get('medias') as $media) : ?>
            <?php if (in_array($media->getEnding(), explode(' ', $this->get('media_ext_file')))) : ?>
                <div id="<?=$media->getId() ?>" class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb media_loader">
                    <img class="image img-thumbnail img-fluid thumbnail"
                         data-alt="<?=$media->getName() ?>"
                         data-url="<?=$media->getUrl() ?>"
                         src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                         alt="">
                    <div class="text-end">
                        <small class="text-info"><?=substr($media->getName(), 0, 20) ?></small>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else : ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'imageckeditor' || $this->getRequest()->getParam('type') === 'file' || $this->getRequest()->getParam('type') === 'fileckeditor' || $this->getRequest()->getParam('type') === 'media' || $this->getRequest()->getParam('type') === 'videockeditor') : ?>
    <script>
    $(".image").click(function() {
        window.top.$('#mediaModal').attr('url', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>
<?php if ($this->getRequest()->getParam('type') === 'single') : ?>
    <script>
    $(".image").click(function() {
        window.top.$('#selectedImage').val($(this).data('url'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>
