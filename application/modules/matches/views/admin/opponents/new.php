<script src="/application/modules/matches/static/js/media.js"></script>
<form id="newOpponentForm" method="post" class="form-horizontal" role="form">
    <legend><?= $this->getTrans('opponents.createNew') ?></legend>
    <?php $this->load("admin/opponents/_form.php", [
        'errors'        => $this->get('errors'),
        'errorFields'   => $this->get('errorFields'),
        'input'         => $this->get('userInput'),
    ]); ?>
    <div class="form-group">
        <label class="col-sm-2"><?= $this->getTrans('opponents.moreInfoQm') ?></label>
        <div class="col-sm-10">
            <p class="form-control-static">
                <span class="text-danger">(soon) </span>
                <span class="text-info">
                    <i class="fa fa-info"></i> <?= $this->getTrans('opponents.additionalInfoTip') ?>
                </span>
            </p>
        </div>
    </div>
    <legend>Logo</legend>
    <div class="form-group">
        <div class="col-sm-12">
            <legend><small>Aus Medienbibliothek auswählen</small></legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-inline">
                        <button type="button" id="mediaStateButton"
                                data-loading-text="Loading media..."
                                data-complete-text="Refresh media"
                                data-failed-text="Failed to load media, try again?"
                                class="btn btn-primary"
                                autocomplete="off"
                                data-url="<?= $this->getUrl(array('module' => 'matches', 'controller' => 'opponents', 'action' => 'mediaQuery')) ?>">
                            Load media
                        </button>
                        <a id="mediaUploadButton" href="#" data-url="<?= $this->getUrl(array('module' => 'media', 'controller' => 'index', 'action' => 'upload')) ?>"
                           class="btn">
                        Upload media
                        </a>
                    </div>
                </div>
            </div>

            <div id="mediaContainer" class="row">
                <h3 class="hidden">Media</h3>
            </div>
        </div>
    </div>
    <div class="content_savebox">
        <button class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $this->getTrans('form.add') ?></button>
    </div>
</form>

<div id="mediaUploadModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <b>Nach dem Upload kannst du auf 'Schließen' klicken und die Medien werden automatisch aktualisiert</b>
                <button id="closeMediaModalButton" type="button" class="btn btn-primary" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>
