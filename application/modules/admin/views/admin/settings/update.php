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

<h1><?=$this->getTrans('ilchUpdate') ?></h1>
<?php if ($getVersions): ?>
    <div id="update">
    <?php if ($this->get('foundNewVersions')): ?>
        <div class="col-xl-6 col-lg-6">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col class="col-xl-2">
                        <col />
                    </colgroup>
                    <thead>
                    <tr>
                        <th></th>
                        <th><?=$this->getTrans('version') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$this->getTrans('installedVersion') ?></td>
                        <td><?=$this->get('version') ?></td>
                    </tr>
                    <?php if ($newVersion !== $this->get('newestVersion')) : ?>
                        <tr>
                            <td><?=$this->getTrans('nextVersion') ?></td>
                            <td>
                                <?php if ($newVersion) : ?>
                                    <?=$newVersion ?>
                                <?php elseif ($this->get('curlErrorOccurred')) : ?>
                                    <?=$this->getTrans('versionNA') ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td><?=$this->getTrans('newestVersion') ?></td>
                        <td>
                            <?php if ($this->get('newestVersion')) : ?>
                                <?=$this->get('newestVersion') ?>
                            <?php elseif ($this->get('curlErrorOccurred')) : ?>
                                <?=$this->getTrans('versionNA') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php if (!$missingRequirements) : ?>
                                <?php if (!$doUpdate): ?>
                                    <?php if (is_file($this->get('zipFileOfUpdate'))): ?>
                                        <?php if (!$doSave): ?>
                                            <p><?=$this->getTrans('isSave') ?>
                                                <a class="btn btn-primary showOverlay"
                                                   href="<?=$this->getUrl(['action' => 'clearCache'], null, true) ?>"><?=$this->getTrans('clearCache') ?>
                                                </a>
                                            </p>
                                        <?php else: ?>
                                            <p><?=$this->getTrans('updateSaveSuccess') ?></p>
                                        <?php endif; ?>
                                        <p><?=$this->getTrans('updateReady') ?>
                                            <a class="btn btn-primary showOverlay"
                                               href="<?=$this->getUrl(['action' => 'update', 'doupdate' => 'true'], null, true) ?>"><?=$this->getTrans('installNow') ?>
                                            </a>
                                        </p>
                                    <?php else: ?>
                                        <?php if ($certMissingOrExpired): ?>
                                            <p><?=$this->getTrans('certMissingOrExpired') ?></p>
                                        <?php endif; ?>
                                        <?php if ($verificationFailed): ?>
                                            <p><?=$this->getTrans('verificationFailed') ?></p>
                                        <?php endif; ?>
                                        <p>
                                            <a class="btn btn-primary showOverlay"
                                               href="<?=$this->getUrl(['action' => 'update', 'dosave' => 'true'], null, true) ?>"><?=$this->getTrans('doSaveNow') ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($doUpdate): ?>
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
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="accordion" id="accordionUpdateDetails">
                <?php if ($doUpdate): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLog" aria-expanded="false" aria-controls="collapseLog">
                                <?=$this->getTrans('viewUpdateInstallLog') ?>
                            </button>
                        </h2>
                        <div id="collapseLog" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="list-files" id="list-files">
                                    <?php foreach ($this->get('content') as $row): ?>
                                        <p><?=$row ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChangelog" aria-expanded="false" aria-controls="collapseChangelog">
                            <?=$this->getTrans('version') . ' ' . $newVersion ?>
                        </button>
                    </h2>
                    <div id="collapseChangelog" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <?=$this->alwaysPurify($getVersions[$newVersion]['changelog'] ?? '') ?: $this->getTrans('noChangelog') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <div class="loadingoverlay" hidden>
        <div class="d-flex justify-content-center">
          <div class="spinner-border" style="width: 6rem; height: 6rem;" role="status">
            <span class="visually-hidden"><?=$this->getTrans('processingPleaseWait') ?></span>
          </div>
        </div>
    </div>
<?php else: ?>
    <p><?=$this->getTrans('noUpdateFound') ?></p>
<?php endif; ?>

<script>
let delayedShow;

$(document).ready(function() {
    $(".showOverlay").on('click', function(){
        let loadingOverlay = $(".loadingoverlay");

        delayedShow = setTimeout(function(){
            loadingOverlay.removeAttr('hidden');
        }, 200);

        setTimeout(function(){
            loadingOverlay.attr('hidden', '');
        }, 30000);
    });

    clearTimeout(delayedShow);
    $(".loadingoverlay").attr('hidden', '');

    let objDiv = document.getElementById("list-files");

    if (objDiv !== null) {
        objDiv.scrollTop = objDiv.scrollHeight;
    }
});
</script>
