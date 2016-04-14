<?php $modulesToInstall = $this->get('modulesToInstall'); ?>
<br><i><?=$this->getTrans('obligatoryModules') ?></i><br><br>
<div class="row">
    <?php foreach ($this->get('modules') as $key => $module): ?>
        <?php if(isset($module['config']->config['system_module'])): ?>

                <div class="col-lg-4">
                    <input id="module_<?=$key ?>" disabled="disabled" checked="checked" name="modulesToInstall[]" value="<?=$key ?>" type="checkbox">
                    <label for="module_<?=$key ?>">
                        <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name']; ?>
                    </label>
                </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<br><i><?=$this->getTrans('optionalModules') ?></i><br><br>
<div class="row">
    <?php foreach ($this->get('modules') as $key => $module): ?>
        <?php if(!isset($module['config']->config['system_module'])): ?>
            <div class="col-lg-4">
                <input id="module_<?=$key ?>" name="modulesToInstall[]" value="<?=$key ?>" type="checkbox"
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
