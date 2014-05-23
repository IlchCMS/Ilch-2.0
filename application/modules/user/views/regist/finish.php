<?php if ($this->get('regist_confirm') == '1') { ?>
    <div class="row">
        <div class="col-lg-1 fa-4x check">
            <i class="fa fa-check-circle text-success" title=""></i>
        </div>
        <div class="col-lg-11">
            Vielen Dank <b><?php echo $_SESSION["name"]; ?></b>, dass Sie sich registriert haben.<br />
            Es wurde eine E-Mail an <b><?php echo $_SESSION["email"]; ?></b> geschickt, mit Anweisungen, wie Sie Ihr Benutzerkonto aktivieren können.
        </div>
    </div>
<?php }else{ ?>
    <div class="row">
        <div class="col-lg-1 fa-4x check">
            <i class="fa fa-check-circle text-success" title=""></i>
        </div>
        <div class="col-lg-11">
            Vielen Dank <b><?php echo $_SESSION["name"]; ?></b>, dass Sie sich registriert haben.<br />
            Sie können sich jetzt mit Ihren Benutzerdaten anmelden.
        </div>
    </div>
<?php
}
?>
