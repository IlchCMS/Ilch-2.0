<?php
if ($this->getUser()->getFirstName() != '') {
    $name = $this->getUser()->getFirstName().' '.$this->getUser()->getLastName();
} else {
    $name = $this->getUser()->getName();
}
?>

<h3><?=$this->getTrans('welcomeBack', $name) ?> !</h3>
<?=$this->getTrans('welcomeBackDescripton') ?>
<br /><br /><br />
<h3>
    <?=$this->getTrans('system') ?>
    <?php if ($this->get('foundNewVersions')) : ?>
        <span class="label label-danger"><?=$this->getTrans('notUpToDate') ?></span>
    <?php else: ?>
        <span class="label label-success"><?=$this->getTrans('upToDate') ?></span>
    <?php endif; ?>
</h3>
<br />
<table class="table">
    <tr>
        <td class="col-lg-1"><?=$this->getTrans('installedVersion') ?></td>
        <td><?=VERSION ?></td>
    </tr>
    <tr>
        <td><?=$this->getTrans('serverVersion') ?></td>
        <td><?=$this->get('newVersion') ?></td>
    </tr>
    <?php if ($this->get('foundNewVersions')) : ?>
    <tr>
        <td></td>
        <td>
            <!-- TODO: Remove this message when the update function is going live. -->
            Update function is not yet running in current stage of development.<br />
            <a href="<?=$this->getUrl(['controller' => 'update', 'action' => 'index'])?>"><?=$this->getTrans('updateNow') ?></a>
        </td>
    </tr>
    <?php endif; ?>
</table>
