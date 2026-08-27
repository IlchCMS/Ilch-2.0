<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('accountdata') ?></h1>
<form method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('checkoutContact') ? ' has-error' : '' ?>">
        <textarea class="form-control ckeditor"
                  id="ck_1"
                  toolbar="ilch_html"
                  name="checkoutContact"><?=$this->get('checkoutContact') ?></textarea>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('checkoutCurrency') ? ' has-error' : '' ?>">
        <label for="checkoutCurrency" class="col-form-label col-xl-2">
            <?=$this->getTrans('checkoutCurrency') ?>:
        </label>
        <div class="col-2">
            <select name="checkoutCurrency" id="checkoutCurrency" class="form-select">
                <?php
                foreach ($this->get('currencies') ?? [] as $currency) {
                    if ($this->get('checkoutCurrency') != $currency->getId()) {
                        echo '<option value="' . $currency->getId() . '">' . $this->escape($currency->getName()) . '</option>';
                    } else {
                        echo '<option value="' . $currency->getId() . '" selected>' . $this->escape($currency->getName()) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>
<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
