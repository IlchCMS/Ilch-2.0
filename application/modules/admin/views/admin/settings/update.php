<?php
$getVersions = $this->get('versions');
$doUpdate = $this->getRequest()->getParam('doupdate');
$doSave = $this->getRequest()->getParam('dosave');
$newVersion = $this->get('newVersion');
$missingRequirements = $this->get('missingRequirements');
$missingRequirementsMessages = $this->get('missingRequirementsMessages');
$certMissingOrExpired = $this->get('certMissingOrExpired');
$verificationFailed = $this->get('verificationFailed');
$updateSuccessfull = $this->get('updateSuccessfull');
?>

<h1><?=$this->getTrans('updateProcess') ?></h1>
<?php if ($getVersions != ''): ?>
    <div id="update">
    <p><?=$this->getTrans('versionNow') ?><?=$this->get('version') ?></p>
    <p><?=$this->getTrans('searchUpdate') ?></p>
    <?php if ($this->get('foundNewVersions')): ?>
        <p><?=$this->getTrans('foundNewVersions') ?><?=$newVersion ?></p>
        <?php if (!$missingRequirements) : ?>
            <?php if (!$doUpdate): ?>
                <?php if (is_file(ROOT_PATH.'/updates/Master-'.$newVersion.'.zip')): ?>
                    <?php if (!$doSave): ?>
                        <p><?=$this->getTrans('isSave') ?>
                            <a class="btn btn-primary"
                               href="<?=$this->getUrl(['action' => 'clearCache']) ?>"><?=$this->getTrans('clearCache') ?>
                            </a>
                        </p>
                    <?php else: ?>
                        <p><?=$this->getTrans('saveSuccess') ?></p>
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
                        <a class="btn btn-primary"
                           href="<?=$this->getUrl(['action' => 'update', 'dosave' => 'true']) ?>"><?=$this->getTrans('doSaveNow') ?>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($doUpdate): ?>
                <div class="list-files" id="list-files">
                    <?php foreach ($this->get('content') as $list): ?>
                        <p><?=$list ?></p>
                    <?php endforeach; ?>
                </div>
                <?php if ($updateSuccessfull) : ?>
                    <p><?=$this->getTrans('updateComplied') ?></p>
                <?php else: ?>
                    <p><?=$this->getTrans('updateFailed') ?></p>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <p><?=$this->getTrans('missingRequirements') ?></p>
            <?php foreach ($missingRequirementsMessages as $message): ?>
                <p><?=$message ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
    </div>
<?php else: ?>
    <p><?=$this->getTrans('noUpdateFound') ?></p>
<?php endif; ?>

<script>
$(document).ready(function() {
    let objDiv = document.getElementById("list-files");

    if (objDiv !== null) {
        objDiv.scrollTop = objDiv.scrollHeight;
    }
});
</script>
