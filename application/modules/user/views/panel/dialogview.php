<?php 
$profil = $this->get('profil'); 
?>

<?php if(!empty($profil)): ?>
<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?=$this->getStaticUrl().'../'.$this->escape($profil->getAvatar()) ?>" title="<?=$this->escape($profil->getName()) ?>">
            <ul class="nav">
                <?php foreach ($this->get('usermenu') as $key): ?>
                    <li><a class="" href="<?=$this->getUrl($key->getKey()); ?>"><?=$key->getTitle() ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend>Willkommen <?=$this->escape($profil->getName()) ?></legend>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Dialog
                </div>
                <div class="shout_box">
                    <div class="message_box" id="niceScroll">
                    </div>
                    <div class="user_info">
                    <?=$this->getTokenField() ?>
                        <div class="input-group col-lg-12">
                            <textarea name="ilch_bbcode"
                                    id="ck_1"
                                    type="text"
                                    class="form-control ckeditor"
                                    toolbar="ilch_bbcode">
                            </textarea>
                        </div>
                        <div>
                            <button class="btn btn-default pull-right" id="myBtn">Absenden</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <?php endif; ?>

<script src="<?=$this->getStaticUrl('../application/modules/user/static/js/jquery.nicescroll.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {

    var token = document.body.querySelector('[name="ilch_token"]').value;
    var id = <?=$this->getRequest()->getParam('id') ?>;

    load_data = {'fetch':1, 'ilch_token':token};
    window.setInterval(function(){
        $.post('<?=$this->getUrl('user/panel/dialogviewmessage/id/');?>'+id, load_data,
        function(data) {
            $('.message_box').html(data);
        });
    }, 1000);

    document.getElementById("myBtn").onclick = function () {

        var token = document.body.querySelector('[name="ilch_token"]').value;
        var editorText = CKEDITOR.instances.ck_1.getData();
        var imessage = editorText;

        post_data = {'text':imessage, 'ilch_token':token};

        $.post('<?=$this->getUrl('user/panel/dialogview/id/');?>'+id, post_data,
        function() {

            CKEDITOR.instances.ck_1.setData("");
            var scrolltoh = $('.message_box')[0].scrollHeight;
            $('.message_box').scrollTop(scrolltoh);

        }).fail(function(err) {

            alert(err.statusText);

        });

    };

    var varCounter = 0;
    var varName = function(){
        if(varCounter <= 1) {
            varCounter++;
            var scrolltoh = $('.message_box')[0].scrollHeight;
            $('.message_box').scrollTop(scrolltoh);
        } else {
            clearInterval(varName);
        }
    };

    setInterval(varName, 1000);

    $("#niceScroll").niceScroll({cursorcolor:"#ccc"});
});
</script>
