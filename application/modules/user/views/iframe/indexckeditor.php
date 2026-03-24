<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<?php if ($this->get('medias') != '') : ?>
<div id="ilchmedia" class="container-fluid">
    <?php if ($this->getRequest()->getParam('type') === 'imageckeditor') : ?>
        <div class="row">
        <?php foreach ($this->get('medias') as $media) : ?>
            <?php if (in_array(strtolower($media->getEnding()), explode(' ', strtolower($this->get('usergallery_filetypes'))))) : ?>
                    <div  id="<?=$media->getId() ?>" class="col-xl-2 col-md-3 col-4 media_loader">
                        <img class="image img-thumbnail img-fluid thumbnail"
                             data-url="<?=$media->getUrl() ?>"
                             src="<?=$this->getBaseUrl($media->getUrlThumb()) ?>"
                             alt="<?=$media->getName() ?>">
                    </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php else : ?>
    <?=$this->getTrans('noMedias') ?>
<?php endif; ?>

<?php if ($this->getRequest()->getParam('type') === 'imageckeditor') : ?>
    <script>
    $(".image").click(function() {
        window.top.$('#mediaModal').attr('url', '<?=$this->getBaseUrl() ?>'+$(this).data('url'));
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
