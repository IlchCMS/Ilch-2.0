<?php 
$profil = $this->get('profil'); 
?>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $usermenu): ?>
                <li><a class="" href="<?=$this->getUrl($usermenu->getKey()) ?>"><?=$usermenu->getTitle() ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend>Avatar</legend>
            <form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <?=$this->getTokenField(); ?>
                    <div class="col-lg-2 col-sm-2 col-2">
                        <img class="panel-profile-image" src="<?=$this->getBaseUrl().$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
                    </div>
                    <div class="col-lg-10 col-sm-10 col-10">
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
                    <div class="col-lg-offset-2 col-lg-12">
                        <input type="submit" 
                               name="saveEntry" 
                               class="btn"
                               value="<?=$this->getTrans('profileSubmit') ?>" />
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
