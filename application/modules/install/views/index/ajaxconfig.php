<?php

/** @var \Ilch\View $this */

/** @var array $modules */
$modules = $this->get('modules');
?>
<div class="row module-select">
    <b><i><?=$this->getTrans('obligatoryModules') ?>:</i></b><br><br>
    <span class="clearfix"></span>
    <?php foreach ($modules as $key => $module) : ?>
        <?php if (isset($module['config']->config['system_module'])) : ?>
            <div class="col-lg-4 col-md-3 col-sm-3">
                <input type="checkbox" id="module_<?=$key ?>" name="modulesToInstall[]" disabled="disabled" checked="checked" value="<?=$key ?>">
                <label for="module_<?=$key ?>">
                    <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name'] ?>
                </label>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <span class="clearfix"></span>
    <br><b><i><?=$this->getTrans('optionalModules') ?>:</i></b><br><br>
    <div class="alert alert-danger d-none" role="alert" id="dependencyMessage"></div>
    <span class="clearfix"></span>
    <?php foreach ($modules as $key => $module) : ?>
        <?php if (!isset($module['config']->config['system_module'])) : ?>
            <div class="col-lg-4 col-md-3 col-sm-3">
                <input type="checkbox" id="module_<?=$key ?>" name="modulesToInstall[]" value="<?=$key ?>" <?=(isset($module['checked'])) ? 'checked="checked"' : '' ?>>
                <label for="module_<?=$key ?>">
                    <?=$module['config']->config['languages'][$this->getTranslator()->getLocale()]['name'] ?>
                </label>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<script>
var dependencies = <?=json_encode($this->get('dependencies')) ?>;

$(function() {
    var dependencyFound = false;
    // Go through all dependencies when any checkbox is clicked
    $("input:checkbox").change(function() {
        $.each(dependencies, function(k, v) {
            $.each(v, function(i, e) {
                // If any dependency is checked, then the current item needs to be checked, too
                if ($("#module_"+k + ":checked").length) {
                    dependencyFound = true;
                    $("#module_"+i).prop('checked', true);
                }
            });
        });

        if (dependencyFound) {
            document.getElementById('dependencyMessage').innerHTML = "<?=$this->getTrans('dependencyMessage') ?>";
            $("#dependencyMessage").removeClass("d-none");
        }
    });
});
</script>
