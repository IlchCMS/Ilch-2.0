<script type="text/javascript" >
$(function() {
    var $shoutboxContainer = $('#shoutbox-container'),
        showForm = function() {
            $("#shoutbox-button-container").slideUp(200, function() {
                $("#shoutbox-form-container").slideDown(400);
            });
        },
        hideForm = function(afterHide) {
            $("#shoutbox-form-container").slideUp(400, function() {
                $("#shoutbox-button-container").slideDown(200, afterHide);
            });
        };


    //slideup-down
    $shoutboxContainer.on('click', '#shoutbox-slide-down', showForm);

    //slideup-down reset on click out
    $(document.body).on('mousedown', function(event) {
        var target = $(event.target);

        if (!target.parents().addBack().is('#shoutbox-container')) {
            hideForm();
        }
    });

    //ajax send
    $shoutboxContainer.on('click', 'button[type=submit]', function(ev) {
        ev.preventDefault();
        var $btn = $(this),
            $form = $btn.closest('form'),
            dataString = $form.serialize();

        if ($form.find('[name=shoutbox_name]').val() == '') {
            alert(<?=json_encode($this->getTrans('missingName')) ?>);
        } else if ($form.find('[name=shoutbox_textarea]').val() == '') {
            alert(<?=json_encode($this->getTrans('missingMessage')) ?>);
        } else {
            $.ajax({
                type: "POST",
                url: "<?=$this->getUrl('shoutbox/index/ajax') ?>",
                data: dataString,
                cache: false,
                success: function(html) {
                    var $htmlWithoutScript = $(html).filter('#shoutbox-container');
                    hideForm(function() {
                        $shoutboxContainer.html($htmlWithoutScript.html());
                    });
                }
            });
        }
    });
});
</script>

<?php $config = \Ilch\Registry::get('config'); ?>

<div id="shoutbox-container">
    <div id="shoutbox-button-container" class="form-horizontal">
        <div class="form-group">
            <div class="col-lg-12">
                <div class="pull-left">
                    <button class="btn" id="shoutbox-slide-down"><?=$this->getTrans('answer') ?></button>
                </div>
                <?php if (count($this->get('shoutbox')) == $config->get('shoutbox_limit')): ?>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl('shoutbox/index/index/') ?>" class="btn btn-default"><?=$this->getTrans('archive') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="shoutbox-form-container" style="display: none;">
        <form class="form-horizontal" action="" method="post">
           <?=$this->getTokenField() ?>
            <div class="form-group hidden">
                <label class="col-lg-2 control-label">
                    <?=$this->getTrans('bot') ?>
                </label>
                <div class="col-lg-8">
                    <input type="text"
                           class="form-control"
                           name="bot"
                           placeholder="Bot" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="text"
                           class="form-control"
                           name="shoutbox_name"
                           placeholder="Name"
                           value="<?=($this->getUser() !== null) ? $this->escape($this->getUser()->getName()) : '' ?>"
                           <?=($this->getUser() !== null) ? 'readonly' : 'required' ?> />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea class="form-control"
                              style="resize: vertical"
                              name="shoutbox_textarea"
                              cols="10"
                              rows="5"
                              maxlength="<?=$config->get('shoutbox_maxtextlength') ?>"
                              placeholder="<?=$this->getTrans('message') ?>"
                              required></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <div class="pull-left">
                        <button type="submit" class="btn" name="form_<?=$this->get('uniqid') ?>">
                            <?=$this->getTrans('answer') ?>
                        </button>
                    </div>
                    <?php if (count($this->get('shoutbox')) == $config->get('shoutbox_limit')): ?>
                        <div class="pull-right">
                            <a href="<?=$this->getUrl('shoutbox/index/index/') ?>" class="btn btn-default"><?=$this->getTrans('archive') ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <?php if ($this->get('shoutbox') != ''): ?>
        <table class="table table-bordered table-striped table-responsive">
            <?php foreach ($this->get('shoutbox') as $shoutbox): ?>
                <?php $userMapper = new \Modules\User\Mappers\User() ?>
                <?php $user = $userMapper->getUserById($shoutbox->getUid()) ?>
                <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
                <tr>
                    <?php if ($shoutbox->getUid() == '0'): ?>
                        <td>
                            <b><?=$this->escape($shoutbox->getName()) ?>:</b><br />
                            <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                        </td>
                    <?php else: ?>
                        <td>
                            <b><a href="<?=$this->getUrl('user/profil/index/user/'.$user->getId()) ?>"><?=$this->escape($user->getName()) ?></a></b>:<br />
                            <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php
                    /*
                     * @todo should fix this regex.
                     */
                    ?>
                    <td><?=preg_replace('/([^\s]{' . $this->get('maxwordlength') . '})(?=[^\s])/', "$1\n", $this->escape($shoutbox->getTextarea())) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
