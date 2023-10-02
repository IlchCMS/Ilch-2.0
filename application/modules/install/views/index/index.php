<?php

/** @var \Ilch\View $this */

/** @var array $languages */
$languages = $this->get('languages');

/** @var array $timezones */
$timezones = $this->get('timezones') ?? [];
?>
<div class="row mb-3">
    <label for="languageInput" class="col-xl-3 form-label">
        <?=$this->getTrans('chooseLanguage') ?>:
    </label>
    <div class="col-xl-4">
        <select class="form-select" id="languageInput" name="language">
            <?php foreach ($languages as $key => $value) : ?>
                <option <?=($this->getTranslator()->getLocale() == $key) ? 'selected="selected"' : '' ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="timezone" class="col-xl-3 form-label <?=$this->validation()->hasError('timezone') ? 'text-danger' : '' ?>">
        <?=$this->getTrans('timezone') ?>:
    </label>
    <div class="col-xl-4">
        <select class="form-select <?=$this->validation()->hasError('timezone') ? 'is-invalid' : '' ?>" id="timezone" name="timezone">
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
