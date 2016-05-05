<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
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
            $currency = array(
                // ISO 4217 code and symbol
                "EUR (€)",
                "USD ($)",
                "GBP (£)",
            );

            foreach ($currency as &$value) {
                if ($this->get('checkout_currency') != $value) {
                    echo '<option>'.$value.'</option>';
                } else {
                    echo '<option selected>'.$value.'</option>';
                }
            }
        ?>
        </select>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>
