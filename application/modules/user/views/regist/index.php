<?php if ($this->get('regist_accept') == '1'): ?>
    <form class="form-horizontal" method="POST" action="<?=$this->getUrl(array('action' => $this->getRequest()->getActionName())) ?>">
        <?=$this->getTokenField() ?>
        <textarea class="form-control" style="height: 200px;" readonly><?=$this->escape($this->get('regist_rules')) ?></textarea>
        <label class="checkbox inline <?php if ($this->get('error') != '') { echo 'text-danger'; } ?>">
            <input type="checkbox" name="acceptRule" value="1"> <?=$this->getTrans('acceptRule') ?>
        </label>
        <button type="submit" name="save" class="btn pull-right"><?=$this->getTrans('nextButton') ?></button>
    </form>
<?php else: ?>
    Der Administrator hat festgelegt dass man sich nicht registrieren kann.
<?php endif; ?>
