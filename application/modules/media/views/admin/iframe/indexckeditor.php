<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<?php if ($this->get('medias') != ''): ?>
<div id="ilchmedia" class="container-fluid">
    <?php if ($this->getRequest()->getParam('type') === 'imageckeditor' || $this->getRequest()->getParam('type') === 'single'): ?>
        <div class="row">
        <?php foreach ($this->get('medias') as $media): ?>
            <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_img')))): ?>
                    <div  id="<?=$media->getId() ?>" class="col-xl-2 col-md-3 col-4 media_loader">
                        <img class="image img-thumbnail img-fluid"
                             data-url="<?=$media->getUrl() ?>"
                             src="<?=$this->getBaseUrl($media->getUrlThumb()) ?>"
                             alt="<?=$media->getName() ?>">
                    </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($this->getRequest()->getParam('type') === 'media' || $this->getRequest()->getParam('type') === 'videockeditor'): ?>
        <div class="row">
        <?php foreach ($this->get('medias') as $media): ?>
            <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_video')))): ?>
                <div id="<?=$media->getId() ?>" class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb media_loader">
                    <img class="image img-thumbnail img-fluid"
                         data-url="<?=$media->getUrl() ?>"
                         src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                         alt="<?=$media->getName() ?>">
                    <div class="text-right">
                        <small class="text-info"><?=substr($media->getName(), 0, 20) ?></small>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($this->getRequest()->getParam('type') === 'file' || $this->getRequest()->getParam('type') === 'fileckeditor'): ?>
        <div class="row">
        <?php foreach ($this->get('medias') as $media): ?>
            <?php if (in_array($media->getEnding(), explode(' ',$this->get('media_ext_file')))): ?>
                <div id="<?=$media->getId() ?>" class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb media_loader">
                    <img class="image img-thumbnail img-fluid"
                         data-alt="<?=$media->getName() ?>"
                         data-url="<?=$media->getUrl() ?>"
                         src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                         alt="<?=$media->getName() ?>">
                    <div class="text-right">
                        <small class="text-info"><?=substr($media->getName(), 0, 20) ?></small>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php else: ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'imageckeditor' || $this->getRequest()->getParam('type') === 'single'): ?>
    <script>
    $(".image").click(function() {
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-basic','src', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>
<?php if ($this->getRequest()->getParam('type') === 'file' || $this->getRequest()->getParam('type') === 'fileckeditor'): ?>
    <script>
    $(".image").click(function() {
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-adv','file', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
        dialog.setValueOf('tab-adv','alt', $(this).data('alt'));
        window.top.$('#mediaModal').modal('hide');
    });
    </script>
<?php endif; ?>
<?php if ($this->getRequest()->getParam('type') === 'media' || $this->getRequest()->getParam('type') === 'videockeditor'): ?>
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
        window.top.$('#selectedImage').val($(this).data('url'));
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
        $.post("<?=$this->getUrl('admin/media/ajax/index/type/') ?><?=$this->getRequest()->getParam('type') ?>/lastid/"+ID,
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
