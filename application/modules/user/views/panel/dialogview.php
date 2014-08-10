<?php 
    
    $profil = $this->get('profil'); 
    if(!empty($profil)){
?>
<script src="<?php echo $this->getStaticUrl('../application/modules/user/static/js/jquery.nicescroll.js'); ?>"></script>
<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <img class="panel-profile-image" src="<?php echo $this->getStaticUrl().'../'.$this->escape($profil->getAvatar()); ?>" title="<?php echo $this->escape($profil->getName()); ?>">
            <ul class="nav">
            <?php foreach ($this->get('usermenu') as $key): ?>
                <li><a class="" href="<?php echo $this->getUrl($key->getKey()); ?>"><?php echo $key->getTitle(); ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-lg-10">
            <legend>Willkommen <?php echo $this->escape($profil->getName()); ?></legend>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Dialog
                </div>
                <div class="shout_box">
                    <div class="message_box" id="niceScroll">
                    </div>
                    <div class="user_info">
                    <?php echo $this->getTokenField(); ?>
                        <div class="input-group col-lg-12">
                            <textarea name="ilch_bbcode"
                                    id="ilch_bbcode"
                                    type="text"
                                    class="form-control">
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
 <?php }?>

<style>
    .shout_box {
            overflow: hidden;
            bottom: 0;
            right: 20%;
            z-index:9;
    }
    .shout_box .message_box {
            background: #FFFFFF;
            height: 600px;
            overflow:auto;
            border: 1px solid #CCC;
    }
    .user_info input {
            width: 98%;
            height: 25px;
            border: 1px solid #CCC;
            border-top: none;
            padding: 3px 0px 0px 3px;
            font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
    }
    .avatar{
        width: 40px;
        height: auto;
    }
    .panel{
        border: none;
    }
    .panel-primary {
        border-color: #DDD;
    }
    .panel-primary > .panel-heading {
        color: #000;
        background-color: #DDD;
        border-color: #DDD;
    }
</style>

<script type="text/javascript">
$(document).ready(function() {

    var token = document.body.querySelector('[name="ilch_token"]').value;
    var id = <?php echo $this->getRequest()->getParam('id') ?>;

    load_data = {'fetch':1, 'ilch_token':token};
    window.setInterval(function(){
        $.post('<?php echo $this->getUrl('user/panel/dialogviewmessage/id/');?>'+id, load_data,
        function(data) {
            $('.message_box').html(data);
        });
    }, 1000);

    document.getElementById("myBtn").onclick = function () {

        var token = document.body.querySelector('[name="ilch_token"]').value;
        var editorText = CKEDITOR.instances.ilch_bbcode.getData();
        var imessage = editorText;

        post_data = {'text':imessage, 'ilch_token':token};

        $.post('<?php echo $this->getUrl('user/panel/dialogview/id/');?>'+id, post_data,
        function() {

            CKEDITOR.instances.ilch_bbcode.setData("");
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