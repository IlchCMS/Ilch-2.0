<?php 
    $profil = $this->get('profil'); 
?>
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $usermenu): ?>
                <li><a class="" href="<?php echo $this->getUrl($usermenu->getKey()); ?>"><?php echo $usermenu->getTitle(); ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend>Avatar</legend>
            <form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <?php echo $this->getTokenField(); ?>
                    <div class="col-lg-3 col-sm-3 col-3">
                        <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
                    </div>
                    <div class="col-lg-9 col-sm-9 col-9">
                        <h4>Avatar Upload</h4>
                        <p>Maximale Bildgröße: 80 Pixel breit, 80 Pixel hoch.</p>
                        <p>Maximale Dateigröße: 48.83 KB.</p>
                        <div class="input-group col-lg-6">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file">
                                    Browse&hellip; <input type="file" name="avatar" accept="image/*">
                                </span>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   readonly />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-8">
                        <input type="submit" 
                               name="saveEntry" 
                               class="btn"
                               value="<?php echo $this->getTrans('submit'); ?>" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
        
    });
});
</script>
<style>
.btn-file {
  position: relative;
  overflow: hidden;
}
.btn-file input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  font-size: 999px;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  background: red;
  cursor: inherit;
  display: block;
}
input[readonly] {
  background-color: white !important;
  cursor: text !important;
}
</style>