<?php
    $updateservers = $this->get('updateservers');
    $index = 0;
    $isHTTPS = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $pages = (empty($this->get('pages')) ? [] : $this->get('pages'));
?>
<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row form-group ilch-margin-b">
        <label for="startPage" class="col-lg-2 control-label">
            <?=$this->getTrans('startPage') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="startPage" name="startPage">
                <optgroup label="<?=$this->getTrans('pages') ?>">
                    <?php foreach ($pages as $page): ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('startPage') == 'page_'.$page->getID()): ?>
                            <?php $selected = 'selected="selected"'; ?>
                        <?php endif; ?>

                        <option <?=$selected ?> value="page_<?=$page->getID() ?>"><?=$this->escape($page->getTitle()) ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="<?=$this->getTrans('modules') ?>">
                    <?php $moduleMapper = new \Modules\Admin\Mappers\Module(); ?>
                    <?php foreach ($this->get('modules') as $module): ?>
                        <?php $content = $module->getContentForLocale($this->getTranslator()->getLocale()); ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('startPage') == 'module_'.$module->getKey()): ?>
                            <?php $selected = 'selected="selected"'; ?>
                        <?php endif; ?>

                        <option <?=$selected ?> value="module_<?=$module->getKey() ?>"><?=$this->escape($content['name']) ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="<?=$this->getTrans('layouts') ?>">
                <?php $layouts = []; ?>
                <?php foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath): ?>
                    <?php if (is_dir($layoutPath)): ?>
                        <?php
                        $configClass = '\\Layouts\\'.ucfirst(basename($layoutPath)).'\\Config\\Config';
                        $config = new $configClass($this->getTranslator()); ?>
                        <?php if (empty($config->config['modulekey'])): ?>
                            <?php $config->config['modulekey'] = ''; ?>
                        <?php endif; ?>

                        <?php $module = $config->config['modulekey']; ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('startPage') == 'layouts_'.$module): ?>
                            <?php $selected = 'selected="selected"'; ?>
                        <?php endif; ?>

                        <?php if (!empty($module)): ?>
                            <option <?=$selected ?> value="layouts_<?=$module ?>"><?=$module ?></option>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('multilingualAcp') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('multilingualAcp') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="multilingualAcp-on" name="multilingualAcp" value="1" <?php if ($this->get('multilingualAcp') == '1') { echo 'checked="checked"'; } ?> />
                <label for="multilingualAcp-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="multilingualAcp-off" name="multilingualAcp" value="0" <?php if ($this->get('multilingualAcp') != '1') { echo 'checked="checked"'; } ?> />
                <label for="multilingualAcp-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="contentLanguage" class="row form-group ilch-margin-b <?php if ($this->get('multilingualAcp') != '1') { echo 'hidden'; } ?>">
        <label for="languageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('contentLanguage') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="languageInput" name="contentLanguage">
                <?php foreach ($this->get('languages') as $key => $value): ?>
                    <?php $selected = ''; ?>
                    <?php if ($this->get('contentLanguage') == $key): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$selected ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div id="contentLanguage" class="row form-group ilch-margin-b">
        <label for="localeInput" class="col-lg-2 control-label">
            <?=$this->getTrans('locale') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="languageInput" name="locale">
                <?php foreach ($this->get('languages') as $key => $value): ?>
                    <?php $selected = ''; ?>
                    <?php if ($this->get('locale') == $key): ?>
                        <?php $selected = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$selected ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="timezone" class="col-lg-2 control-label">
            <?=$this->getTrans('timezone') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="timezone" name="timezone">
                <?php $timezones = $this->get('timezones'); ?>
                <?php for ($i = 0, $iMax = count($timezones); $i < $iMax; $i++): ?>
                    <?php $sel = ''; ?>
                    <?php if ($this->get('timezone') == $timezones[$i]): ?>
                        <?php $sel = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$sel ?> value="<?=$this->escape($timezones[$i]) ?>"><?=$this->escape($timezones[$i]) ?></option>
               <?php endfor; ?>
            </select>
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('standardMail') ? 'has-error' : '' ?>">
        <label for="standardMailInput" class="col-lg-2 control-label">
            <?=$this->getTrans('standardMail') ?>:
        </label>
        <div class="col-lg-4">
            <input type="email"
                   class="form-control"
                   id="standardMailInput"
                   name="standardMail"
                   value="<?=$this->escape($this->get('standardMail')) ?>"
                   required />
        </div>
    </div>
    <div class="row form-group ilch-margin-b <?=$this->validation()->hasError('defaultPaginationObjects') ? 'has-error' : '' ?>">
        <label for="defaultPaginationObjectsInput" class="col-lg-2 control-label">
            <?=$this->getTrans('defaultPaginationObjects') ?>:
        </label>
        <div class="col-lg-1">
            <input type="number"
                   class="form-control"
                   id="defaultPaginationObjectsInput"
                   name="defaultPaginationObjects"
                   min="1"
                   value="<?=$this->escape($this->get('defaultPaginationObjects')) ?>" />
        </div>
    </div>

    <h1><?=$this->getTrans('captcha') ?></h1>
    <div class="row form-group ilch-margin-b">
        <div for="captcha" class="col-lg-2 control-label">
            <?=$this->getTrans('captcha') ?>:
        </div>
        <div class="col-lg-4">
            <select class="form-control" id="captcha" name="captcha">
                <option <?=($this->get('captcha') == 0 || !$this->get('captcha') ? 'selected="selected"' : '') ?> value="0"><?=$this->getTrans('default') ?></option>
                <option <?=($this->get('captcha') == 2 ? 'selected="selected"' : '') ?> value="2"><?=$this->getTrans('grecaptcha') ?> V2</option>
                <option <?=($this->get('captcha') == 3 ? 'selected="selected"' : '') ?> value="3"><?=$this->getTrans('grecaptcha') ?> V3</option>
            </select>
        </div>
    </div>
    <div id="captcha_apikey_info" class="form-group">
        <div class="col-lg-6 alert alert-info">
            <?=$this->getTrans('captcha_apikey_info', '<a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a>') ?>
        </div>
    </div>
    <div id="captcha_apikey" class="">
        <div class="row form-group ilch-margin-b">
            <label for="captcha_apikey" class="col-lg-2 control-label">
                    <?=$this->getTrans('captcha_apikey') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       id="captcha_apikey"
                       name="captcha_apikey"
                       value="<?=$this->get('captcha_apikey') ?>" />
            </div>
        </div>
    </div>
    <div id="captcha_seckey" class="">
        <div class="row form-group ilch-margin-b">
            <label for="captcha_seckey" class="col-lg-2 control-label">
                    <?=$this->getTrans('captcha_seckey') ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       type="text"
                       id="captcha_seckey"
                       name="captcha_seckey"
                       value="<?=$this->get('captcha_seckey') ?>" />
            </div>
        </div>
    </div>
    <div class="row form-group ilch-margin-b">
        <label for="hideCaptchaFor" class="col-lg-2 control-label">
            <?=$this->getTrans('hideCaptchaFor') ?>:
        </label>
        <div class="col-lg-4">
            <select class="chosen-select form-control"
                    id="hideCaptchaFor"
                    name="groups[]"
                    data-placeholder="<?=$this->getTrans('hideCaptchaFor') ?>"
                    multiple>
                <?php
                foreach ($this->get('groupList') as $group) {
                    ?>
                    <option value="<?=$group->getId() ?>"
                        <?php
                        foreach ($this->get('hideCaptchaFor') as $assignedGroup) {
                            if ($group->getId() == $assignedGroup) {
                                echo 'selected="selected"';
                                break;
                            }
                        }
                        ?>>
                        <?=$this->escape($group->getName()) ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <h1><?=$this->getTrans('htmlPurifier') ?></h1>
    <p><?=$this->getTrans('htmlPurifierDescription') ?></p>
    <div id="htmlPurifier" class="row form-group ilch-margin-b">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('htmlPurifier') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="htmlPurifier-on" name="htmlPurifier" value="1" <?= ($this->get('htmlPurifier')) ? 'checked="checked"' : '' ?> />
                <label for="htmlPurifier-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="htmlPurifier-off" name="htmlPurifier" value="0" <?= (!$this->get('htmlPurifier')) ? 'checked="checked"' : '' ?> />
                <label for="htmlPurifier-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <h1><?=$this->getTrans('backendFunctions') ?></h1>
    <div id="hmenuFixed" class="row form-group ilch-margin-b">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('hmenuFixed') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="hmenuFixed-on" name="hmenuFixed" value="1" <?php if ($this->get('hmenuFixed') === 'hmenu-fixed') { echo 'checked="checked"'; } ?> />
                <label for="hmenuFixed-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="hmenuFixed-off" name="hmenuFixed" value="0" <?php if ($this->get('hmenuFixed') == '') { echo 'checked="checked"'; } ?> />
                <label for="hmenuFixed-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <h1><?=$this->getTrans('updateserver') ?></h1>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col class="icon_width">
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
                <th></th>
                <th></th>
                <th><?=$this->getTrans('url') ?></th>
                <th><?=$this->getTrans('operator') ?></th>
                <th><?=$this->getTrans('country') ?></th>
            </thead>
            <tbody>
                <?php foreach ($updateservers as $updateserver): ?>
                    <?php if ($isHTTPS && (strpos($updateserver->getURL(), 'https://') === false)) {
                        continue;
                    } ?>
                    <tr>
                        <td><input type="radio" id="updateserver<?=$index ?>" name="updateserver" value="<?=$updateserver->getURL() ?>" <?=($updateserver->getURL() == $this->get('updateserver')) ? ' checked="checked"' : '' ?>></td>
                        <td><?=(strpos($updateserver->getURL(), 'https://') !== false) ? '<span class="fa-solid fa-lock"></span>': '<span class="fa-solid fa-unlock"></span>' ?></td>
                        <td><?=$this->escape($updateserver->getURL()) ?></td>
                        <td><?=$this->escape($updateserver->getOperator()) ?></td>
                        <td><?=$this->escape($updateserver->getCountry()) ?></td>
                    </tr>
                <?php $index++; endforeach; ?>
            </tbody>
        </table>
    </div>
    <?=$this->getSaveBar() ?>
</form>

<script>
$('[name="multilingualAcp"]').click(function () {
    if ($(this).val() == "1") {
        $('#contentLanguage').removeClass('hidden');
    } else {
        $('#contentLanguage').addClass('hidden');
    }
});

$('[name="captcha"]').change(function () {
    if ($(this).val() == "2" || $(this).val() == "3") {
        $('#captcha_apikey').removeClass('hidden');
        $('#captcha_seckey').removeClass('hidden');
        $('#captcha_apikey_info').removeClass('hidden');
    } else {
        $('#captcha_apikey').addClass('hidden');
        $('#captcha_seckey').addClass('hidden');
        $('#captcha_apikey_info').addClass('hidden');
    }
});
$('[name="captcha"]').change();

$('#hideCaptchaFor').chosen();
</script>
