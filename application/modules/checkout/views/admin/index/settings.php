<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName()]) ?>">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('accountdata') ?></legend>
    <div class="form-group">
        <textarea class="form-control ckeditor"
                  id="ck_1"
                  toolbar="ilch_html"
                  name="checkout_contact"><?php if ($this->get('checkout_contact') != '') { echo $this->get('checkout_contact') ; } ?>
        </textarea><br>
        <p><?=$this->getTrans('currencyOfCheckout') ?><br>
        <select name="checkout_currency">
        <?php
        foreach ($this->get('currencies') as $currency) {
            if ($this->get('checkout_currency') != $currency->getId()) {
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
