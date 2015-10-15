<?php include APPLICATION_PATH.'/modules/user/views/regist/navi.php'; ?>
<?php if ($this->get('regist_confirm') == '1'): ?>
    <div class="regist panel panel-default">
        <div class="panel-heading">
            <?=$this->getTrans('finish') ?>
        </div>
        <div class="panel-body">
            <div class="col-lg-1 fa-4x check">
                <i class="fa fa-check-circle text-success" title="<?=$this->getTrans('finish') ?>"></i>
            </div>
            Vielen Dank <b><?=$_SESSION["name"] ?></b>, dass Sie sich registriert haben.
            <br /><br />
            Es wurde eine E-Mail an <b><?=$_SESSION["email"] ?></b> geschickt, mit Anweisungen, wie Sie Ihr Benutzerkonto aktivieren können.
        </div>
    </div>
<?php else: ?>
    <div class="regist panel panel-default">
        <div class="panel-heading">
            <?=$this->getTrans('finish') ?>
        </div>
        <div class="panel-body">
            <div class="col-lg-1 fa-4x check">
                <i class="fa fa-check-circle text-success" title="<?=$this->getTrans('finish') ?>"></i>
            </div>
            Vielen Dank <b><?=$_SESSION["name"] ?></b>, dass Sie sich registriert haben.
            <br /><br />
            Sie können sich jetzt mit Ihren Benutzerdaten anmelden.
        </div>
    </div>
<?php endif; ?>
