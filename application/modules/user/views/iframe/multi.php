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

<form method="POST" action="<?=$_SESSION['media-url-action-button'] ?><?=$this->getRequest()->getParam('id') ?>">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-pills navbar-fixed-top">
        <li class="nav-item"><a href="<?=$this->getUrl(['controller' => 'iframe', 'action' => 'upload', 'id' => $this->getRequest()->getParam('id')]) ?>" class="nav-link"><?=$this->getTrans('upload') ?></a></li>
        <li class="nav-item"><a href="<?=$_SESSION['media-url-media-button'] ?><?=$this->getRequest()->getParam('id') ?>" class="nav-link"><?=$this->getTrans('media') ?></a></li>
        <li class="float-end nav-item"><button type="submit" class="btn btn-primary" name="save" value="save"><?=$this->getTrans('add') ?></button></li>
    </ul>

    <?php if ($this->get('medias') != ''): ?>
        <div id="ilchmedia">
            <div class="container-fluid">
                <div class="row">
                    <?php foreach ($this->get('medias') as $media): ?>
                        <?php if (in_array(strtolower($media->getEnding()), explode(' ', strtolower($this->get('usergallery_filetypes'))))): ?>
                            <div id="<?=$media->getId() ?>" class="col-xl-2 col-lg-2 col-md-3 col-4 co thumb media_loader">
                                <img class="image img-thumbnail img-fluid thumbnail"
                                     data-url="<?=$media->getUrl() ?>"
                                     <?php if (file_exists($media->getUrlThumb())): ?>
                                        src="<?=$this->getBaseUrl($media->getUrlThumb()) ?>"
                                     <?php else: ?>
                                        src="<?=$this->getBaseUrl('application/modules/media/static/img/nomedia.png') ?>"
                                     <?php endif; ?>
                                     alt="<?=$media->getName() ?>">
                                <div class="form-check">
                                    <input type="checkbox"
                                           class="form-check-input regular-checkbox big-checkbox"
                                           id="<?=$media->getId() ?>_img"
                                           name="check_image[]"
                                           value="<?=$media->getId() ?>" />
                                    <label class="form-check-label" for="<?=$media->getId() ?>_img"></label>
                                </div>
                            </div>
                            <input type="text"
                                   name="check_url[]"
                                   value="<?=$media->getUrl() ?>"
                                   hidden />
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?=$this->getTrans('noMedias') ?>
    <?php endif; ?>
</form>

<script>
$(".btn").click(function() {
    window.top.$('#mediaModal').modal('hide');
    window.top.reload();
});

$(document).on("click", "img.image", function() {
    $(this).closest('div').find('input[type="checkbox"]').click();
    elem = $(this).closest('div').find('img');
    if (elem.hasClass('checked')) {
        $(this).closest('div').find('img').removeClass("checked");
    } else {
        $(this).closest('div').find('img').addClass("checked");
    };
});

$(document).ready(function() {
    function media_loader() {
        var ID=$(".media_loader:last").attr("id");
        $.post("<?=$this->getUrl('admin/media/ajax/multi/type/') ?><?=$this->getRequest()->getParam('type') ?>/lastid/"+ID,
            function(data)
            {
                if (data !== "")
                {
                    $(".media_loader:last").after(data);
                }
            }
        );
    };

    $(window).scroll(function() {
        if ($(window).scrollTop() === $(document).height() - $(window).height())
        {
            media_loader();
        }
    });
});
</script>
