<form id="editOpponentForm" method="post" class="form-horizontal" role="form">
    <legend><?= $this->getTrans('opponents.editOpponent') ?></legend>
    <?php $this->load("admin/opponents/_form.php", [
        // Parameters
    ]); ?>
    <legend>Logo</legend>
    <div class="form-group">
        <div class="col-sm-4">
            <legend>Aktuelles Logo</legend>
            <div class="text-center">
                kein Logo vorhanden
            </div>
        </div>
        <div class="col-sm-8">
            <legend>Neues Logo hochladen</legend>
            <div class="text-center">
                <input type="file" name="logo">
            </div>
        </div>
    </div>
</form>
