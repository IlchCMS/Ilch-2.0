<link href="<?=$this->getBaseUrl('application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<?php if( $this->getRequest()->getParam('type') === 'multi'){ ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(); ?>/index.php/admin/gallery/gallery/treatgallery/id/<?=$this->getRequest()->getParam('id') ?>">
<?php } ?>
<?php if( $this->getRequest()->getParam('type') === 'file'){ ?>
<form class="form-horizontal" method="POST" action="<?=$this->getUrl(); ?>/index.php/admin/downloads/downloads/treatdownloads/id/<?=$this->getRequest()->getParam('id') ?>">
<?php } ?>
    <ul class="nav nav-pills navbar-fixed-top">
    <li><a href="<?php echo $this->getUrl(array('controller' => 'iframe', 'action' => 'upload')); ?>/id/<?=$this->getRequest()->getParam('id') ?>">Upload</a></li>
    <li><a href="<?=$this->getUrl('admin/media/iframe/multi/type/multi');?>/id/<?=$this->getRequest()->getParam('id') ?>">Media</a></li>
    <li class="pull-right"><button class="btn btn-primary" name="save" type="submit" value="save">Hinzufügen…</button></li>
</ul>
<?php echo $this->getTokenField(); ?>
<?php if ($this->get('medias') != '') {?>
    <div id="ilchmedia">
    <?=$this->get('pagination')->getHtml($this,array('type' => 'multi', 'id' => $this->getRequest()->getParam('id'))); ?>
        <div class="container-fluid">
        <?php if( $this->getRequest()->getParam('type') === 'image' OR $this->getRequest()->getParam('type') === 'multi'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if(in_array($media->getEnding() , explode(' ',$this->get('media_ext_img')))): ?>
                    <div class="col-lg-1 col-md-2 col-sm-3 col-xs-4 co thumb">
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
                   <div class="col-lg-1 col-md-2 col-sm-3 col-xs-4 co thumb">
                    <img class="image 
                        thumbnail 
                        img-responsive" 
                        data-url="<?php echo $this->getUrl().'/'.$media->getUrl() ?>" 
                        src="<?=$this->getStaticUrl('../application/modules/media/static/img/nomedia.png')?>"
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
    
    $(".image").click(function() {
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
    
    $(".image").click(function() {
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