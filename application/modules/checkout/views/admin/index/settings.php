<form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
    <?=$this->getTokenField() ?>
    <legend><?=$this->getTrans('accountdata') ?></legend>
    <div class="form-group">
        <textarea class="form-control ckeditor"
                  id="ck_1"
                  toolbar="ilch_html"
                  name="checkout_contact"><?php if ($this->get('checkout_contact') != '') { echo $this->get('checkout_contact') ; } ?>
        </textarea>
    </div>
    <?=$this->getSaveBar('updateButton') ?>
</form>
