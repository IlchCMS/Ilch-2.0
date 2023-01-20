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
        <title><?=$config->get('page_title') ?> - <?=$translator->trans('maintenanceMode') ?></title>

        <!-- META -->
        <meta charset="utf-8">
        <meta name="description" content="<?=$config->get('page_title') ?> - <?=$translator->trans('maintenanceMode') ?>">

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->getStaticUrl('img/favicon.ico') ?>">

        <!-- STYLES -->
        <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <?php if ($config->get('fontAwesomePro')) : ?>
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/all.min.css') ?>" rel="stylesheet">
    <?php else : ?>
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/fontawesome.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/solid.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/brands.min.css') ?>" rel="stylesheet">
    <?php endif; ?>
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v5-font-face.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('fortawesome/font-awesome/css/v4-shims.min.css') ?>" rel="stylesheet">
        <link href="<?=$this->getStaticUrl('css/ilch.css') ?>" rel="stylesheet">
        <link href="<?=$this->getBaseUrl('application/modules/admin/static/css/maintenance.css') ?>" rel="stylesheet">
        <link href="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/themes/ui-lightness/jquery-ui.min.css') ?>" rel="stylesheet">

        <!-- SCRIPTS -->
        <script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('npm-asset/jquery-ui/dist/jquery-ui.min.js') ?>"></script>
        <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
        <script src="<?=$this->getStaticUrl('js/countdown/jquery.countdown.min.js') ?>"></script>
    </head>
    <body>
        <div class="container">
            <div class="col-lg-offset-2 col-lg-8 col-md-12 col-sm-12 maintenance_container">
                <div class="logo" title="<?=$translator->trans('ilchCMSVersion', $config->get('version')) ?>"></div>
                <div class="maintenance_head"><?=$translator->trans('maintenanceMode') ?></div>
                <div class="hidden-xs">
                    <div class="countdownHead"><?=$translator->trans('maintenanceTime') ?></div>
                    <div class="countdown">
                        <?php if (strtotime($config->get('maintenance_date')) > strtotime($date->format('Y-m-d H:i:00', true))): ?>
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
                </div>
                <b><?=$translator->trans('maintenanceStatus') ?></b>
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-success active"
                        role="progressbar"
                        aria-valuenow="<?=$config->get('maintenance_status') ?>"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        style="width: <?=$config->get('maintenance_status') ?>%;">
                    </div>
                </div>
                <div class="install_content">
                    <?=$config->get('maintenance_text') ?>
                </div>

                <div class="save_box">
                    <a href="<?=$this->getUrl('admin/admin/login/index/') ?>" class="btn btn-primary pull-right">
                        Admin Login
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
$('#countdown').countdown('<?=date('Y/m/d H:i:s', strtotime($maintenanceTime)) ?>').on('update.countdown', function(event) {
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
