<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css') ?>" rel="stylesheet">

<?php if ($this->get('smilies') != ''): ?>
    <div id="ilchmedia" class="container-fluid">
        <?php foreach ($this->get('smilies') as $smilie): ?>
            <?php if (in_array($smilie->getEnding(), explode(' ',$this->get('smiley_filetypes')))): ?>
                <div id="<?=$smilie->getId() ?>" class="col-lg-2 col-sm-3 col-xs-4 media_loader">
                    <img class="image thumbnail img-responsive"
                         data-url="<?=$smilie->getUrl() ?>"
                         src="<?=$this->getBaseUrl($smilie->getUrlThumb()) ?>"
                         alt="<?=$smilie->getName() ?>">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?=$this->getTrans('noSmilies') ?>
<?php endif; ?>

<script type="text/javascript">
$(".image").click(function(){
    window.top.$('#selectedImage<?=$this->getRequest()->getParam('input')?>').val($(this).data('url'));
    window.top.$('#mediaModal').modal('hide');
});

$(document).ready(function()
{
    function media_loader() 
    {
        var ID=$(".media_loader:last").attr("id");
        $.post("<?=$this->getUrl('admin/media/ajax/index/type/single/lastid/') ?>"+ID,
            function(data)
            {
                if (data !== "") 
                {
                    $(".media_loader:last").after(data);
                }
            }
        );
    };  

    $(window).scroll(function()
    {
        if ($(window).scrollTop() === $(document).height() - $(window).height())
        {
            media_loader();
        }
    }); 
});
</script>
