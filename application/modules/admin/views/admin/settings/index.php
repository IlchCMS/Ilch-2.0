<form class="form-horizontal" method="POST" action="<?php echo $this->url(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend><?php echo $this->trans('systemSettings'); ?></legend>
    <div class="form-group">
        <label for="languageInput" class="col-lg-2 control-label">
            <?php echo $this->trans('chooseLanguage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="language" id="languageInput">
                <?php
                foreach ($this->get('languages') as $key => $value) {
                    $selected = '';

                    if ($this->getTranslator()->getLocale() == $key) {
                        $selected = 'selected="selected"';
                    }

                    echo '<option '.$selected.' value="'.$key.'">'.$this->escape($value).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="startPage" class="col-lg-2 control-label">
            <?php echo $this->trans('startPage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="startPage" id="startPage">
                <optgroup label="<?php echo $this->trans('pages'); ?>">
                <?php
                    foreach ($this->get('pages') as $page) {
                        $selected = '';

                        if ($this->get('startPage') == 'page_'.$page->getID()) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option '.$selected.' value="page_'.$page->getID().'">'.$this->escape($page->getTitle()).'</option>';
                    }
                ?>
                </optgroup>
                <optgroup label="<?php echo $this->trans('modules'); ?>">
                <?php
                    $moduleMapper = new \Admin\Mappers\Module();

                    foreach ($this->get('modules') as $module) {
                        $selected = '';

                        if ($this->get('startPage') == 'module_'.$module->getKey()) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option '.$selected.' value="module_'.$module->getKey().'">'.$this->escape($module->getName($this->getTranslator()->getLocale())).'</option>';
                    }
                ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="maintenanceMode" class="col-lg-2 control-label">
            <?php echo $this->trans('maintenanceMode'); ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="maintenanceMode"
                       id="maintenanceMode"
                       value="1"
                <?php if ($this->get('maintenanceMode') == '1') { echo 'checked="checked"';} ?> /> <?php echo $this->trans('on'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="maintenanceMode"
                       value="0"
                <?php if ($this->get('maintenanceMode') != '1') { echo 'checked="checked"';} ?>> <?php echo $this->trans('off'); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-2 control-label">
            <?php echo $this->trans('pageTitle'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   id="pageTitleInput"
                   name="pageTitle"
                   type="text"
                   value="<?php echo $this->escape($this->get('pageTitle')); ?>" />
        </div>
    </div>
    <div class="content_savebox">
        <button type="submit" name="save" class="btn">
            <?php echo $this->trans('saveButton'); ?>
        </button>
    </div>
</form>
