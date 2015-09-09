<?php
$i = 0;
$modulesToInstall = $this->get('modulesToInstall');
?>

<?php foreach ($this->get('modules') as $key => $module): ?>
    <?php if ($i == 0): ?>
        <i><?=$this->getTrans('obligatoryModules') ?></i><br /><br />
    <?php endif; ?>

    <?php if ($i !== 0 && $i % 9 == 0): ?>
        </div>
    <?php endif; ?>

    <?php if ($i == 9): ?>
        <br /><i><?=$this->getTrans('optionalModules') ?></i><br /><br />
    <?php endif; ?>

    <?php if ($i % 9 == 0): ?>
        <div class="row">
    <?php endif; ?>

    <div class="col-lg-4">
        <input id="module_<?=$key ?>" <?php if(isset($module['config']->config['system_module'])) { echo 'disabled="disabled"';} ?>
            <?php if(
                        isset($module['config']->config['system_module'])
                    ||
                        (
                            !empty($modulesToInstall)
                            && in_array($key, $modulesToInstall)
                        )
                    ||
                        (
                            empty($modulesToInstall)
                            && (isset($module['checked']))
                        ))
                { echo 'checked="checked"'; } ?>
               type="checkbox"
               name="modulesToInstall[]"
               value="<?=$key ?>" />
        <label for="module_<?=$key ?>">
            <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name']; ?>
        </label>
    </div>
    <?php $i++; ?>
<?php endforeach; ?>
