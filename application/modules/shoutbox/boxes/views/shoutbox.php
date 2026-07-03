<?php

/** @var \Ilch\View $this */

/** @var \Modules\User\Models\User[] $userCache */
$userCache = $this->get('userCache');

/** @var \Ilch\Validation|null $validationResult */
$validationResult = $this->get('validation');

/** @var int $remainingFloodSeconds */
$remainingFloodSeconds = (int)$this->get('remainingFloodSeconds');

/** @var \Ilch\Validation\ErrorBag|null $errorBag */
$errorBag = $validationResult !== null ? $validationResult->getErrorBag() : null;

/** @var \Ilch\Config\Database $config */
$config = \Ilch\Registry::get('config');
?>
<link href="<?=$this->getModuleUrl('../shoutbox/static/css/shoutbox.css') ?>" rel="stylesheet">
<script>
    $(function() {
        let $shoutboxContainer = $('#shoutbox-container<?=$this->get('uniqid') ?>'),
            reloadCaptcha = function() {
                let $img = $shoutboxContainer.find('.shoutbox-captcha-image');

                if ($img.length) {
                    $img.attr('src', '<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php?' + Math.random());
                }
            },
            showForm = function() {
                // Reload the captcha so the visible image always matches the session value,
                // even with multiple captchas on the page.
                reloadCaptcha();
                $("#shoutbox-button-container<?=$this->get('uniqid') ?>").slideUp(200, function() {
                    $("#shoutbox-form-container<?=$this->get('uniqid') ?>").slideDown(400);
                });
            },
            hideForm = function(afterHide) {
                $("#shoutbox-form-container<?=$this->get('uniqid') ?>").slideUp(400, function() {
                    $("#shoutbox-button-container<?=$this->get('uniqid') ?>").slideDown(200, afterHide);
                });
            },
            floodTimer = null,
            startFloodCountdown = function() {
                let $floodAlert = $shoutboxContainer.find('.shoutbox-flood-alert');

                if (floodTimer !== null) {
                    clearInterval(floodTimer);
                    floodTimer = null;
                }

                if (!$floodAlert.length) {
                    return;
                }

                let remaining = parseInt($floodAlert.data('seconds'), 10);

                floodTimer = setInterval(function() {
                    remaining--;

                    if (remaining <= 0) {
                        clearInterval(floodTimer);
                        floodTimer = null;
                        $floodAlert.slideUp(300, function() {
                            $(this).remove();
                        });
                    } else {
                        $floodAlert.find('.shoutbox-flood-seconds').text(remaining);
                    }
                }, 1000);
            },
            autoRefreshPending = false,
            refreshMessages = function() {
                if (document.visibilityState === 'hidden' || autoRefreshPending) {
                    return;
                }

                autoRefreshPending = true;
                $.get('<?=$this->getUrl('shoutbox/index/ajax') ?>', function(html) {
                    // parseHTML (keepScripts=false) prevents the inline script of the response from being executed again.
                    let $newMessages = $($.parseHTML(html)).find('.shoutbox-messages').first(),
                        $currentMessages = $shoutboxContainer.find('.shoutbox-messages').first();

                    if ($newMessages.length && $currentMessages.length && $newMessages.html() !== $currentMessages.html()) {
                        $currentMessages.html($newMessages.html());
                    }
                }).always(function() {
                    autoRefreshPending = false;
                });
            };

        startFloodCountdown();

        <?php if ((int)$this->get('autoRefreshInterval') > 0) : ?>
        setInterval(refreshMessages, <?=(int)$this->get('autoRefreshInterval') ?> * 1000);
        <?php endif; ?>

        // Stop the countdown if the alert gets dismissed manually.
        $shoutboxContainer.on('closed.bs.alert', '.shoutbox-flood-alert', function() {
            if (floodTimer !== null) {
                clearInterval(floodTimer);
                floodTimer = null;
            }
        });

        // Load a new captcha on demand.
        $shoutboxContainer.on('click', '.shoutbox-captcha-reload', function() {
            reloadCaptcha();
            $shoutboxContainer.find('input[name=captcha]').val('').trigger('focus');
        });


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
                        startFloodCountdown();
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
                return;
            }

            if ($form.find('[name=shoutbox_textarea]').val() === '') {
                alert(<?=json_encode($this->getTrans('missingMessage')) ?>);
                return;
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
    <?php if ($validationResult !== null && !$validationResult->isValid()) : ?>
        <div class="alert alert-danger alert-dismissible">
            <?php foreach ($validationResult->getErrorBag()->getErrorMessages() as $errorMessage) : ?>
                <div><?=$this->escape($errorMessage) ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($remainingFloodSeconds > 0) : ?>
        <div class="alert alert-warning alert-dismissible shoutbox-flood-alert" data-seconds="<?=$remainingFloodSeconds ?>">
            <?=$this->getTrans('floodProtection', '<span class="shoutbox-flood-seconds">' . $remainingFloodSeconds . '</span>') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div id="shoutbox-button-container<?=$this->get('uniqid') ?>">
        <div class="row mb-3">
            <div class="col-xl-12">
                <?php if (is_in_array($this->get('writeAccess'), explode(',', $config->get('shoutbox_writeaccess')))) : ?>
                    <div class="float-start">
                        <button class="btn btn-outline-secondary" id="shoutbox-slide-down<?=$this->get('uniqid') ?>"><?=$this->getTrans('answer') ?></button>
                    </div>
                <?php endif; ?>
                <?php if (count($this->get('shoutbox')) == $config->get('shoutbox_limit')) : ?>
                    <div class="float-end">
                        <a href="<?=$this->getUrl('shoutbox/index/index/') ?>" class="btn btn-outline-secondary"><?=$this->getTrans('archive') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (is_in_array($this->get('writeAccess'), explode(',', $config->get('shoutbox_writeaccess')))) : ?>
        <div id="shoutbox-form-container<?=$this->get('uniqid') ?>" style="display: none;">
            <form id="shoutboxForm_<?=$this->get('uniqid') ?>" name="shoutboxForm_<?=$this->get('uniqid') ?>" method="post">
                <input type="hidden" name="uniqid" value="<?=$this->get('uniqid') ?>">
               <?=$this->getTokenField() ?>
                <div class="row mb-3 d-none">
                    <label class="col-xl-2 col-form-label" for="bot">
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
                <div class="row mb-3<?=($errorBag !== null && $errorBag->hasError('shoutbox_name')) ? ' has-error' : '' ?>">
                    <div class="col-xl-12">
                        <input type="text"
                               class="form-control"
                               name="shoutbox_name"
                               placeholder="Name"
                               maxlength="100"
                               value="<?=($this->getUser() !== null) ? $this->escape($this->getUser()->getName()) : '' ?>"
                               <?=($this->getUser() !== null) ? 'readonly' : 'required' ?> />
                    </div>
                </div>
                <div class="row mb-3<?=($errorBag !== null && $errorBag->hasError('shoutbox_textarea')) ? ' has-error' : '' ?>">
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
                            <div class="row mb-3<?=($errorBag !== null && $errorBag->hasError('captcha')) ? ' has-error' : '' ?>">
                                <div class="col-xl-12 mb-2">
                                    <img src="<?=$this->getUrl() ?>/application/libraries/Captcha/Captcha.php"
                                         class="shoutbox-captcha-image"
                                         alt="<?=$this->getTrans('captcha') ?>">
                                </div>
                                <div class="col-xl-12">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               name="captcha"
                                               autocomplete="off"
                                               placeholder="<?=$this->getTrans('captcha') ?>"
                                               required>
                                        <span class="input-group-text">
                                            <a href="javascript:void(0)" class="shoutbox-captcha-reload" title="<?=$this->getTrans('reloadCaptcha') ?>">
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="float-start">
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
                            <div class="float-end">
                                <a href="<?=$this->getUrl('shoutbox/index/index/') ?>" class="btn btn-secondary"><?=$this->getTrans('archive') ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="shoutbox shoutbox-messages table-responsive">
        <table class="table table-bordered table-striped">
            <?php if (!empty($this->get('shoutbox'))) : ?>
                <?php
                /** @var \Modules\Shoutbox\Models\Shoutbox $shoutbox */
                foreach ($this->get('shoutbox') as $shoutbox) : ?>
                    <?php $user = $userCache[$shoutbox->getUid()] ?>
                    <?php $date = new \Ilch\Date($shoutbox->getTime()) ?>
                    <tr>
                        <?php if ($shoutbox->getUid() == '0' || empty($user)) : ?>
                            <td>
                                <b><?=$this->escape($shoutbox->getName()) ?>:</b><br />
                                <span class="small"><?=$date->format('d.m.Y H:i', true) ?></span>
                            </td>
                        <?php else : ?>
                            <td>
                                <img class="avatar" src="<?=$this->getStaticUrl() . '../' . $user->getAvatar() ?>" alt="<?=$this->escape($user->getName()) ?>">
                                <b><a href="<?=$this->getUrl('user/profil/index/user/' . $user->getId()) ?>"><?=$this->escape($user->getName()) ?></a></b>:<br />
                                <span class="small"><?=$date->format('d.m.Y H:i', true) ?></span>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td class="shoutbox-text"><?=$this->escape($shoutbox->getTextarea()) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td><?=$this->getTrans('noEntries') ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
