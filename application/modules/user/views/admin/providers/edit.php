<form
    action="<?=$this->getUrl([
        'module' => 'user',
        'controller' => 'providers',
        'action' => 'save',
        'key' => $this->get('provider')->getKey()
    ]) ?>"
    method="POST"
    class="form-horizontal"
>
    <h1><?= $this->getTrans('authProvider') ?> <?= $this->get('provider')->getName() ?></h1>
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="moduleInput" class="col-lg-3 control-label">
            <?=$this->getTrans('module') ?>
        </label>
        <div class="col-lg-9">
            <select id="moduleInput" name="module" class="form-control">
                <option value=""><?= $this->getTrans('deactivateAuthProvider') ?></option>
                <?php foreach ($this->get('modules') as $module): ?>
                    <option
                        value="<?= $module->getModule() ?>"
                        <?= $module->getModule() === $this->get('provider')->getModule() ? 'selected="selected"' : '' ?>
                    >
                        <?= $module->getName() ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="help-block"><?= $this->getTrans('providersModuleHelpText') ?></span>
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
