<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
<?php echo $this->getTokenField(); ?>
    <legend>
    <?php echo $this->getTrans('accountdata'); ?>
    </legend>
    <div class="form-group">
        <textarea class="form-control"
                  id="ilch_html"
                  name="checkout_contact"><?php if ($this->get('checkout_contact') != '') { echo $this->get('checkout_contact') ; } ?>
        </textarea>
    </div>
    <?=$this->getSaveBar('editButton')?>
</form>