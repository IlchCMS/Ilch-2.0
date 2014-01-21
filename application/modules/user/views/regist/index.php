<?php if ($this->get('regist_accept') == '1') { ?>
    <form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => $this->getRequest()->getActionName())); ?>">
        <?php echo $this->getTokenField(); ?>
        <textarea class="form-control" style="height: 200px;" readonly><?php echo $this->escape($this->get('regist_rules')); ?></textarea>
        <label class="checkbox inline <?php if ($this->get('error') != '') { echo 'text-danger'; } ?>">
            <input type="checkbox" name="acceptRule" value="1"> <?php echo $this->getTrans('acceptRule'); ?>
        </label>
        <button type="submit" name="save" class="btn pull-right"><?php echo $this->getTrans('nextButton'); ?></button>
    </form>
<?php }else{
    echo 'Der Administrator hat festgelegt dass man sich nicht registrieren kann.';
}
?>