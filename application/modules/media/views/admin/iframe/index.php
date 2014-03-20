<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<?php if ($this->get('medias') != '') {?>
<div id="ilchmedia" class="container">
    <div class="row">
        <?php if( $this->getRequest()->getParam('type') === 'image' OR $this->getRequest()->getParam('type') === 'single'){ ?>
            <?php foreach ($this->get('medias') as $media) : ?>
                <?php if(in_array($media->getEnding() , explode(' ',$this->get('media_ext_img')))){
                    echo '<div class="col-lg-2 col-sm-3 col-xs-4"><img class="image thumbnail img-responsive" data-url="'.$this->getUrl().'/'.$media->getUrl().'" src="'.$this->getStaticUrl().'../'.$media->getUrl().'" alt=""><div class="media-getname">'.$media->getName().'</div></div>';
                    }
                ?>
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
</div>

<?php
} else {
    echo $this->getTrans('noMedias');
}
?>
<?php if( $this->getRequest()->getParam('type') === 'image'){ ?>
<script>
    $(".image").click(function(){
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-basic','src', $(this).data('url'));
        window.top.$('#MediaModal').modal('hide');
    });
</script>
<?php }  ?>
<?php if( $this->getRequest()->getParam('type') === 'file'){ ?>
<script>
    $(".image").click(function(){
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-adv','file', $(this).data('url'));
        dialog.setValueOf('tab-adv','alt', $(this).data('alt'));
        window.top.$('#MediaModal').modal('hide');
    });
</script>
<?php }  ?>
<?php if( $this->getRequest()->getParam('type') === 'media'){ ?>
<script>
    $(".image").click(function(){
        var dialog = window.top.CKEDITOR.dialog.getCurrent();
        dialog.setValueOf('tab-mov','video', $(this).data('url'));
        window.top.$('#MediaModal').modal('hide');
    });
</script>
<?php }  ?>
<?php if( $this->getRequest()->getParam('type') === 'single'){ ?>
<script>
    $(".image").click(function(){
        window.top.$('#articleImage').val($(this).data('url'));
        window.top.$('#MediaModal').modal('hide');
    });
</script>
<?php }  ?>