<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(); ?>/index.php/admin/gallery/gallery/treatgallery/id/<?=$this->getRequest()->getParam('id') ?>">
<ul class="nav nav-pills navbar-fixed-top">
    <li><a href="<?php echo $this->getUrl(array('controller' => 'iframe', 'action' => 'upload')); ?>/id/<?=$this->getRequest()->getParam('id') ?>">Upload</a></li>
    <li><a href="<?=$this->getUrl('admin/media/iframe/multi/type/multi');?>/id/<?=$this->getRequest()->getParam('id') ?>">Media</a></li>
    <li class="pull-right"><button class="btn btn-primary" name="save" type="submit" value="save">Hinzufügen…</button></li>
</ul>
<?php echo $this->getTokenField(); ?>
<?php if ($this->get('medias') != '') {?>
<div id="ilchmedia">
    
        <?php if( $this->getRequest()->getParam('type') === 'image' OR $this->getRequest()->getParam('type') === 'multi'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if(in_array($media->getEnding() , explode(' ',$this->get('media_ext_img')))): ?>
                    <div class="col-lg-2 col-md-3 col-xs-6 thumb">
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
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-url="'.$this->getUrl().'/'.$media->getUrl().'" src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.jpg').'" alt=""><div class="media-getending">Type: '.$media->getEnding().'</div><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
            <?php endforeach; ?>
        <?php }  ?>
            
        <?php if( $this->getRequest()->getParam('type') === 'file'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if( in_array($media->getEnding() , explode(' ',$this->get('media_ext_file')))){
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-alt="'.$media->getName().'" data-url="'.$this->getUrl().'/'.$media->getUrl().'" src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.jpg').'" alt=""><div class="media-getending">Type: '.$media->getEnding().'</div><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
            <?php endforeach; ?>
        <?php }  ?>
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
        setTimeout(window.top.location.reload(1), 3000);
        
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