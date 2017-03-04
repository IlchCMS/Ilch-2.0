<legend><?=$this->getTrans('settings') ?></legend>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="startPage" class="col-lg-2 control-label">
            <?=$this->getTrans('startPage') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="startPage" name="startPage">
                <optgroup label="<?=$this->getTrans('pages') ?>">
                    <?php foreach ($this->get('pages') as $page): ?>
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
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('multilingualAcp') ? 'has-error' : '' ?>">
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
    <div id="contentLanguage" class="form-group <?php if ($this->get('multilingualAcp') != '1') { echo 'hidden'; } ?>">
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
    <div id="contentLanguage" class="form-group">
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
    <div class="form-group">
        <label for="timezone" class="col-lg-2 control-label">
            <?=$this->getTrans('timezone') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="timezone" name="timezone">
                <?php $timezones = $this->get('timezones'); ?>
                <?php for ($i = 0; $i < count($timezones); $i++): ?>
                    <?php $sel = ''; ?>
                    <?php if ($this->get('timezone') == $timezones[$i]): ?>
                        <?php $sel = 'selected="selected"'; ?>
                    <?php endif; ?>

                    <option <?=$sel ?> value="<?=$this->escape($timezones[$i]) ?>"><?=$this->escape($timezones[$i]) ?></option>
               <?php endfor; ?>
            </select>
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('standardMail') ? 'has-error' : '' ?>">
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
    <div class="form-group <?=$this->validation()->hasError('defaultPaginationObjects') ? 'has-error' : '' ?>">
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
    <div class="form-group <?=$this->validation()->hasError('modRewrite') ? 'has-error' : '' ?>">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('modRewrite') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="modRewrite-on" name="modRewrite" value="1" <?php if ($this->get('modRewrite') == '1') { echo 'checked="checked"'; } ?> />
                <label for="modRewrite-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="modRewrite-off" name="modRewrite" value="0" <?php if ($this->get('modRewrite') != '1') { echo 'checked="checked"'; } ?> />
                <label for="modRewrite-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <legend><?=$this->getTrans('backendFunctions') ?></legend>
    <div id="hmenuFixed" class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('hmenuFixed') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="hmenuFixed-on" name="hmenuFixed" value="1" <?php if ($this->get('hmenuFixed') == 'hmenu-fixed') { echo 'checked="checked"'; } ?> />
                <label for="hmenuFixed-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="hmenuFixed-off" name="hmenuFixed" value="0" <?php if ($this->get('hmenuFixed') == '') { echo 'checked="checked"'; } ?> />
                <label for="hmenuFixed-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
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
</script>
