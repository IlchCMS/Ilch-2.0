<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<?php if ($this->get('medias') != '') {?>
<div id="ilchmedia" class="container">
    <div class="row">
        <?php if( $this->getRequest()->getParam('type') === 'image'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if(in_array($media->getEnding() , explode(' ',$this->get('media_ext_img')))){
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-url="'.$this->getStaticUrl().'../'.$media->getUrl().'" src="'.$this->getStaticUrl().'../'.$media->getUrl().'" alt=""><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
            <?php endforeach; ?>
        <?php }  ?>
  
        <?php if( $this->getRequest()->getParam('type') === 'media'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if( in_array($media->getEnding() , explode(' ',$this->get('media_ext_video')))){
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-url="'.$this->getStaticUrl().'../'.$media->getUrl().'" src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.jpg').'" alt=""><div class="media-getending">Type: '.$media->getEnding().'</div><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
            <?php endforeach; ?>
        <?php }  ?>
            
        <?php if( $this->getRequest()->getParam('type') === 'file'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if( in_array($media->getEnding() , explode(' ',$this->get('media_ext_file')))){
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-url="'.$this->getStaticUrl().'../'.$media->getUrl().'" src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.jpg').'" alt=""><div class="media-getending">Type: '.$media->getEnding().'</div><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
            <?php endforeach; ?>
        <?php }  ?>
    </div>
</div>

<?php
} else {
    echo $this->getTrans('noMedias');
}
?>

<script>
    $(".image").click(function(){
        window.parent.$('#<?=$this->getRequest()->getParam('inputid') ?>').val($(this).data('url'));
    });
</script>
