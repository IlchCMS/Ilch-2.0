<?php
$getVersions = $this->get('versions');
$doUpdate = $this->getRequest()->getParam('doupdate');
$doSave = $this->getRequest()->getParam('dosave');
$newVersion = $this->get('newVersion');
$certMissingOrExpired = $this->get('certMissingOrExpired');
$verificationFailed = $this->get('verificationFailed');
$updateSuccessfull = $this->get('updateSuccessfull');
?>

<h1><?=$this->getTrans('updateProcess') ?></h1>
<?php if ($getVersions != ''): ?>
    <div id="update">
    <p><?=$this->getTrans('versionNow') ?><?=$this->get('version') ?></p>
    <p><?=$this->getTrans('readReleas') ?></p>
    <?php if ($this->get('foundNewVersions')): ?>
        <p><?=$this->getTrans('foundNewVersions') ?><?=$newVersion ?></p>
        <?php if (!$doUpdate): ?>
            <?php if (is_file(ROOT_PATH.'/updates/Master-'.$newVersion.'.zip')): ?>
                <?php if (!$doSave): ?>
                    <p><?=$this->getTrans('isSave') ?></p>
                <?php else: ?>
                    <p><?=$this->getTrans('save') ?></p>
                <?php endif; ?>
                <p><?=$this->getTrans('updateReady') ?>
                    <a class="btn btn-primary"
                       href="<?=$this->getUrl(['action' => 'update', 'doupdate' => 'true']) ?>"><?=$this->getTrans('installNow') ?>
                    </a>
                </p>
            <?php else: ?>
                <?php if ($certMissingOrExpired): ?>
                    <p><?=$this->getTrans('certMissingOrExpired') ?>
                <?php endif; ?>
                <?php if ($verificationFailed): ?>
                    <p><?=$this->getTrans('verificationFailed') ?>
                <?php endif; ?>
                <p><?=$this->getTrans('doSave') ?>
                    <a class="btn btn-primary btn-xs"
                       href="<?=$this->getUrl(['action' => 'update', 'dosave' => 'true']) ?>"><?=$this->getTrans('doSaveNow') ?>
                    </a>
                </p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($doUpdate): ?>
            <?php if ($updateSuccessfull) : ?>
                <div class="list-files" id="list-files">
                    <?php foreach ($this->get('content') as $list): ?>
                        <p><?=$list ?></p>
                    <?php endforeach; ?>
                </div>
                <p><?=$this->getTrans('updateComplied') ?></p>
            <?php else: ?>
                <p><?=$this->getTrans('updateFailed') ?></p>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    </div>
<?php else: ?>
    <p><?=$this->getTrans('noReleas') ?></p>
<?php endif; ?>

<script>
$(document).ready(function() {
   var objDiv = document.getElementById("list-files");
    objDiv.scrollTop = objDiv.scrollHeight;
});
</script>
