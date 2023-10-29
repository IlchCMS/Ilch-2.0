<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<ul class="nav nav-pills navbar-fixed-top">
    <li class="nav-item"><a href="<?=$this->getUrl('admin/media/iframe/upload/') ?>" class="nav-link"><?=$this->getTrans('upload') ?></a></li>
    <li class="nav-item"><a href="<?=$_SESSION['media-url-media-button'] ?>" class="nav-link"><?=$this->getTrans('media') ?></a></li>
</ul>

<?php if ($this->get('medias') != ''): ?>
    <div id="ilchmedia" class="container-flui rowd">
        <?php if ($this->getRequest()->getParam('type') === 'image' || $this->getRequest()->getParam('type') === 'single'): ?>
            <?php foreach ($this->get('medias') as $media): ?>
                <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_img')))): ?>
                  <div class="row">
                    <div id="<?=$media->getId() ?>" class="col-xl-2 col-md-3 col-4 media_loader">
                        <img class="image img-thumbnail img-responsive"
                             data-url="<?=$media->getUrl() ?>"
                             <?php if (file_exists($media->getUrlThumb())): ?>
                                src="<?=$this->getBaseUrl($media->getUrlThumb()) ?>"
                             <?php else: ?>
                                src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                             <?php endif; ?>
                             alt="<?=$media->getName() ?>">
                    </div>
                  </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($this->getRequest()->getParam('type') === 'media'): ?>
            <?php foreach ($this->get('medias') as $media): ?>
                <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_video')))): ?>
                  <div class="row">
                    <div class="col-xl-2 col-md-3 col-4">
                        <img class="image img-thumbnail img-fluid"
                             data-url="<?=$media->getUrl() ?>"
                             src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                             alt="<?=$media->getName() ?>">
                        <div class="media-getending">Type: <?=$media->getEnding() ?></div>
                        <div class="media-getname"><?=$media->getName() ?></div>
                    </div>
                  </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($this->getRequest()->getParam('type') === 'file'): ?>
            <?php foreach ($this->get('medias') as $media): ?>
                <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_file')))): ?>
                  <div class="row">
                    <div class="col-xl-2 col-md-3 col-4">
                        <img class="image img-thumbnail img-responsive"
                             data-alt="<?=$media->getName() ?>"
                             data-url="<?=$this->getUrl().'/'.$media->getUrl() ?>"
                             src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                             alt="<?=$media->getName() ?>">
                        <div class="media-getending">Type: <?=$media->getEnding() ?></div>
                        <div class="media-getname"><?=$media->getName() ?></div>
                    </div>
                  </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'image'): ?>
    <script>
    $(".image").click(function() {
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-basic','src', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'file'): ?>
    <script>
    $(".image").click(function() {
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-adv','file', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
        dialog.setValueOf('tab-adv','alt', $(this).data('alt'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'media'): ?>
    <script>
    $(".image").click(function() {
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-mov','video', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'single'): ?>
    <script>
    $(".image").click(function() {
        window.top.$('#selectedImage<?=$this->getRequest()->getParam('input') ?>').val($(this).data('url'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>

<script>
$(document).ready(function()
{
    function media_loader()
    {
        var ID=$(".media_loader:last").attr("id");
        $.post("<?=$this->getUrl('admin/media/ajax/index/type/single/lastid/') ?>"+ID,
            function(data)
            {
                if (data !== "") {
                    $(".media_loader:last").after(data);
                }
            }
        );
    };

    $(window).scroll(function()
    {
        if ($(window).scrollTop() === $(document).height() - $(window).height()) {
            media_loader();
        }
    });
});
</script>
