<?php

/** @var \Ilch\View $this */


$userMapper = new \Modules\User\Mappers\User();

$config = \Ilch\Registry::get('config');
?>
<style>
.shoutbox-text {
    line-break: anywhere;
}
</style>
<script>
    $(function() {
        let $shoutboxContainer = $('#shoutbox-container<?=$this->get('uniqid') ?>'),
            showForm = function() {
                $("#shoutbox-button-container<?=$this->get('uniqid') ?>").slideUp(200, function() {
                    $("#shoutbox-form-container<?=$this->get('uniqid') ?>").slideDown(400);
                });
            },
            hideForm = function(afterHide) {
                $("#shoutbox-form-container<?=$this->get('uniqid') ?>").slideUp(400, function() {
                    $("#shoutbox-button-container<?=$this->get('uniqid') ?>").slideDown(200, afterHide);
                });
            };


        //slideup-down
        $shoutboxContainer.on('click', '#shoutbox-slide-down<?=$this->get('uniqid') ?>', showForm);

        //slideup-down reset on click out
        $(document.body).on('mousedown', function(event) {
            let target = $(event.target);

            if (!target.parents().addBack().is('#shoutbox-container<?=$this->get('uniqid') ?>')) {
                hideForm();
            }
        });

        function sendRequest(dataString) {
            $.ajax({
                type: "POST",
                url: "<?=$this->getUrl('shoutbox/index/ajax') ?>",
                data: dataString,
                cache: false,
                success: function(html) {
                    let $htmlWithoutScript = $(html).filter('#shoutbox-container<?=$this->get('uniqid') ?>');
                    hideForm(function() {
                        $shoutboxContainer.html($htmlWithoutScript.html());
                    });
                }
            });
        }

        //ajax send
        $shoutboxContainer.on('click', 'button[type=submit]', function(ev) {
            ev.preventDefault();

            let $btn = $(this),
                $form = $btn.closest('form');

            if ($form.find('[name=shoutbox_name]').val() === '') {
                alert(<?=json_encode($this->getTrans('missingName')) ?>);
            } else if ($form.find('[name=shoutbox_textarea]').val() === '') {
                alert(<?=json_encode($this->getTrans('missingMessage')) ?>);
            }

            <?php if ($this->get('googlecaptcha') && $this->get('googlecaptcha')->getVersion() === 3) : ?>
            grecaptcha.ready(function() {
                grecaptcha.execute('<?=$this->get('googlecaptcha')->getKey() ?>', {action: 'saveshoutbox<?=$this->get('uniqid') ?>'}).then(function(token) {
                    $form.prepend('<input type="hidden" name="token" value="' + token + '">');
                    $form.prepend('<input type="hidden" name="action" value="saveshoutbox<?=$this->get('uniqid') ?>">');
                    sendRequest($form.serialize());
                });
            });
            <?php elseif ($this->get('googlecaptcha') && $this->get('googlecaptcha')->getVersion() === 2) : ?>
            $form.prepend('<input type="hidden" name="token" value="' + grecaptcha.getResponse() + '">');
            $form.prepend('<input type="hidden" name="action" value="saveshoutbox<?=$this->get('uniqid') ?>">');
            sendRequest($form.serialize());
            <?php else : ?>
            sendRequest($form.serialize());
            <?php endif; ?>
        });
    });
</script>
<div id="shoutbox-container<?=$this->get('uniqid') ?>">
    <div id="shoutbox-button-container<?=$this->get('uniqid') ?>" class="form-horizontal">
        <div class="row mb-3">
            <div class="col-xl-12">
                <?php if (is_in_array($this->get('writeAccess'), explode(',', $config->get('shoutbox_writeaccess')))) : ?>
                    <div class="pull-left">
                        <button class="btn btn-outline-secondary" id="shoutbox-slide-down<?=$this->get('uniqid') ?>"><?=$this->getTrans('answer') ?></button>
                    </div>
                <?php endif; ?>
                <?php if (count($this->get('shoutbox')) == $config->get('shoutbox_limit')) : ?>
                    <div class="pull-right">
                        <a href="<?=$this->getUrl('shoutbox/index/index/') ?>" class="btn btn-outline-secondary"><?=$this->getTrans('archive') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (is_in_array($this->get('writeAccess'), explode(',', $config->get('shoutbox_writeaccess')))) : ?>
        <div id="shoutbox-form-container<?=$this->get('uniqid') ?>" style="display: none;">
            <form id="shoutboxForm_<?=$this->get('uniqid') ?>" name="shoutboxForm_<?=$this->get('uniqid') ?>" class="form-horizontal" method="post">
                <input type="hidden" name="uniqid" value="<?=$this->get('uniqid') ?>">
               <?=$this->getTokenField() ?>
                <div class="row mb-3 d-none">
                    <label class="col-xl-2 control-label" for="bot">
                        <?=$this->getTrans('bot') ?>
                    </label>
                    <div class="col-xl-8">
                        <input type="text"
                               class="form-control"
                               name="bot"
                               id="bot"
                               placeholder="Bot" />
                    </div>
                </div>
                <div class="row mb-3 <?=$this->validation()->hasError('shoutbox_name') ? 'has-error' : '' ?>">
                    <div class="col-xl-12">
                        <input type="text"
                               class="form-control"
                               name="shoutbox_name"
                               placeholder="Name"
                               value="<?=($this->getUser() !== null) ? $this->escape($this->getUser()->getName()) : '' ?>"
                               <?=($this->getUser() !== null) ? 'readonly' : 'required' ?> />
                    </div>
                </div>
                <div class="row mb-3 <?=$this->validation()->hasError('shoutbox_textarea') ? 'has-error' : '' ?>">
                    <div class="col-xl-12">
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
                <div class="row mb-3">
                    <div class="col-xl-12">
                        <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')) : ?>
                            <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
                        <?php endif; ?>
                        <div class="pull-left">
                            <?php
                            if ($this->get('captchaNeeded')) {
                                if ($this->get('googlecaptcha')) {
                                    echo $this->get('googlecaptcha')->setForm('shoutboxForm_' . $this->get('uniqid'))->getCaptcha($this, 'answer', 'shoutbox' . $this->get('uniqid'));
                                } else {
                                    echo $this->getSaveBar('answer', 'shoutbox' . $this->get('uniqid'));
                                }
                            } else {
                                echo $this->getSaveBar('answer', 'shoutbox' . $this->get('uniqid'));
                            }
                            ?>
                        </div>
                        <?php if (count($this->get('shoutbox')) == $config->get('shoutbox_limit')) : ?>
                            <div class="pull-right">
                                <a href="<?=$this->getUrl('shoutbox/index/index/') ?>" class="btn btn-default"><?=$this->getTrans('archive') ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <?php if (!empty($this->get('shoutbox'))) : ?>
                <?php
                /** @var \Modules\Shoutbox\Models\Shoutbox $shoutbox */
                foreach ($this->get('shoutbox') as $shoutbox) : ?>
                    <?php $user = $userMapper->getUserById($shoutbox->getUid()) ?>
                    <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
                    <tr>
                        <?php if ($shoutbox->getUid() == '0' || empty($user)) : ?>
                            <td>
                                <b><?=$this->escape($shoutbox->getName()) ?>:</b><br />
                                <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                            </td>
                        <?php else : ?>
                            <td>
                                <b><a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>"><?=$this->escape($user->getName()) ?></a></b>:<br />
                                <span class="small"><?=$date->format("d.m.Y H:i", true) ?></span>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td class="shoutbox-text"><?=$this->escape($shoutbox->getTextarea()) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td><?=$this->getTrans('noEntrys') ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
