<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">
<style>
.container-fluid {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.container-fluid > [class*="col-"] {
    padding:0;
}
*, *:before, *:after {
    box-sizing: border-box;
}
</style>

<form class="form-horizontal" method="POST" action="<?=$_SESSION['media-url-action-button'] ?>">
    <?=$this->getTokenField() ?>
    <?php if ($this->get('medias') != ''): ?>
        <div id="ilchmedia">
            <div class="container-fluid">
                <?php if ($this->getRequest()->getParam('type') === 'image' || $this->getRequest()->getParam('type') === 'multi'): ?>
                    <div class="row">
                    <?php foreach ($this->get('medias') as $media): ?>
                        <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_img')))): ?>
                            <div class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb">
                                <img class="image img-thumbnail img-fluid"
                                     data-url="<?=$media->getUrl() ?>"
                                     src="<?=$this->getUrl($media->getUrlThumb()) ?>"
                                     alt="<?=$media->getName() ?>">
                                <input type="checkbox"
                                       class="regular-checkbox big-checkbox"
                                       id="<?=$media->getId() ?> test"
                                       name="check_image[]"
                                       value="<?=$media->getId() ?>" />
                                <label for="<?=$media->getId() ?> test"></label>
                            </div>
                            <input type="text"
                                   class="hidden"
                                   name="check_url[]"
                                   value="<?=$media->getUrl() ?>" />
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->getRequest()->getParam('type') === 'media'): ?>
                    <div class="row">
                    <?php foreach ($this->get('medias') as $media): ?>
                        <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_video')))): ?>
                            <div class="col-xl-2 col-md-3 col-4">
                                <img class="image img-thumbnail img-fluid"
                                     data-url="<?=$media->getUrl() ?>"
                                     src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                                     alt="<?=$media->getName() ?>">
                                <div class="media-getending">Type: <?=$media->getEnding() ?></div>
                                <div class="media-getname"><?=$media->getName() ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->getRequest()->getParam('type') === 'file'): ?>
                    <div class="row">
                    <?php foreach ($this->get('medias') as $media): ?>
                        <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_file')))): ?>
                            <div class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb">
                                <img class="image img-thumbnail img-fluid"
                                     data-url="<?=$media->getUrl() ?>"
                                     src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                                     alt="">
                                <input type="checkbox"
                                       class="regular-checkbox big-checkbox"
                                       id="<?=$media->getId() ?> test"
                                       name="check_image[]"
                                       value="<?=$media->getId() ?>" />
                                <label for="<?=$media->getId() ?> test"></label>
                            </div>
                            <input type="text"
                                   class="hidden"
                                   name="check_url[]"
                                   value="<?=$media->getUrl() ?>" />
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noMedias') ?>
    <?php endif; ?>
</form>

<?php if ($this->getRequest()->getParam('type') === 'multi'): ?>
    <script>
    $(".btn").click(function() {
        window.top.$('#mediaModal').modal('hide');
        window.top.reload();
    });

    $(".image").click(function() {
        $(this).closest('div').find('input[type="checkbox"]').click();
        elem = $(this).closest('div').find('img');
        if (elem.hasClass('chacked')) {
            $(this).closest('div').find('img').removeClass("chacked");
        } else {
            $(this).closest('div').find('img').addClass("chacked");
        };
    });
    </script>
<?php endif; ?>
<?php if ($this->getRequest()->getParam('type') === 'file'): ?>
    <script>
    $(".btn").click(function() {
        window.top.$('#mediaModal').modal('hide');
        window.top.reload();
    });

    $(".image").click(function() {
        $(this).closest('div').find('input[type="checkbox"]').click();
        elem = $(this).closest('div').find('img');
        if (elem.hasClass('chacked')) {
            $(this).closest('div').find('img').removeClass("chacked");
        } else {
            $(this).closest('div').find('img').addClass("chacked");
        };
    });
    </script>
<?php endif; ?>
