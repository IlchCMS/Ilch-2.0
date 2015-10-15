<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('systemSettings') ?></legend>
    <div class="form-group">
        <label for="startPage" class="col-lg-2 control-label">
            <?=$this->getTrans('startPage') ?>:
        </label>
        <div class="col-lg-8">
            <select class="form-control" name="startPage" id="startPage">
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
                    <?php $layouts = array(); ?>
                    <?php foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath): ?>
                        <?php include_once $layoutPath.'/config/config.php'; ?>
                        <?php if (empty($config['modulekey'])): ?>
                            <?php $config['modulekey'] = ''; ?>
                        <?php endif; ?>

                        <?php $module = $config['modulekey']; ?>
                        <?php $selected = ''; ?>
                        <?php if ($this->get('startPage') == 'layouts_'.$module): ?>
                            <?php $selected = 'selected="selected"'; ?>
                        <?php endif; ?>

                        <?php if(!empty($module)): ?>
                            <option <?=$selected ?> value="layouts_<?=$module ?>"><?=$module ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="multilingualAcp" class="col-lg-2 control-label">
            <?=$this->getTrans('multilingualAcp') ?>:
        </label>
        <div class="col-lg-2">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="multilingualAcp" value="1" id="multilingualAcp-on" <?php if ($this->get('multilingualAcp') == '1') { echo 'checked="checked"'; } ?> />
                <label for="multilingualAcp-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" name="multilingualAcp" value="0" id="multilingualAcp-off" <?php if ($this->get('multilingualAcp') != '1') { echo 'checked="checked"'; } ?> />
                <label for="multilingualAcp-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <div id="contentLanguage" class="form-group <?php if($this->get('multilingualAcp') != '1') { echo 'hidden'; } ?>">
        <label for="languageInput" class="col-lg-2 control-label">
            <?=$this->getTrans('contentLanguage') ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="contentLanguage" id="languageInput">
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
        <div class="col-lg-2">
            <select class="form-control" name="locale" id="languageInput">
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
        <label for="standardMailInput" class="col-lg-2 control-label">
            <?=$this->getTrans('timezone') ?>:
        </label>
        <div class="col-lg-8">
            <select id="timezone" name="timezone" class="form-control">
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
    <div class="form-group">
        <label for="standardMailInput" class="col-lg-2 control-label">
            <?=$this->getTrans('standardMail') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   id="standardMailInput"
                   name="standardMail"
                   type="text"
                   value="<?=$this->escape($this->get('standardMail')) ?>" />
        </div>
    </div>

    <legend><?=$this->getTrans('seo') ?></legend>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-2 control-label">
            <?=$this->getTrans('pageTitle') ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   id="pageTitleInput"
                   name="pageTitle"
                   type="text"
                   value="<?=$this->escape($this->get('pageTitle')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="descriptionInput" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" id="descriptionInput" name="description"><?=$this->escape($this->get('description')) ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="modRewrite" class="col-lg-2 control-label">
            <?=$this->getTrans('modRewrite') ?>:
        </label>
        <div class="col-lg-8">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="modRewrite" value="1" id="modRewrite-on" <?php if ($this->get('modRewrite') == '1') { echo 'checked="checked"'; } ?> />
                <label for="modRewrite-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" name="modRewrite" value="0" id="modRewrite-off" <?php if ($this->get('modRewrite') != '1') { echo 'checked="checked"'; } ?> />
                <label for="modRewrite-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <legend><?=$this->getTrans('backendFunctions') ?></legend>
    <div class="form-group">
        <label for="navbarFixed" class="col-lg-2 control-label">
            <?=$this->getTrans('navbarFixed') ?>:
        </label>
        <div class="col-lg-8">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" name="navbarFixed" value="1" id="navbarFixed-on" <?php if ($this->get('navbarFixed') == 'navbar-fixed-top') { echo 'checked="checked"'; } ?> />
                <label for="navbarFixed-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" name="navbarFixed" value="0" id="navbarFixed-off" <?php if ($this->get('navbarFixed') == '') { echo 'checked="checked"'; } ?> />
                <label for="navbarFixed-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
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
