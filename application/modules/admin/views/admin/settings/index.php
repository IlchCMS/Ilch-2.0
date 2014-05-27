<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
    <?php echo $this->getTokenField(); ?>
    <legend><?php echo $this->getTrans('systemSettings'); ?></legend>
    <div class="form-group">
        <label for="startPage" class="col-lg-2 control-label">
            <?php echo $this->getTrans('startPage'); ?>:
        </label>
        <div class="col-lg-8">
            <select class="form-control" name="startPage" id="startPage">
                <optgroup label="<?php echo $this->getTrans('pages'); ?>">
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
                <optgroup label="<?php echo $this->getTrans('modules'); ?>">
                <?php
                    $moduleMapper = new \Modules\Admin\Mappers\Module();

                    foreach ($this->get('modules') as $module) {
                        $content = $module->getContentForLocale($this->getTranslator()->getLocale());
                        $selected = '';

                        if ($this->get('startPage') == 'module_'.$module->getKey()) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option '.$selected.' value="module_'.$module->getKey().'">'.$this->escape($content['name']).'</option>';
                    }
                ?>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="maintenanceMode" class="col-lg-2 control-label">
            <?php echo $this->getTrans('maintenanceMode'); ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="maintenanceMode"
                       value="1"
                <?php if ($this->get('maintenanceMode') == '1') { echo 'checked="checked"';} ?> /> <?php echo $this->getTrans('on'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="maintenanceMode"
                       value="0"
                <?php if ($this->get('maintenanceMode') != '1') { echo 'checked="checked"';} ?>> <?php echo $this->getTrans('off'); ?>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="multilingualAcp" class="col-lg-2 control-label">
            <?php echo $this->getTrans('multilingualAcp'); ?>:
        </label>
        <div class="col-lg-2">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="multilingualAcp"
                       id="multilingualAcp"
                       value="1"
                <?php if ($this->get('multilingualAcp') == '1') { echo 'checked="checked"';} ?> /> <?php echo $this->getTrans('on'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="multilingualAcp"
                       value="0"
                <?php if ($this->get('multilingualAcp') != '1') { echo 'checked="checked"';} ?>> <?php echo $this->getTrans('off'); ?>
                </label>
            </div>
        </div>
    </div>
    <div id="contentLanguage" class="form-group <?php if($this->get('multilingualAcp') != '1'){ echo 'hidden'; } ?>">
        <label for="languageInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('contentLanguage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="contentLanguage" id="languageInput">
                <?php
                foreach ($this->get('languages') as $key => $value) {
                    $selected = '';

                    if ($this->get('contentLanguage') == $key) {
                        $selected = 'selected="selected"';
                    }

                    echo '<option '.$selected.' value="'.$key.'">'.$this->escape($value).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <legend>SEO</legend>
    <div class="form-group">
        <label for="descriptionInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('description'); ?>:
        </label>
        <div class="col-lg-8">
            <textarea class="form-control" id="descriptionInput" name="description"><?php
                echo $this->escape($this->get('description')); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('pageTitle'); ?>:
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   id="pageTitleInput"
                   name="pageTitle"
                   type="text"
                   value="<?php echo $this->escape($this->get('pageTitle')); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="modRewrite" class="col-lg-2 control-label">
            <?php echo $this->getTrans('modRewrite'); ?>:
        </label>
        <div class="col-lg-8">
            <div class="radio">
                <label>
                    <input type="radio"
                       name="modRewrite"
                       id="modRewrite"
                       value="1"
                <?php if ($this->get('modRewrite') == '1') { echo 'checked="checked"';} ?> /> <?php echo $this->getTrans('on'); ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio"
                       name="modRewrite"
                       value="0"
                <?php if ($this->get('modRewrite') != '1') { echo 'checked="checked"';} ?>> <?php echo $this->getTrans('off'); ?>
                </label>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar()?>
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