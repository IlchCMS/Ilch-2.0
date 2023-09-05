<?php

/** @var \Ilch\View $this */

/** @var array $languages */
$languages = $this->get('languages');

/** @var array $timezones */
$timezones = $this->get('timezones') ?? [];
?>
<div class="mb-3">
    <label for="languageInput" class="col-lg-3 form-label">
        <?=$this->getTrans('chooseLanguage') ?>:
    </label>
    <div class="col-lg-4">
        <select class="form-control" id="languageInput" name="language">
            <?php foreach ($languages as $key => $value) : ?>
                <option <?=($this->getTranslator()->getLocale() == $key) ? 'selected="selected"' : '' ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="mb-3 <?=$this->validation()->hasError('timezone') ? 'has-error' : '' ?>">
    <label for="timezone" class="col-lg-3 form-label">
        <?=$this->getTrans('timezone') ?>:
    </label>
    <div class="col-lg-4">
        <select class="form-control" id="timezone" name="timezone">
            <?php for ($i = 0, $iMax = count($timezones); $i < $iMax; $i++) : ?>
                <option <?=($this->originalInput('timezone', $this->get('timezone')) == $timezones[$i]) ? 'selected="selected"' : '' ?> value="<?=$this->escape($timezones[$i]) ?>"><?=$this->escape($timezones[$i]) ?></option>
            <?php endfor; ?>
        </select>
    </div>
</div>

<script>
$('#languageInput').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['action' => 'index']) ?>/language/'+$(this).val();
    }
);
</script>
