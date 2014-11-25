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
                <span class="text-info">
                    <i class="fa fa-info"></i> <?= $this->getTrans('opponents.additionalInfoTip') ?>
                </span>
            </p>
        </div>
    </div>
    <legend>Logo</legend>
    <div class="form-group">
        <div class="col-sm-4">
            <legend>Neues Logo hochladen</legend>
            <div>
                <!--<input type="file" name="logo">-->
                aktuell nicht möglich
            </div>
        </div>
        <div class="col-sm-8">
            <legend>Aus Medienbibliothek auswählen</legend>
            keine Medien vorhanden
        </div>
    </div>
    <div class="content_savebox">
        <button class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $this->getTrans('form.add') ?></button>
    </div>
</form>
