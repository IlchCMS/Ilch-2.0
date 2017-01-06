<legend><?=$this->getTrans('accountdata') ?></legend>
<?php if ($this->validation()->hasErrors()): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->validation()->getErrorMessages() as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group <?=$this->validation()->hasError('checkoutContact') ? 'has-error' : '' ?>">
        <textarea class="form-control ckeditor"
                  id="ck_1"
                  toolbar="ilch_html"
                  name="checkoutContact"><?php if ($this->get('checkoutContact') != '') { echo $this->get('checkoutContact') ; } ?></textarea>
    </div>
    <div class="form-group <?=$this->validation()->hasError('checkoutCurrency') ? 'has-error' : '' ?>">
        <label for="checkoutCurrency" class="control-label">
            <?=$this->getTrans('checkoutCurrency') ?>:
        </label>
        <select name="checkoutCurrency" id="checkoutCurrency">
            <?php
            foreach ($this->get('currencies') as $currency) {
                if ($this->get('checkoutCurrency') != $currency->getId()) {
                    echo '<option value="'.$currency->getId().'">'.$this->escape($currency->getName()).'</option>';
                } else {
                    echo '<option value="'.$currency->getId().'" selected>'.$this->escape($currency->getName()).'</option>';
                }
            }
            ?>
        </select>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>
