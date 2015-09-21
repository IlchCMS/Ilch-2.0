<?php 
$config = \Ilch\Registry::get('config');
$translator = new \Ilch\Translator();
$translator->load(APPLICATION_PATH.'/modules/admin/translations/');
$maintenanceTime = $config->get('maintenance_date');
$date = new \Ilch\Date();
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <title><?=$config->get('page_title'); ?> - <?=$translator->trans('maintenanceMode') ?></title>

        <!-- META -->
        <meta charset="utf-8">        
        <meta name="description" content="<?=$config->get('page_title'); ?> - <?=$translator->trans('maintenanceMode') ?>">

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">

        <!-- STYLES -->
        <link href="<?=$this->getStaticUrl('css/bootstrap.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/font-awesome.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getBaseUrl('application/modules/install/static/css/install.css') ?>" rel="stylesheet">
        <link href="<?=$this->getBaseUrl('application/modules/admin/static/css/maintenance.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ui-lightness/jquery-ui.css') ?>" rel="stylesheet">

        <!-- SCRIPTS -->
        <script src="<?=$this->getStaticUrl('js/jquery.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/jquery-ui.js') ?>"></script>        
        <script src="<?=$this->getStaticUrl('js/bootstrap.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/countdown/jquery.countdown.js') ?>"></script>
    </head>
    <body>
        <div class="container install_container">
            <img class="logo" src="<?=$this->getStaticUrl('img/ilch_logo.png'); ?>" />
            <div class="maintranceHead"><?=$translator->trans('maintenanceMode') ?></div>
            <div class="countdownHead"><?=$translator->trans('maintenanceTime') ?></div>
            <div class="countdown">
                <?php if(strtotime($config->get('maintenance_date')) > strtotime($date->format('Y-m-d H:i:00', true))): ?>
                    <span id="countdown"></span>
                <?php else: ?>
                    <div class="countDays">
                        <span class="position">
                            <span style="top: 0px; opacity: 1;">00</span>
                        </span>
                        <span class="boxName">
                            <span><?=$translator->trans('maintenanceDays') ?></span>
                        </span>
                    </div>

                    <span class="points">:</span>

                    <div class="countHours">
                        <span class="position">
                            <span style="top: 0px; opacity: 1;">00</span>
                        </span>
                        <span class="boxName">
                            <span><?=$translator->trans('maintenanceHours') ?></span>
                        </span>
                    </div>

                    <span class="points">:</span>

                    <div class="countMinutes">
                        <span class="position">
                            <span style="top: 0px; opacity: 1;">00</span>
                        </span>
                        <span class="boxName">
                            <span><?=$translator->trans('maintenanceMinutes') ?></span>
                        </span>
                    </div>

                    <span class="points">:</span>

                    <div class="countSeconds">
                        <span class="position">
                            <span style="top: 0px; opacity: 1;">00</span>
                        </span>
                        <span class="boxName">
                            <span><?=$translator->trans('maintenanceSeconds') ?></span>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <b><?=$translator->trans('maintenanceStatus') ?></b>
            <div class="progress  progress-striped">
                <div class="progress-bar progress-bar-success active"
                    role="progressbar"
                    aria-valuenow="<?=$config->get('maintenance_status'); ?>"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    style="width: <?=$config->get('maintenance_status'); ?>%;">
                </div>
            </div>
            <div class="install_content">
                <?=$config->get('maintenance_text'); ?>
            </div>
        </div>
        <div class="container save_box">
            <form action="<?=$this->getUrl('admin/admin/login/index/'); ?>">
                <button type="submit" class="btn pull-right">
                    Admin Login
                </button>
            </form>
        </div>
    </body>
</html>

<script>
$('#countdown').countdown('<?=date("Y/m/d H:i:s", strtotime($maintenanceTime)) ?>').on('update.countdown', function(event) {
    var $this = $(this).html(event.strftime(''
        + '<div class="countDays">'
            + '<span class="position">'
                + '<span style="top: 0px; opacity: 1;">%D</span>'
            + '</span>'
            + '<span class="boxName">'
                + '<span><?=$translator->trans('maintenanceDays') ?></span>'
            + '</span>'
        + '</div>'

        + '<span class="points">:</span>'

        + '<div class="countHours">'
            + '<span class="position">'
                + '<span style="top: 0px; opacity: 1;">%H</span>'
            + '</span>'
            + '<span class="boxName">'
                + '<span><?=$translator->trans('maintenanceHours') ?></span>'
            + '</span>'
        + '</div>'

        + '<span class="points">:</span>'

        + '<div class="countMinutes">'
            + '<span class="position">'
                + '<span style="top: 0px; opacity: 1;">%M</span>'
            + '</span>'
            + '<span class="boxName">'
                + '<span><?=$translator->trans('maintenanceMinutes') ?></span>'
            + '</span>'
        + '</div>'

        + '<span class="points">:</span>'

        + '<div class="countSeconds">'
            + '<span class="position">'
                + '<span style="top: 0px; opacity: 1;">%S</span>'
            + '</span>'
            + '<span class="boxName">'
                + '<span><?=$translator->trans('maintenanceSeconds') ?></span>'
            + '</span>'
        + '</div>'));
});
</script>
