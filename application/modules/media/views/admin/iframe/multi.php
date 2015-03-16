<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<form class="form-horizontal" method="POST" action="<?=$_SESSION['media-url-action-button']?><?=$this->getRequest()->getParam('id')?>">
    <ul class="nav nav-pills navbar-fixed-top">
    <li><a href="<?php echo $this->getUrl(array('controller' => 'iframe', 'action' => 'upload', 'id' => $this->getRequest()->getParam('id'))); ?>">Upload</a></li>
    <li><a href="<?=$_SESSION['media-url-media-button']?><?=$this->getRequest()->getParam('id')?>">Media</a></li>
    <li class="pull-right"><button class="btn btn-primary" name="save" type="submit" value="save">Hinzufügen…</button></li>
</ul>
<?php echo $this->getTokenField(); ?>
<?php if ($this->get('medias') != '') {?>
    <div id="ilchmedia">
    
        <div class="container-fluid">
        <?php if( $this->getRequest()->getParam('type') === 'image' OR $this->getRequest()->getParam('type') === 'multi'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if(in_array($media->getEnding() , explode(' ',$this->get('media_ext_img')))): ?>
                    <div id="<?=$media->getId() ?>"  class="col-lg-2 col-md-2 col-sm-3 col-xs-4 co thumb media_loader">
                    <img class="image 
                        thumbnail 
                        img-responsive" 
                        data-url="<?php echo $this->getUrl().'/'.$media->getUrl() ?>" 
                        src="<?php echo $this->getUrl().'/'.$media->getUrlThumb() ?>"
                            alt="">
                            <input
                            type="checkbox"
                            id="<?php echo $media->getId() ?> test" 
                            class="regular-checkbox big-checkbox"
                            name="check_image[]"
                            value="<?php echo $media->getId() ?>" />
                            <label for="<?php echo $media->getId() ?> test"></label>
                            </img>
                            </div>
                            <input
                            type="text"
                            name="check_url[]"
                            class="hidden"
                            value="<?php echo $media->getUrl() ?>" />
                    
                <?php endif; ?>
            <?php endforeach; ?>
        <?php }  ?>
  
        <?php if( $this->getRequest()->getParam('type') === 'media'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if( in_array($media->getEnding() , explode(' ',$this->get('media_ext_video')))){
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-url="'.$this->getUrl().'/'.$media->getUrl().'" src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.png').'" alt=""><div class="media-getending">Type: '.$media->getEnding().'</div><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
            <?php endforeach; ?>
        <?php }  ?>
            
        <?php if( $this->getRequest()->getParam('type') === 'file'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if( in_array($media->getEnding() , explode(' ',$this->get('media_ext_file')))): ?>
                   <div id="<?=$media->getId() ?>" class="col-lg-2 col-md-2 col-sm-3 col-xs-4 co thumb media_loader">
                    <img class="image 
                        thumbnail 
                        img-responsive" 
                        data-url="<?php echo $this->getUrl().'/'.$media->getUrl() ?>" 
                        src="<?=$this->getStaticUrl('../application/modules/media/static/img/nomedia.png')?>"
                            alt="">
                            <div class="text-right">
                                <small class="text-info"><?=substr($media->getName(), 0 , 20)?></small>
                            </div>
                            <input
                            type="checkbox"
                            id="<?php echo $media->getId() ?> test" 
                            class="regular-checkbox big-checkbox"
                            name="check_image[]"
                            value="<?php echo $media->getId() ?>" />
                            <label for="<?php echo $media->getId() ?> test"></label>
                            </img>
                            </div>
                            <input
                            type="text"
                            name="check_url[]"
                            class="hidden"
                            value="<?php echo $media->getUrl() ?>" />
                    
                <?php endif; ?>
            <?php endforeach; ?>
        <?php }  ?>
        </div>
    </div>
    
<?php
} else {
    echo $this->getTrans('noMedias');
}
?>
    
</form>
<?php if( $this->getRequest()->getParam('type') === 'multi'){ ?>
<script>
    $(".btn").click(function(){
        window.top.$('#MediaModal').modal('hide');
        window.top.reload();
        
    });
    
    $( document ).on( "click", "img.image", function() {
        $(this).closest('div').find('input[type="checkbox"]').click();
        elem = $(this).closest('div').find('img');
        if(elem.hasClass('chacked')){
            $(this).closest('div').find('img').removeClass("chacked");
        }else{
            $(this).closest('div').find('img').addClass("chacked");
        };
    });
</script>
<?php }  ?>
<?php if( $this->getRequest()->getParam('type') === 'file'){ ?>
<script>
    $(".btn").click(function(){
        window.top.$('#MediaModal').modal('hide');
        window.top.reload();
        
    });
    
    $( document ).on( "click", "img.image", function() {
        $(this).closest('div').find('input[type="checkbox"]').click();
        elem = $(this).closest('div').find('img');
        if(elem.hasClass('chacked')){
            $(this).closest('div').find('img').removeClass("chacked");
        }else{
            $(this).closest('div').find('img').addClass("chacked");
        };
    });
</script>
<?php }  ?>
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
<script type="text/javascript">
    $(document).ready(function()
    {
	function media_loader() 
        { 
            var ID=$(".media_loader:last").attr("id");
            $.post("<?=$this->getUrl('admin/media/ajax/multi/type/')?><?=$this->getRequest()->getParam('type')?>/lastid/"+ID,
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
