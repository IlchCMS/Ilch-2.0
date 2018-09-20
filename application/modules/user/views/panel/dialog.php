<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">
<link href="<?=$this->getModuleUrl('static/css/message.css') ?>" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 profile">
        <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>

        <div class="profile-content active">
            <h1><?=$this->getTrans('dialog') ?></h1>
            <div id="uMessenger">
                <div class="chat">
                    <div class="row chat-wrapper">

                        <div class="col-xs-12 col-md-5 col-lg-4 <?=$this->get('dialog') ? 'hidden-list-massage' : '' ?>">
                            <div class="chat-list-info">
                                <span class="avatar">
                                    <img src="<?=$this->getUrl().'/'.$this->getUser()->getAvatar() ?>" class="img-circle" alt="<?=$this->escape($this->getUser()->getName()) ?>" title="<?=$this->escape($this->getUser()->getName()) ?>">
                                </span>
                            </div>
                            <div class="slimScrollDiv chat-list-scroll <?=$this->get('dialog') ? '' : 'noscroll' ?>">
                                <div class="chat-list-wrapper">
                                    <ul class="chat-list">

                                        <?php if ($this->get('dialogs') != ''): ?>
                                            <?php foreach ($this->get('dialogs') as $dialog): ?>
                                                <?php
                                                $date = new \Ilch\Date($dialog->getTime());

                                                if ($dialog->getRead() != '' AND $dialog->getCId() == $this->getRequest()->getParam('id')) {
                                                    $class = 'new active';
                                                } elseif ($dialog->getRead() != '') {
                                                    $class = 'new';
                                                } elseif ($dialog->getCId() == $this->getRequest()->getParam('id')) {
                                                    $class = 'active';
                                                } else {
                                                    $class = '';
                                                }
                                                ?>
                                                <li class="<?=$class ?>">
                                                    <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialog', 'id' => $dialog->getCId()]) ?>" class="dialog-link">
                                                        <span class="avatar">
                                                            <img src="<?=$this->getUrl().'/'.$dialog->getAvatar() ?>" class="img-circle" alt="<?=$this->escape($dialog->getName()) ?>" title="<?=$this->escape($dialog->getName()) ?>">
                                                        </span>
                                                        <div class="body">
                                                            <div class="header">
                                                                <span class="username"><?=$this->escape($dialog->getName()) ?></span>
                                                                <small class="timestamp text-muted">
                                                                    <?php
                                                                    if (strtotime($date) <= strtotime('-7 day')) {
                                                                        echo $date->format('d.m.Y', true);
                                                                    } elseif (strtotime($date) <= strtotime('-2 day') AND strtotime($date) >= strtotime('-6 day') ) {
                                                                        echo $this->getTrans($date->format('l', true));
                                                                    } elseif (strtotime($date) <= strtotime('-1 day')) {
                                                                        echo $this->getTrans('profileYesterday').' '.$date->format('H:i', true);
                                                                    } else {
                                                                        echo $date->format('H:i', true);
                                                                    };
                                                                    ?>
                                                                </small>
                                                            </div>
                                                            <p>
                                                                <?=nl2br($this->getHtmlFromBBCode($this->escape($dialog->getText()))) ?>
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="no-dialog">
                                                <?=$this->getTrans('noDialog') ?>
                                            </div>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                                <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 100%; background: rgb(0, 0, 0);"></div>
                                <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-7 col-lg-8 <?=$this->get('dialog') ? '' : 'hidden-list-massage' ?>">
                            <?php if ($this->get('dialog')): ?>
                                <div class="message-info">
                                    <span class="back-chat-list">
                                        <a href="<?=$this->getUrl(['controller' => 'panel', 'action' => 'dialog']) ?>">
                                            <i class="fa fa-chevron-left"></i>
                                        </a>
                                    </span>
                                    <span class="avatar">
                                        <img src="<?=$this->getUrl().'/'.$this->get('dialog')->getAvatar() ?>" class="img-circle" alt="<?=$this->escape($this->get('dialog')->getName()) ?>" title="<?=$this->escape($this->get('dialog')->getName()) ?>">
                                    </span>
                                    <span class="username">
                                        <?=$this->escape($this->get('dialog')->getName()) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="slimScrollDiv message-list-scroll <?=$this->get('dialog') ? 'noscroll' : '' ?>">
                                <div class="message-list-wrapper" style="overflow: hidden; width: auto; height: 100%;">
                                    <ul class="message-list">

                                        <?php if ($this->get('dialog') != ''): ?>
                                            <div class="message_box"></div>
                                        <?php else: ?>
                                            <div class="no-dialog"><?=$this->getTrans('selectDialog') ?></div>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                                <div class="slimScrollBar" style="width: 7px; position: absolute; top: 265px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 187.092px; background: rgb(0, 0, 0);"></div>
                                <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div>
                            </div>

                            <?php if ($this->get('dialog')): ?>
                                <div class="compose-box">
                                    <div class="row">
                                        <div class="col-xs-12 chat-textarea">
                                            <?=$this->getTokenField() ?>
                                            <textarea class="form-control input-sm ckeditor"
                                                      id="ck_1"
                                                      name="ilch_bbcode"
                                                      toolbar="ilch_bbcode"></textarea>
                                            <button class="btn btn-primary btn-sm pull-right" id="chatSendBtn">
                                                <i class="fa fa-location-arrow"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=$this->getModuleUrl('static/js/jquery.nicescroll.js') ?>"></script>
<script>
    $(function(){
        $(".chat-list-wrapper, .message-list-wrapper").niceScroll();
    });

    $("ul.chat-list li").click(function(){
        location.href = $(this).find("a.dialog-link").attr("href");
    });

    CKEDITOR.on('instanceReady', function(e) {
        $('.cke_contents iframe').contents().keyup(function(){
            if (CKEDITOR.instances.ck_1.getData().trim() === '') {
                $(".chat-textarea").find('#chatSendBtn').hide();
            } else {
                $(".chat-textarea").find('#chatSendBtn').show();
            }
        });
    });

    if (<?=$this->getRequest()->getParam('id') ?>) {
        $(document).ready(function() {
            var token = document.body.querySelector('[name="ilch_token"]').value;
            var id = <?=$this->getRequest()->getParam('id') ?>;

            load_data = {'fetch':1, 'ilch_token':token};
            window.setInterval(function() {
                $.post('<?=$this->getUrl('user/panel/dialogmessage/id/') ?>'+id, load_data,
                    function(data) {
                        $('.message_box').html(data);
                    });
            }, 1000);

            document.getElementById("chatSendBtn").onclick = function () {
                var token = document.body.querySelector('[name="ilch_token"]').value;
                var editorText = CKEDITOR.instances.ck_1.getData();
                var imessage = editorText;

                post_data = {'text':imessage, 'ilch_token':token};

                $.post('<?=$this->getUrl('user/panel/dialog/id/') ?>'+id, post_data,
                    function() {
                        CKEDITOR.instances.ck_1.setData("");
                        var scrolltoh = $('.message-list-wrapper')[0].scrollHeight;
                        $('.message-list-wrapper').scrollTop(scrolltoh);

                        var messageDiv = $('.message-list-wrapper');
                        messageDiv.scrollTop(messageDiv.prop("scrollHeight"));
                    }).fail(function(err) {

                    alert(err.statusText);
                });
            };

            var varCounter = 0;
            var varName = function() {
                if (varCounter <= 1) {
                    varCounter++;
                    var scrolltoh = $('.message-list-wrapper')[0].scrollHeight;
                    $('.message-list-wrapper').scrollTop(scrolltoh);
                } else {
                    clearInterval(varName);
                }
            };

            setInterval(varName, 1000);
        });
    }
</script>
