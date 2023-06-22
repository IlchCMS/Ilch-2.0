<?php

/** @var \Ilch\View $this */
?>
<h1><?=$this->getTrans('accountdata') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('checkoutContact') ? 'has-error' : '' ?>">
        <textarea class="form-control ckeditor"
                  id="ck_1"
                  toolbar="ilch_html"
                  name="checkoutContact"><?=$this->get('checkoutContact') ?></textarea>
    </div>
    <div class="form-group <?=$this->validation()->hasError('checkoutCurrency') ? 'has-error' : '' ?>">
        <label for="checkoutCurrency" class="control-label">
            <?=$this->getTrans('checkoutCurrency') ?>:
        </label>
        <select name="checkoutCurrency" id="checkoutCurrency">
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
    <?=$this->getSaveBar('updateButton') ?>
</form>
