<div class="row module-select">
    <b><i><?=$this->getTrans('obligatoryModules') ?>:</i></b><br><br>
    <?php foreach ($this->get('modules') as $key => $module): ?>
        <?php if (isset($module['config']->config['system_module'])): ?>
            <div class="col-lg-4 col-md-3 col-sm-3">
                <input type="checkbox" id="module_<?=$key ?>" name="modulesToInstall[]" disabled="disabled" checked="checked" value="<?=$key ?>">
                <label for="module_<?=$key ?>">
                    <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name']; ?>
                </label>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <span class="clearfix"></span>
    <br><b><i><?=$this->getTrans('optionalModules') ?>:</i></b><br><br>
    <?php foreach ($this->get('modules') as $key => $module): ?>
        <?php if (!isset($module['config']->config['system_module'])): ?>
            <div class="col-lg-4 col-md-3 col-sm-3">
                <input type="checkbox" id="module_<?=$key ?>" name="modulesToInstall[]" value="<?=$key ?>"
                    <?php if (isset($module['checked'])) {
                        echo 'checked="checked"';
                    } ?>>
                <label for="module_<?=$key ?>">
                    <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name']; ?>
                </label>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
