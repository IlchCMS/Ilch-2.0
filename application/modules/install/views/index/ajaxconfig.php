<?php
$i = 0;
$modulesToInstall = $this->get('modulesToInstall');

foreach ($this->get('modules') as $key => $module) {
    if ($i == 0) {
        echo '<i>'.$this->getTrans('obligatoryModules').'</i><br /><br />';
    }

    if ($i !== 0 && $i % 9 == 0) {
        echo '</div>';
    }

    if ($i == 9) {
        echo '<br /><i>'.$this->getTrans('optionalModules').'</i><br /><br />';
    }

    if ($i % 9 == 0) {
        echo '<div class="row">';
    }
?>
    <div class="col-lg-4">
        <input id="module_<?=$key?>" <?php if(isset($module['config']->config['system_module'])) { echo 'disabled="disabled"';} ?>
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
               value="<?=$key?>" />
        <label for="module_<?=$key?>">
            <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name']; ?>
        </label>
    </div>
<?php
    $i++;
}
?>