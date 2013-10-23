<h2>
    <?php echo $this->trans('welcomeToInstall', array('[VERSION]' => VERSION)); ?>
</h2>
<br />
<div class="form-group">
    <label for="languageInput" class="col-lg-2 control-label">
        <?php echo $this->trans('chooseLanguage'); ?>:
    </label>
    <div class="col-lg-3">
        <select name="language" id="languageInput" class="form-control">
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
    <label for="timezone" class="col-lg-2 control-label">
        <?php echo $this->trans('timezone'); ?>:
    </label>
    <div class="col-lg-3">
        <select id="timezone" name="timezone" class="form-control">
            <?php
                $timezones = $this->get('timezones');

                for ($i = 0; $i < count($timezones); $i++) {
                    $sel = '';
                    if ($this->get('timezone') == $timezones[$i]) {
                        $sel = 'selected="selected"';
                    }

                    echo '<option '.$sel.' value="'.$this->escape($timezones[$i]).'">'.$this->escape($timezones[$i]).'</option>';
                }
            ?>
        </select>
    </div>
</div>
<script>
    $('#languageInput').change
    (
        this,
        function () {
            top.location.href = '<?php echo $this->url(array('action' => 'index')); ?>/language/'+$(this).val();
        }
    );
</script>
